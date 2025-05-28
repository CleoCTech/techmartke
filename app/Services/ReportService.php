<?php

namespace App\Services;

use App\Models\FiscalYear;
use App\Models\FiscalPeriod;
use App\Models\Report;

class ReportService
{
    /**
     * Generate a financial report for a fiscal year.
     */
    public function generateFinancialReportForFiscalYear(FiscalYear $fiscalYear): Report
    {
        // Calculate financial data
        $totalIncome = $this->calculateTotalIncome($fiscalYear);
        $totalExpenses = $this->calculateTotalExpenses($fiscalYear);
        $netProfit = $totalIncome - $totalExpenses;

        // Prepare report data
        $reportData = [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];

        // Create and save the report
        return Report::create([
            'fiscal_year_id' => $fiscalYear->id,
            'type' => 'financial',
            'data' => $reportData,
        ]);
    }

    /**
     * Generate a financial report for a fiscal period.
     */
    public function generateFinancialReportForFiscalPeriod(FiscalPeriod $fiscalPeriod): Report
    {
        // Calculate financial data
        $totalIncome = $this->calculateTotalIncomeForPeriod($fiscalPeriod);
        $totalExpenses = $this->calculateTotalExpensesForPeriod($fiscalPeriod);
        $netProfit = $totalIncome - $totalExpenses;

        // Prepare report data
        $reportData = [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];

        // Create and save the report
        return Report::create([
            'fiscal_year_id' => $fiscalPeriod->fiscal_year_id,
            'fiscal_period_id' => $fiscalPeriod->id,
            'type' => 'financial',
            'data' => $reportData,
        ]);
    }

    /**
     * Calculate total income for a fiscal year.
     */
    private function calculateTotalIncome(FiscalYear $fiscalYear): float
    {
        // Example: Sum income from all transactions in the fiscal year
        return $fiscalYear->transactions()->where('type', 'income')->sum('amount');
    }

    /**
     * Calculate total expenses for a fiscal year.
     */
    private function calculateTotalExpenses(FiscalYear $fiscalYear): float
    {
        // Example: Sum expenses from all transactions in the fiscal year
        return $fiscalYear->transactions()->where('type', 'expense')->sum('amount');
    }

    /**
     * Calculate total income for a fiscal period.
     */
    private function calculateTotalIncomeForPeriod(FiscalPeriod $fiscalPeriod): float
    {
        // Example: Sum income from all transactions in the fiscal period
        return $fiscalPeriod->transactions()->where('type', 'income')->sum('amount');
    }

    /**
     * Calculate total expenses for a fiscal period.
     */
    private function calculateTotalExpensesForPeriod(FiscalPeriod $fiscalPeriod): float
    {
        // Example: Sum expenses from all transactions in the fiscal period
        return $fiscalPeriod->transactions()->where('type', 'expense')->sum('amount');
    }
}