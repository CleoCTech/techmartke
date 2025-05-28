<?php

namespace App\Observers;

use App\Models\FiscalYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FiscalYearObserver
{
    /**
     * Handle the FiscalYear "created" event.
     */
    public function created(FiscalYear $fiscalYear): void
    {
        DB::transaction(function () use ($fiscalYear) {
            if ($fiscalYear->is_current) {
                FiscalYear::where('id', '!=', $fiscalYear->id)->update(['is_current' => false]);
            }
    
            $quarters = [
                ['period_name' => 'Q1', 'start_date' => $fiscalYear->start_date, 'end_date' => date('Y-m-d', strtotime('+2 months', strtotime($fiscalYear->start_date))), 'status' => 'pending'],
                ['period_name' => 'Q2', 'start_date' => date('Y-m-d', strtotime('+3 months', strtotime($fiscalYear->start_date))), 'end_date' => date('Y-m-d', strtotime('+5 months', strtotime($fiscalYear->start_date))), 'status' => 'pending'],
                ['period_name' => 'Q3', 'start_date' => date('Y-m-d', strtotime('+6 months', strtotime($fiscalYear->start_date))), 'end_date' => date('Y-m-d', strtotime('+8 months', strtotime($fiscalYear->start_date))), 'status' => 'pending'],
                ['period_name' => 'Q4', 'start_date' => date('Y-m-d', strtotime('+9 months', strtotime($fiscalYear->start_date))), 'end_date' => $fiscalYear->end_date, 'status' => 'pending'],
            ];
    
            foreach ($quarters as $quarter) {
                $fiscalYear->fiscalPeriods()->create($quarter);
            }
        });
    }

    /**
     * Handle the FiscalYear "updated" event.
     */
    public function updated(FiscalYear $fiscalYear): void
    {
        // If this year is being marked as current, unmark all others
        if ($fiscalYear->isDirty('is_current') && $fiscalYear->is_current) {
            FiscalYear::where('id', '!=', $fiscalYear->id)->update(['is_current' => false]);
            Log::info("Fiscal year {$fiscalYear->year} is set as current.");
        }

        // If the fiscal year's start or end date is updated, ensure it doesn't overlap with other fiscal years
        if ($fiscalYear->isDirty(['start_date', 'end_date'])) {
            $overlappingYears = FiscalYear::where('id', '!=', $fiscalYear->id)
                ->where(function ($query) use ($fiscalYear) {
                    $query->whereBetween('start_date', [$fiscalYear->start_date, $fiscalYear->end_date])
                          ->orWhereBetween('end_date', [$fiscalYear->start_date, $fiscalYear->end_date])
                          ->orWhere(function ($query) use ($fiscalYear) {
                              $query->where('start_date', '<', $fiscalYear->start_date)
                                    ->where('end_date', '>', $fiscalYear->end_date);
                          });
                })
                ->exists();

            if ($overlappingYears) {
                throw new \Exception('Fiscal year dates overlap with an existing fiscal year.');
            }
        }
    }

    /**
     * Handle the FiscalYear "deleted" event.
     */
    public function deleted(FiscalYear $fiscalYear): void
    {
        // If the deleted fiscal year was the current one, mark the most recent fiscal year as current
        if ($fiscalYear->is_current) {
            $mostRecentFiscalYear = FiscalYear::latest('start_date')->first();
            if ($mostRecentFiscalYear) {
                $mostRecentFiscalYear->update(['is_current' => true]);
            }
        }
    }

    /**
     * Handle the FiscalYear "restored" event.
     */
    public function restored(FiscalYear $fiscalYear): void
    {
        // If the restored fiscal year is marked as current, unmark all others
        if ($fiscalYear->is_current) {
            FiscalYear::where('id', '!=', $fiscalYear->id)->update(['is_current' => false]);
        }
    }

    /**
     * Handle the FiscalYear "force deleted" event.
     */
    public function forceDeleted(FiscalYear $fiscalYear): void
    {
        // If the force-deleted fiscal year was the current one, mark the most recent fiscal year as current
        if ($fiscalYear->is_current) {
            $mostRecentFiscalYear = FiscalYear::latest('start_date')->first();
            if ($mostRecentFiscalYear) {
                $mostRecentFiscalYear->update(['is_current' => true]);
            }
        }
    }
}
