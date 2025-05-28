<?php

namespace App\Observers;

use App\Models\FiscalPeriod;
use App\Notifications\FiscalPeriodEndingNotification;

class FiscalPeriodObserver
{
    /**
     * Handle the FiscalPeriod "created" event.
     */
    public function created(FiscalPeriod $fiscalPeriod): void
    {
        //
    }

    /**
     * Handle the FiscalPeriod "updated" event.
     */
    public function updated(FiscalPeriod $fiscalPeriod): void
    {
        $today = now()->format('Y-m-d');
        if ($today > $fiscalPeriod->end_date) {
            $fiscalPeriod->status = 'completed';
        } elseif ($today >= $fiscalPeriod->start_date && $today <= $fiscalPeriod->end_date) {
            $fiscalPeriod->status = 'active';
        } else {
            $fiscalPeriod->status = 'inactive';
        }
        if ($fiscalPeriod->end_date == now()->addDays(7)->format('Y-m-d')) {
            $fiscalPeriod->user->notify(new FiscalPeriodEndingNotification($fiscalPeriod));
        }
    }

    /**
     * Handle the FiscalPeriod "deleted" event.
     */
    public function deleted(FiscalPeriod $fiscalPeriod): void
    {
        //
    }

    /**
     * Handle the FiscalPeriod "restored" event.
     */
    public function restored(FiscalPeriod $fiscalPeriod): void
    {
        //
    }

    /**
     * Handle the FiscalPeriod "force deleted" event.
     */
    public function forceDeleted(FiscalPeriod $fiscalPeriod): void
    {
        //
    }
}
