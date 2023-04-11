<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\EcommerceSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
  public function editCompanySetting()
  {
    abort_unless(auth()->user()->hasRole('Super Admin'), 403);
    $company_setting = CompanySetting::first();
    return view('admin.settings.company_setting', compact('company_setting'));
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function updateCompanySetting(Request $request)
  {
    abort_unless(auth()->user()->hasRole('Super Admin'), 403);
    $companySetting = CompanySetting::first();
    $request->validate([
      'name'         => 'required|string',
      'mobile1'      => 'nullable|digits_between:10,11',
      'mobile2'      => 'nullable|digits_between:10,11',
      'email'        => 'nullable|email',
      'about'        => 'nullable|string',
      'about_footer' => 'nullable|string',
      'facebook'     => 'nullable|string',
      'twitter'      => 'nullable|string',
      'instagram'    => 'nullable|string',
      'whatsapp'     => 'nullable|string',
      'location'     => 'nullable|string',
      'logo'         => 'nullable|image|mimes:jpeg,png,jpg|max:512',
      'footer_logo'  => 'nullable|image|mimes:jpeg,png,jpg|max:512',
    ]);

    $logo = $companySetting->logo;
    $footer_logo = $companySetting->footer_logo;

    // working with logo
    if ($request->hasFile('logo')) {
      $filePath = 'storage/' . $companySetting->logo;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $fileName = Rand() . '.' . $request->logo->getClientOriginalExtension();
      $logo = $request->logo->storeAs('images/logo', $fileName, 'public');
    }

    // working with footer logo
    if ($request->hasFile('footer_logo')) {
      $filePath = 'storage/' . $companySetting->footer_logo;
      if (File::exists($filePath)) {
        File::delete($filePath);
      }

      $fileName = Rand() . '.' . $request->footer_logo->getClientOriginalExtension();
      $footer_logo = $request->footer_logo->storeAs('images/logo', $fileName, 'public');
    }

    $companySetting->update([
      'name'         => $request->input('name'),
      'mobile1'      => $request->input('mobile1'),
      'mobile2'      => $request->input('mobile2'),
      'email'        => $request->input('email'),
      'about'        => $request->input('about'),
      'about_footer' => $request->input('about_footer'),
      'facebook'     => $request->input('facebook'),
      'twitter'      => $request->input('twitter'),
      'instagram'    => $request->input('instagram'),
      'whatsapp'     => $request->input('whatsapp'),
      'location'     => $request->input('location'),
      'logo'         => $logo,
      'footer_logo'  => $footer_logo,
    ]);

    return back()->with('success', 'Setting Updated');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function editEcommerceSetting()
  {
    abort_unless(auth()->user()->hasRole('Super Admin'), 403);
    $ecommerce_setting = EcommerceSetting::first();
    // dd($ecommerce_setting);
    return view('admin.settings.ecommerce_setting', compact('ecommerce_setting'));
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function updateEcommerceSetting(Request $request)
  {
    abort_unless(auth()->user()->hasRole('Super Admin'), 403);
    $ecommerceSetting = EcommerceSetting::first();
    $request->validate([
      'currency'              => 'nullable|string',
      'shipping_cost_dhaka'   => 'nullable|integer',
      'shipping_cost_outside' => 'nullable|integer',
      'tax'                   => 'nullable|integer',
      'delivery_time_dhaka'   => 'nullable|integer',
      'delivery_time_outside' => 'nullable|integer',
      'note'                  => 'nullable|string',
    ]);

    $ecommerceSetting->update([
      'currency'              => $request->input('currency'),
      'shipping_cost_dhaka'   => $request->input('shipping_cost_dhaka'),
      'shipping_cost_outside' => $request->input('shipping_cost_outside'),
      'tax'                   => $request->input('tax'),
      'delivery_time_dhaka'   => $request->input('delivery_time_dhaka'),
      'delivery_time_outside' => $request->input('delivery_time_outside'),
      'note'                  => $request->input('note'),
    ]);

    return back()->with('success', 'Setting Updated');
  }
}
