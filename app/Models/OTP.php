<?php

namespace App\Models;

use App\Notifications\OTPNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'otp';
    protected $fillable = ['user_id', 'code', 'status'];

    public function expireAt(): Attribute
    {
        return Attribute::make(fn($value) => $this->created_at->addMinute(config('auth.otp.lifetime')));
    }

    public static function send(User $user): OTP
    {
        $otp = self::findActiveOTP($user->id);
        if (empty($otp)) {
            $otp = self::generateNewOTP($user->id);

            if (!empty($user->email)) {
                $user->notify(new OTPNotification(
                    $otp
                ));
            }
        }
        return $otp;
    }

    public static function generateNewOTP(int $user_id): OTP
    {
        return self::create([
            'user_id' => $user_id,
            'code' => rand(000000, 999999)
        ]);
    }

    public static function matchOTP(int $user_id, string $otp): bool
    {
        $min_datetime = Carbon::now()
            ->subMinute(
                config('auth.otp.lifetime')
            );

        if (
            $otp = self::where('user_id', $user_id)
                ->where('code', $otp)
                ->where('created_at', '>=', $min_datetime)
                ->latest()
                ->first()
        ) {
            $otp->exception_status = true;
            $otp->save();
        }

        return (bool)$otp;
    }

    public static function findActiveOTP(int $user_id): OTP|null
    {
        $min_datetime = Carbon::now()
            ->subMinute(
                config('auth.otp.lifetime')
            );
        return self::where('user_id', $user_id)
            ->where('created_at', '>=', $min_datetime)
            ->latest()
            ->first();
    }

    public static function findValidOTP(int $user_id, string $otp): OTP|null
    {
        return self::where('user_id', $user_id)
            ->where('code', $otp)
            ->active()
            ->latest()
            ->first();
    }

    public static function destroyOTP(int $user_id, string $otp): ?bool
    {
        return self::where('user_id', $user_id)
            ->where('code', $otp)
            ->active()
            ->latest()
            ->delete();
    }
}
