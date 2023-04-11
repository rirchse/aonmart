<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Utilities;
use App\Models\User;
use App\Models\Ewallet;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class WalletController extends Controller
{
    public function showWalletDetails(User $user): View
    {
        Utilities::checkPermissions('wallet.add-money');
        $ewallets = Ewallet::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        return view('dashboard.wallet.details', compact('user', 'ewallets'));
    }

    public function addMoney(Request $request, User $user): RedirectResponse
    {
        Utilities::checkPermissions('wallet.add-money');

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('error', __($validator->errors()->first()));
        }

        Ewallet::create([
            'user_id' => $user->id,
            'amount' => $request->input('amount'),
            'created_by' => auth()->user()->id,
            'payment_method' => 1,
            'before_amount' => $user->balance
        ]);

        return back()->with('success', __('Wallet successfully updated.'));
    }

    public function approve($id)
    {
        Utilities::checkPermissions('wallet.approve');

        $wallet = Ewallet::find($id);
        $wallet->status = 1;
        $wallet->save();

        $user = User::find($wallet->user_id);
        $user->balance += $wallet->amount;
        $user->save();

        return back()->with('success', __('Wallet successfully approved.'));
    }

    public function destroy($wallet)
    {
        Utilities::checkPermissions('wallet.delete');

        $ewallet = Ewallet::find($wallet);
        if($ewallet->status == 0){
            $ewallet->delete();

            $user = User::find($ewallet->user_id);
            $user->balance -= $ewallet->amount;
            $user->save();

            return back()->with('success', __('The Wallet item successfully deleted.'));
        }
    }
}
