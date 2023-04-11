<?php

namespace App\Http\Controllers;

use App\Models\EcommerceSetting;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\CouponProduct;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;

class SslCommerzPaymentController extends Controller
{
  public function exampleEasyCheckout()
  {
    return view('exampleEasycheckout');
  }

  public function exampleHostedCheckout()
  {
    return view('exampleHosted');
  }

  public function index(Request $request)
  {
    $isExists = true;
    $user = auth()->user();
    $eSetting = EcommerceSetting::query()->first();
    if ($request->input('shipping_address') == 'new_address') {
      $request->input('division_id') == 1 ? $shipping_cost = $eSetting->shipping_cost_dhaka : $shipping_cost = $eSetting->shipping_cost_outside;
      $shippingAddress = ShippingAddress::create([
        'user_id'     => $user->id,
        'division_id' => $request->input('division_id'),
        'city'        => $request->input('city'),
        'address'     => $request->input('address'),
        'country'     => 'Bangladesh',
        'area'        => $request->input('area'),
        'zip'         => $request->input('zip'),
        'contact'     => $request->input('contact'),
      ]);
    } else {
      $shippingAddress = ShippingAddress::query()->findOrFail($request->shipping_address);
      $shippingAddress->division_id == 1 ? $shipping_cost = $eSetting->shipping_cost_dhaka : $shipping_cost = $eSetting->shipping_cost_outside;
    }

    // make unique order number
    $orderNo = 'Ebook-' . substr(md5(time()), 0, 6);
    Order::query()->where('order_no', $orderNo)->exists() ? $orderNo .= '1' : null;

    $cart = null;
    if (Session::has('cart')) {
      $cart = new Cart(Session::get('cart'));
    }

    $totalDiscount = 0;
    $couponId = Session::has('coupon') ? Session::get('coupon')['coupon_id'] : '';

    if ($couponId != '' && $coupon = Coupon::query()->find($couponId)) {
      foreach ($cart->items as $product) {
        $coupon_discount = CouponProduct::query()->where('coupon_id', $coupon->id)->where('product_id', $product['item']['product_id'])->first();
        if ($coupon_discount) {
          $isExists = Order::query()->where('user_id', auth()->id())->where('coupon_id', $coupon->id)->exists();
          if (!$isExists) {
            $totalDiscount += $coupon->discount;
          }
        }
      }
    }

    $totalPrice = 0;
    $productName = '';
    if ($cart != null && $cart->items != null) {
      foreach ($cart->items as $product) {
        $productData = Product::query()->find($product['item']['product_id']);
        $totalPrice = intval($totalPrice) + (intval($product['item']['qty']) * intval($productData->sell_price ?? $productData->regular_price));
        $productName .= $productData->name . ',';
      }
    }

    $final_cost = (($totalPrice - $totalDiscount) + $shipping_cost);

    $post_data = [];
    $post_data['total_amount'] = $final_cost; // You cant not pay less than 10
    $post_data['currency'] = 'BDT';
    $post_data['tran_id'] = $orderNo; // tran_id must be unique

    // CUSTOMER INFORMATION
    $post_data['cus_name'] = $user->name;
    $post_data['cus_email'] = $user->email;
    $post_data['cus_add1'] = $shippingAddress->address;
    $post_data['cus_add2'] = '';
    $post_data['cus_city'] = $shippingAddress->city;
    $post_data['cus_state'] = $shippingAddress->area;
    $post_data['cus_postcode'] = $shippingAddress->zip;
    $post_data['cus_country'] = 'Bangladesh';
    $post_data['cus_phone'] = '8801XXXXXXXXX';
    $post_data['cus_fax'] = '';

    // SHIPMENT INFORMATION
    $post_data['ship_address_id'] = $shippingAddress->id;
    $post_data['ship_name'] = $user->name;
    $post_data['ship_add1'] = $shippingAddress->address;
    $post_data['ship_add2'] = '';
    $post_data['ship_city'] = $shippingAddress->city;
    $post_data['ship_state'] = $shippingAddress->area;
    $post_data['ship_postcode'] = $shippingAddress->zip;
    $post_data['ship_phone'] = $shippingAddress->contact;
    $post_data['ship_country'] = 'Bangladesh';

    $post_data['shipping_method'] = 'NO';
    $post_data['product_name'] = rtrim($productName, ',');
    $post_data['product_category'] = 'Book';
    $post_data['product_profile'] = 'physical-goods';

    $post_data['value_a'] = '';
    $post_data['value_b'] = '';
    $post_data['value_c'] = '';
    $post_data['value_d'] = '';

    // dd($isExists,$couponId);

    $update_product = DB::table('orders')
      ->where('order_no', $post_data['tran_id'])
      ->updateOrInsert([
        'user_id'             => $user->id,
        'coupon_id'           => !$isExists ? $couponId : null,
        'total'               => $post_data['total_amount'],
        'status'              => 'Pending',
        'shipping_address_id' => $post_data['ship_address_id'],
        'order_no'            => $post_data['tran_id'],
        'currency'            => $post_data['currency'],
      ]);
    $order_id = Order::where('order_no', $post_data['tran_id'])->first()->id;

    if ($cart != null && $cart->items != null) {
      foreach ($cart->items as $product) {
        $productQty = $product['item']['qty'];
        $product = Product::query()->findOrFail($product['item']['product_id']);
        $productDiscount = 0;

        if ($couponId != '' && !$isExists) {
          $productDiscount = $coupon_discount->Coupon->discount;
        }
        OrderProduct::query()->create([
          'order_id'            => $order_id,
          'product_id'          => $product->id,
          'qty'                 => $productQty,
          'price'               => intval($product->sell_price ?? $product->regular_price),
          'discount_sell_price' => intval($product->sell_price ?? $product->regular_price) - intval($productDiscount),
        ]);
      }
    }
//    Session::query()->forget('coupon');
//    Session::query()->forget('cart');

    $sslc = new SslCommerzNotification();
    $payment_options = $sslc->makePayment($post_data, 'hosted');

    if (!is_array($payment_options)) {
      print_r($payment_options);
      $payment_options = [];
    }
  }

