<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole('SALES') => $this->dashboardSales(),
            $user->hasRole('PROJECT') => $this->dashboardProject(),
            default => $this->dashboardBOD(),
        };
    }

    /**
     * Dashboard for BOD role
     */
    private function dashboardBOD()
    {
        $totalRevenue = $this->getRevenue();
        $activeProjects = $this->getActiveProjectsCount();
        $completionRate = $this->getCompletionRate();
        $performanceData = $this->getPerformanceData();
        $monthlyData = $this->getMonthlyData();
        $salesTeams = $this->getSalesTeams();
        $prospects = $this->getProspects();
        $reportMetrics = $this->getSalesReportMetrics();

        return view('dashboard.bod', compact(
            'totalRevenue',
            'activeProjects',
            'completionRate',
            'performanceData',
            'monthlyData',
            'salesTeams',
            'prospects',
            'reportMetrics'
        ));
    }

    /**
     * Dashboard for Sales role
     */
    private function dashboardSales()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $totalRevenue = $this->getRevenue($userId);
        $quotationCount = $this->getQuotationCount($userId);
        $monthlyData = $this->getMonthlyData($userId);
        $prospects = $this->getProspects($userId);
        $salesTarget = $user->currentYearSalesTarget;
        $reportMetrics = $this->getSalesReportMetrics($userId);

        return view('dashboard.sales', compact(
            'totalRevenue',
            'quotationCount',
            'monthlyData',
            'prospects',
            'salesTarget',
            'reportMetrics'
        ));
    }

    /**
     * Dashboard for Project role
     */
    private function dashboardProject()
    {
        $prospects = Prospect::all();
        $projects = Project::all();
        $selectedProject = request()->get('project_id')
            ? Project::find(request()->get('project_id'))
            : $projects->first();

        $statusBarangPercentage = $this->calculateStatusBarangPercentage($selectedProject);
        $projectProgressPercentage = $selectedProject?->calculateProgressPercentage() ?? 0;

        return view('dashboard.project', compact(
            'prospects',
            'projects',
            'selectedProject',
            'statusBarangPercentage',
            'projectProgressPercentage'
        ));
    }

    // ========== UNIFIED HELPER METHODS ==========

    /**
     * Get revenue - unified method for both global and user-specific revenue
     */
    private function getRevenue(?int $userId = null): float
    {
        $query = Quotation::where('status', 'accepted');

        if ($userId) {
            $query->where('created_by', $userId);
        }

        return $query->sum('total_amount');
    }

    /**
     * Get quotation count - unified method
     */
    private function getQuotationCount(?int $userId = null): int
    {
        $query = Quotation::query();

        if ($userId) {
            $query->where('created_by', $userId);
        }

        return $query->count();
    }

    /**
     * Get prospects with optional user filtering
     */
    private function getProspects(?int $userId = null)
    {
        $query = Prospect::with(['quotations', 'prospectStatus', 'preSalesPerson']);

        if ($userId) {
            $query->where('created_by', $userId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get total omset from converted prospects - unified method
     */
    private function getTotalOmsetFromProspects(?int $userId = null): float
    {
        $query = Prospect::with('quotations')->where('is_converted_to_project', true);

        if ($userId) {
            $query->whereHas('quotations', function ($q) use ($userId) {
                $q->where('created_by', $userId);
            });
        }

        return $query->get()->sum(function ($prospect) {
            return $prospect->quotations[0]?->calculateGrandTotalPrice()['grand_total_price'] ?? 0;
        });
    }

    /**
     * Get completion rate - unified method
     */
    private function getCompletionRate(?int $userId = null): float
    {
        $totalQuery = Quotation::query();
        $acceptedQuery = Quotation::where('status', 'accepted');

        if ($userId) {
            $totalQuery->where('created_by', $userId);
            $acceptedQuery->where('created_by', $userId);
        }

        $total = $totalQuery->count();
        $accepted = $acceptedQuery->count();

        return $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
    }

    /**
     * Calculate status barang (order items) completion percentage
     */
    private function calculateStatusBarangPercentage($project): float
    {
        if (! $project) {
            return 0;
        }

        $totalItems = $project->orderItems()->count();
        if ($totalItems === 0) {
            return 0;
        }

        $completeItems = $project->orderItems()->where('order_status', 'complete')->count();
        $partialItems = $project->orderItems()->where('order_status', 'partial')->count();
        $weightedCompletion = $completeItems + ($partialItems * 0.5);

        return round(($weightedCompletion / $totalItems) * 100, 1);
    }

    /**
     * Get count of active projects
     */
    private function getActiveProjectsCount(): int
    {
        return Project::count();
    }

    /**
     * Get performance data by company/region
     */
    private function getPerformanceData(): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $companies = Prospect::select('company')
            ->distinct()
            ->whereNotNull('company')
            ->pluck('company');

        return $companies->map(function ($company) use ($currentMonth, $currentYear) {
            $monthlyData = $this->getCompanyMonthlyData($company, $currentMonth, $currentYear);
            $yearlyData = $this->getCompanyYearlyData($company, $currentYear);

            return [
                'company' => $company,
                'monthly_target' => $monthlyData['target'],
                'completion' => $monthlyData['total'],
                'monthly_completion_rate' => $monthlyData['completion_rate'],
                'monthly_completion_color' => $this->getCompletionColor($monthlyData['completion_rate']),
                'yearly_target' => $yearlyData['target'],
                'accumulative_total' => $yearlyData['total'],
                'yearly_completion_rate' => $yearlyData['completion_rate'],
                'yearly_completion_color' => $this->getCompletionColor($yearlyData['completion_rate']),
            ];
        })->toArray();
    }

    /**
     * Get monthly chart data - unified method for both global and user-specific data
     */
    private function getMonthlyData(?int $userId = null): array
    {
        $currentYear = Carbon::now()->year;
        $omsetData = [];
        $grossProfitData = [];

        for ($month = 1; $month <= 12; $month++) {
            if ($userId) {
                $monthlyOmset = $this->getMonthlyOmsetForUser($userId, $month, $currentYear);
                $monthlyBasePrice = $this->getMonthlyBasePriceForUser($userId, $month, $currentYear);
                $monthlyGrossProfit = $monthlyOmset - $monthlyBasePrice;
            } else {
                $monthlyOmset = Quotation::whereHas('prospect', fn ($query) => $query->where('is_converted_to_project', true))
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $currentYear)
                    ->get()
                    ->sum(fn ($quotation) => $quotation->calculateGrandTotalPrice()['grand_total_price'] ?? 0);
                $monthlyBasePrice = Quotation::whereHas('prospect', fn ($query) => $query->where('is_converted_to_project', true))

                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $currentYear)
                    ->get()
                    ->sum(fn ($quotation) => $quotation->calculateGrandTotalBasePrice()['grand_total_base_price'] ?? 0);

                $monthlyGrossProfit = $monthlyOmset - $monthlyBasePrice;
            }

            $omsetData[] = $monthlyOmset;
            $grossProfitData[] = $monthlyGrossProfit;
        }

        return [
            'omset' => $omsetData,
            'gross_profit' => $grossProfitData,
        ];
    }

    /**
     * Get sales teams from users
     */
    private function getSalesTeams(): array
    {
        return User::whereNotNull('no_quotation')
            ->role('sales')
            ->where('no_quotation', '>', 0)
            ->select('id', 'name')
            ->get()
            ->map(fn ($user) => [
                'id' => strtolower(str_replace(' ', '', $user->name)),
                'name' => strtoupper($user->name),
            ])
            ->toArray();
    }

    // ========== HELPER METHODS ==========

    /**
     * Get company monthly data for performance calculation
     */
    private function getCompanyMonthlyData(string $company, int $month, int $year): array
    {
        $monthlyTotal = Quotation::whereHas('prospect', fn ($query) => $query->where('company', $company))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'accepted')
            ->sum('total_amount');

        $monthlyTarget = $monthlyTotal * 1.5;
        $completionRate = $monthlyTarget > 0 ? round(($monthlyTotal / $monthlyTarget) * 100, 1) : 0;

        return [
            'total' => $monthlyTotal,
            'target' => $monthlyTarget,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Get company yearly data for performance calculation
     */
    private function getCompanyYearlyData(string $company, int $year): array
    {
        $yearlyTotal = Quotation::whereHas('prospect', fn ($query) => $query->where('company', $company))
            ->whereYear('created_at', $year)
            ->where('status', 'accepted')
            ->sum('total_amount');

        $yearlyTarget = $yearlyTotal * 1.3;
        $completionRate = $yearlyTarget > 0 ? round(($yearlyTotal / $yearlyTarget) * 100, 1) : 0;

        return [
            'total' => $yearlyTotal,
            'target' => $yearlyTarget,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Get completion color based on rate
     */
    private function getCompletionColor(float $rate): string
    {
        return match (true) {
            $rate >= 80 => 'green',
            $rate >= 60 => 'blue',
            default => 'yellow'
        };
    }

    /**
     * Get monthly omset for specific user
     */
    private function getMonthlyOmsetForUser(int $userId, int $month, int $year): float
    {
        return Quotation::where('created_by', $userId)
            ->whereHas('prospect', fn ($query) => $query->where('is_converted_to_project', true))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->sum(fn ($quotation) => $quotation->calculateGrandTotalPrice()['grand_total_price'] ?? 0);
    }

    /**
     * Get monthly base price for specific user
     */
    private function getMonthlyBasePriceForUser(int $userId, int $month, int $year): float
    {
        return Quotation::where('created_by', $userId)
            ->whereHas('prospect', fn ($query) => $query->where('is_converted_to_project', true))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->sum(fn ($quotation) => $quotation->calculateGrandTotalBasePrice()['grand_total_base_price'] ?? 0);
    }

    /**
     * Get sales report metrics for dashboard
     */
    private function getSalesReportMetrics(?int $userId = null): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $salesTarget = null;

        if ($userId) {
            $user = User::find($userId);
            $salesTarget = $user?->currentYearSalesTarget;
        }

        $prospectQuery = $userId
            ? fn ($query) => $query->whereHas('quotations', fn ($q) => $q->where('created_by', $userId))
            : fn ($query) => $query->whereHas('quotations');

        $totalProspects = Prospect::where($prospectQuery)->count();
        $prospectsDeals = Prospect::where($prospectQuery)
            ->whereHas('prospectStatus', fn ($query) => $query->where('persentage', 100))
            ->count();
        $prospectsNewMonth = Prospect::where($prospectQuery)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $prospectsLost = Prospect::where($prospectQuery)
            ->whereHas('prospectStatus', function ($query) {
                $query->where('persentage', 0)
                    ->orWhere('name', 'like', '%lost%')
                    ->orWhere('name', 'like', '%gagal%');
            })
            ->count();

        $totalOmset = $this->getTotalOmsetFromProspects($userId);
        $targetAchievement = ($salesTarget && $salesTarget->target_yearly > 0)
            ? round(($totalOmset / $salesTarget->target_yearly) * 100)
            : 0;

        return [
            'target_achievement' => $targetAchievement,
            'total_prospects' => $totalProspects,
            'prospects_deal' => $prospectsDeals,
            'prospects_new_month' => $prospectsNewMonth,
            'prospects_lost' => $prospectsLost,
            'total_omset' => $totalOmset,
            'total_deal' => $totalOmset, // Same as omset based on original logic
        ];
    }

    /**
     * Get team data for dashboard API
     */
    public function getTeamData(string $teamId)
    {
        if ($teamId === 'all') {
            return $this->getAllTeamsData();
        }

        $user = User::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$teamId])
            // ->whereNotNull("no_quotation")
            // ->where("no_quotation", ">", 0)
            ->first();

        if (! $user) {
            return response()->json(['error' => 'Sales team not found'], 404);
        }

        $monthlyData = $this->getMonthlyData($user->id);
        $prospects = $this->getProspects($user->id);
        $teamRevenue = $this->getRevenue($user->id);
        $teamProjects = Prospect::whereHas('quotations', fn ($query) => $query->where('created_by', $user->id))->count();
        $teamCompletionRate = $this->getCompletionRate($user->id);
        $reportMetrics = $this->getSalesReportMetrics($user->id);

        return response()->json([
            'monthlyData' => $monthlyData,
            'prospects' => $prospects,
            'teamName' => strtoupper($user->name),
            'reportMetrics' => $reportMetrics,
        ]);
    }

    /**
     * Get all teams data for dashboard API
     */
    private function getAllTeamsData()
    {
        $monthlyData = $this->getMonthlyData();
        $prospects = $this->getProspects();
        $salesTeams = $this->getSalesTeams();

        // Calculate overall report metrics for all teams
        $reportMetrics = $this->getSalesReportMetrics();

        return response()->json([
            'monthlyData' => $monthlyData,
            'prospects' => $prospects,
            'teamName' => 'ALL TEAMS',
            'reportMetrics' => $reportMetrics,
        ]);
    }
}
