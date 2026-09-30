<?php

namespace App\Notifications;

use App\Models\MedicineReminder;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class MedicineReminderNotification extends Notification
{
    public function __construct(protected MedicineReminder $reminder) {}

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $dosage = $this->reminder->dosage ? " - {$this->reminder->dosage}" : '';

        return (new WebPushMessage)
            ->title('Waktunya Minum Obat! 💊')
            ->icon('/images/icon-192.png')
            ->body("{$this->reminder->medicine_name}{$dosage}")
            ->data(['url' => route('reminder.index')])
            ->options(['TTL' => 3600]);
    }
}