  public function success(Request $request)
  {
    echo 'Transaction is Successful';

    $tran_id = $request->input('tran_id');
    $amount = $request->input('amount');
    $currency = $request->input('currency');

    $sslc = new SslCommerzNotification();

    $order_detials = Order::where('order_no', $tran_id)
      ->select('id', 'order_no', 'status', 'currency', 'total', 'user_id')->first();

    if ($order_detials->exception_status == 'Pending') {
      $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

      if ($validation == true) {
        $update_product = Order::query()->where('order_no', $tran_id)->first();
        $update_product->update(['status' => 'Processing']);

        $orderProducts = OrderProduct::query()->where('order_id', $update_product->id)->get();
        foreach ($orderProducts as $orderProduct) {
          $product = Product::query()->findOrFail($orderProduct->product_id);
          $product->stock_out += $orderProduct->qty;
          $product->save();
        }

        $info = [
          'type'    => 'order_completed',
          'message' => 'Your order has been completed'
        ];
        Mail::to($order_detials->User->email)->send(new OrderMail($info));
        Auth::login($order_detials->User);

        return redirect(url('/'))->with('message', 'Transaction is successfully Completed');
      } else {
        $update_product = DB::table('orders')->where('order_no', $tran_id)->update(['status' => 'Failed']);
        echo 'validation Fail';
      }
    } elseif ($order_detials->exception_status == 'Processing' || $order_detials->exception_status == 'Complete') {
      $info = [
        'type'    => 'order_completed',
        'message' => 'Your order has been completed'
      ];
      Mail::to($order_detials->User->email)->send(new OrderMail($info));
      Auth::login($order_detials->User);

      return redirect(url('/'))->with('message', 'Transaction is successfully Completed');
    } else {
      return redirect(url('/'))->with('message', 'Invalid Transaction');
    }
  }

  public function fail(Request $request)
  {
    $tran_id = $request->input('tran_id');

    $order_detials = DB::table('orders')->where('order_no', $tran_id)->select('order_no', 'status', 'currency', 'total')->first();

    if ($order_detials->exception_status == 'Pending') {
      $update_product = DB::table('orders')->where('order_no', $tran_id)->update(['status' => 'Failed']);
      echo 'Transaction is Falied';
    } elseif ($order_detials->exception_status == 'Processing' || $order_detials->exception_status == 'Complete') {
      echo 'Transaction is already Successful';
    } else {
      echo 'Transaction is Invalid';
    }
  }

  public function cancel(Request $request)
  {
    $tran_id = $request->input('tran_id');

    $order_detials = DB::table('orders')
      ->where('order_no', $tran_id)
      ->select('order_no', 'status', 'currency', 'total')->first();

    if ($order_detials->exception_status == 'Pending') {
      $update_product = DB::table('orders')
        ->where('order_no', $tran_id)
        ->update(['status' => 'Canceled']);
      echo 'Transaction is Cancel';
    } elseif ($order_detials->exception_status == 'Processing' || $order_detials->exception_status == 'Complete') {
      echo 'Transaction is already Successful';
    } else {
      echo 'Transaction is Invalid';
    }
  }

  public function ipn(Request $request)
  {
    //Received all the payement information from the gateway
    if ($request->input('tran_id')) { //Check transation id is posted or not.
      $tran_id = $request->input('tran_id');

      //Check order status in order tabel against the transaction id or order id.
      $order_details = DB::table('orders')
        ->where('order_no', $tran_id)
        ->select('order_no', 'status', 'currency', 'total')->first();

      if ($order_details->exception_status == 'Pending') {
        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
        if ($validation == true) {
          $update_product = DB::table('orders')
            ->where('order_no', $tran_id)
            ->update(['status' => 'Processing']);

          echo 'Transaction is successfully Completed';
        } else {
          $update_product = DB::table('orders')
            ->where('order_no', $tran_id)
            ->update(['status' => 'Failed']);

          echo 'validation Fail';
        }
      } elseif ($order_details->exception_status == 'Processing' || $order_details->exception_status == 'Complete') {
        //That means Order status already updated. No need to udate database.

        echo 'Transaction is already successfully Completed';
      } else {
        //That means something wrong happened. You can redirect customer to your product page.

        echo 'Invalid Transaction';
      }
    } else {
      echo 'Invalid Data';
    }
  }
}
