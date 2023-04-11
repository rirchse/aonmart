<?php

namespace App\Library;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Log;

class Utilities
{
    const INCREASE = 'INCREASE';
    const DECREASE = 'DECREASE';

    const VALIDATION_RULE_PASSWORD = 'password';

    const PAY_BY_WALLET = 1;
    const PAY_BY_COD = 2;
    const NAGAD = 3;
    const BKASH = 4;

    const PAYMENT_METHODS = [
        self::PAY_BY_WALLET => 'Pay By Wallet',
        self::PAY_BY_COD => 'Pay By Cash On Delivery'
    ];

    const SALE_PAYMENT_METHODS = [
        self::PAY_BY_COD => 'Pay By Cash',
//        self::PAY_BY_WALLET => 'Pay By Wallet',
        self::NAGAD => 'Pay By Nagad',
        self::BKASH => 'Pay By Bkash'
    ];

    const PAID = 1;
    const UNPAID = 2;
    const REFUNDED = 3;

    const PAYMENT_STATUSES = [
        self::PAID => "Paid",
        self::UNPAID => "Unpaid",
        self::REFUNDED => "Refunded"
    ];

    const SUCCESS_CODE = 200;
    const FAILED_CODE = 500;
    const NOT_FOUNT_ERROR_CODE = 404;
    const VALIDATION_ERROR_CODE = 406;

    /*
     * All function of this class should be Static
     */

    public static function checkPermissions(string|array $permissions, string $message = "Permission denied.")
    {
        abort_unless(
            Auth::user()->canAny($permissions),
            403,
            $message
        );
    }

    public static function getActiveStore()
    {
        abort_unless(Auth::check(), 403);

        if (Auth::user()->can('access.all.store')) {
            return Store::where('id', session('store_id'))
                ->first();
        }

        abort_unless(
            $store = Auth::user()->store,
            403
        );

        return $store;
    }

    public static function currency(): string
    {
        return 'TK';
    }

    public static function formattedAmount(int|float $amount = 0): string
    {
        $currency = self::currency();
        return "{$amount} {$currency}";
    }

    public static function getImageUrl($path = null, $type = null): string
    {
        if (empty($path)) {
            if ($type == 'user') {
                $path = 'default/default_user.png';
            } else {
                $path = 'default/default_image.png';
            }
        } else {
            $path = 'storage/' . $path;
        }
        return asset($path);
    }

    public static function isValidPhoneNumber(string $phone): bool
    {
        return preg_match('/(\+8801)[0-9]{9}/', $phone)
            or preg_match('/(01)[0-9]{9}/', $phone);
    }

    public static function isValidEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function getValidationRule(string $type, array $extra = [], array $reset = []): array
    {
        $validation_rules = [
            self::VALIDATION_RULE_PASSWORD => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (!empty($reset)) {
            $validation_rules[$type] = array_diff($validation_rules[$type], $extra);
        }

        if (!empty($extra)) {
            $validation_rules[$type] = array_merge($validation_rules[$type], $extra);
        }

        return $validation_rules[$type];
    }

    public static function availablePaymentMethods()
    {
        $paymentMethods = [];
        foreach (self::PAYMENT_METHODS as $key => $value) {
            $paymentMethods[] = [
                'title' => $value,
                'key' => $key
            ];
        }
        return $paymentMethods;
    }

    public static function generateCustomErrorLog(string $action, string $message, int $line, $extra = false)
    {
        $log_data = [
            'message' => $message,
            'line' => $line
        ];

        if ($extra != false) $log_data['trace'] = $extra;

        Log::error($action, $log_data);
    }

    public static function getUserBannedMessage(string $ban_reason)
    {
        return "Your account has been restricted for " . $ban_reason . ". Please contact AonMart Support. Thank you.";
    }
}
