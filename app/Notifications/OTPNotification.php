<?php

namespace App\Notifications;

use App\Models\OTP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OTPNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private OTP $otp;

    public function __construct(OTP $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable): array
    {
        return [
            'mail'
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Your verification one time password: ' . $this->otp->code . '. It will be expire at ' . datetimeFormat($this->otp->expire_at) . '.')
            ->line('If you didn\'t request this, you can ignore this email or let us know.')
            ->line('Thank you for using our application!');
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
