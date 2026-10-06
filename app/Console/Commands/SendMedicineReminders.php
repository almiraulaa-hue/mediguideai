<?php

namespace App\Console\Commands;

use App\Models\MedicineReminder;
use App\Notifications\MedicineReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMedicineReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Kirim push notification untuk pengingat obat yang jatuh tempo';

    public function handle(): int
    {
        $now = now();
        $today = $now->toDateString();

        $due = MedicineReminder::with('user')
            ->where('is_active', true)
            ->where('notify_enabled', true)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('last_notified_date')
                  ->orWhereDate('last_notified_date', '<', $today);
            })
            ->get()
            ->filter(fn ($r) => $this->isDueNow($r, $now))
            ->filter(fn ($r) => $this->isScheduledToday($r, $now))
            ->reject(fn ($r) => $r->today_status === 'diminum');

        foreach ($due as $reminder) {
            $reminder->user?->notify(new MedicineReminderNotification($reminder));
            $reminder->update(['last_notified_date' => $today]);
        }

        $this->info("{$due->count()} reminder dikirim.");

        return self::SUCCESS;
    }

    /**
     * Toleran terhadap cron yang cuma jalan tiap 5 menit (minimum interval Railway),
     * bukan cuma cocok persis di menit yang sama seperti schedule:work di lokal.
     */
    private function isDueNow(MedicineReminder $r, Carbon $now): bool
    {
        $scheduledAt = Carbon::parse($now->toDateString().' '.$r->time, $now->timezone);

        return $scheduledAt->lte($now) && $scheduledAt->gte($now->copy()->subMinutes(5));
    }

    private function isScheduledToday(MedicineReminder $r, Carbon $now): bool
    {
        $days = $r->repeat_days;

        if (empty($days)) {
            return true;
        }

        $today = $now->dayOfWeek;
        $names = [
            0 => ['minggu', 'ming', 'sun', 'sunday'],
            1 => ['senin', 'sen', 'mon', 'monday'],
            2 => ['selasa', 'sel', 'tue', 'tuesday'],
            3 => ['rabu', 'rab', 'wed', 'wednesday'],
            4 => ['kamis', 'kam', 'thu', 'thursday'],
            5 => ['jumat', "jum'at", 'jum', 'fri', 'friday'],
            6 => ['sabtu', 'sab', 'sat', 'saturday'],
        ];

        foreach ($days as $d) {
            $d = strtolower(trim((string) $d));

            if (is_numeric($d) ? (int) $d === $today : in_array($d, $names[$today], true)) {
                return true;
            }
        }

        return false;
    }
}