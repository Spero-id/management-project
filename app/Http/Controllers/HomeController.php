<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Installation;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole('SALES') => $this->dashboardSales(),
            $user->hasRole('PROJECT') => $this->dashboardProject(),
            // $user->hasRole('logistic') => $this->dashboardLogistic(),
            // $user->hasRole('finance') => $this->dashboardFinance(),
            default => $this->dashboardBOD(),
        };
    }

    /**
     * Dashboard for BOD role
     */
    private function dashboardBOD()
    {
        $totalRevenue = $this->getTotalRevenue();
        $activeProjects = $this->getActiveProjectsCount();
        $completionRate = $this->getCompletionRate();
        $performanceData = $this->getPerformanceData();
        $monthlyData = $this->getMonthlyData();
        $salesTeams = $this->getSalesTeams();
        $prospects = $this->getProspects();

        return view('dashboard.bod', compact(
            'totalRevenue',
            'activeProjects',
            'completionRate',
            'performanceData',
            'monthlyData',
            'salesTeams',
            'prospects'
        ));
    }

    /**
     * Dashboard for Sales role
     */
    private function dashboardSales()
    {
        $userId = Auth::id();
        $totalRevenue = $this->getSalesRevenue($userId);
        $quotationCount = $this->getSalesQuotationCount($userId);
        $acceptanceRate = $this->getSalesAcceptanceRate($userId);
        $performanceData = $this->getSalesPerformanceData($userId);
        $monthlyData = $this->getSalesMonthlyData($userId);
        $prospects = $this->getProspects($userId);

        return view('dashboard.sales', compact(
            'totalRevenue',
            'quotationCount',
            'acceptanceRate',
            'performanceData',
            'monthlyData',
            'prospects'
        ));
    }

    /**
     * Dashboard for Project role
     */
    private function dashboardProject()
    {

        $prospects = Prospect::all();
        $projects = Project::all();
        if (request()->get('project_id') == null) {
            $selectedProject = $projects->first();
        } else {
            $selectedProject = Project::find(request()->get('project_id'));
        }

        return view('dashboard.project', compact(
            'prospects',
            'projects',
            'selectedProject'
        ));
    }

    /**
     * Dashboard for Logistic role
     */
    private function dashboardLogistic()
    {
        $totalInstallations = $this->getTotalInstallations();
        $pendingInstallations = $this->getPendingInstallations();
        $completedInstallations = $this->getCompletedInstallations();
        $installationSchedule = $this->getInstallationSchedule();
        $accommodationStatus = $this->getAccommodationStatus();
        $performanceMetrics = $this->getLogisticPerformanceMetrics();

        return view('dashboard.logistic', compact(
            'totalInstallations',
            'pendingInstallations',
            'completedInstallations',
            'installationSchedule',
            'accommodationStatus',
            'performanceMetrics'
        ));
    }

    /**
     * Dashboard for Finance role
     */
    private function dashboardFinance()
    {
        $totalRevenue = $this->getTotalRevenue();
        $totalExpenses = $this->getTotalExpenses();
        $profitMargin = $this->getProfitMargin();
        $invoiceStatus = $this->getInvoiceStatus();
        $paymentAnalysis = $this->getPaymentAnalysis();
        $quotationMetrics = $this->getQuotationMetrics();
        $cashflowTrend = $this->getCashflowTrend();

        return view('dashboard.finance', compact(
            'totalRevenue',
            'totalExpenses',
            'profitMargin',
            'invoiceStatus',
            'paymentAnalysis',
            'quotationMetrics',
            'cashflowTrend'
        ));
    }

    /**
     * Get total revenue from accepted quotations
     */
    private function getTotalRevenue()
    {
        return Quotation::where('status', 'accepted')->sum('total_amount');
    }

    /**
     * Get count of active projects
     */
    private function getActiveProjectsCount()
    {
        return Project::count();
    }

    /**
     * Get overall completion rate based on accepted quotations vs total quotations
     */
    private function getCompletionRate()
    {
        $totalQuotations = Quotation::count();
        $acceptedQuotations = Quotation::where('status', 'accepted')->count();

        return $totalQuotations > 0 ? round(($acceptedQuotations / $totalQuotations) * 100, 1) : 0;
    }

    /**
     * Get performance data by company/region
     */
    private function getPerformanceData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get companies from prospects and their performance
        $companies = Prospect::select('company')
            ->distinct()
            ->whereNotNull('company')
            ->get()
            ->pluck('company');

        $performanceData = [];

        foreach ($companies as $company) {
            // Get monthly data
            $monthlyQuotations = Quotation::whereHas('prospect', function ($query) use ($company) {
                $query->where('company', $company);
            })
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);

            $monthlyTotal = $monthlyQuotations->where('status', 'accepted')->sum('total_amount');
            $monthlyTarget = $monthlyTotal * 1.5; // Simulate target as 150% of current achievement

            // Get yearly data
            $yearlyQuotations = Quotation::whereHas('prospect', function ($query) use ($company) {
                $query->where('company', $company);
            })
                ->whereYear('created_at', $currentYear);

            $yearlyTotal = $yearlyQuotations->where('status', 'accepted')->sum('total_amount');
            $yearlyTarget = $yearlyTotal * 1.3; // Simulate target as 130% of current achievement

            // Calculate completion rates
            $monthlyCompletionRate = $monthlyTarget > 0 ? round(($monthlyTotal / $monthlyTarget) * 100, 1) : 0;
            $yearlyCompletionRate = $yearlyTarget > 0 ? round(($yearlyTotal / $yearlyTarget) * 100, 1) : 0;

            // Determine colors based on completion rate
            $monthlyColor = $monthlyCompletionRate >= 80 ? 'green' : ($monthlyCompletionRate >= 60 ? 'blue' : 'yellow');
            $yearlyColor = $yearlyCompletionRate >= 80 ? 'green' : ($yearlyCompletionRate >= 60 ? 'blue' : 'yellow');

            $performanceData[] = [
                'company' => $company,
                'monthly_target' => $monthlyTarget,
                'completion' => $monthlyTotal,
                'monthly_completion_rate' => $monthlyCompletionRate,
                'monthly_completion_color' => $monthlyColor,
                'yearly_target' => $yearlyTarget,
                'accumulative_total' => $yearlyTotal,
                'yearly_completion_rate' => $yearlyCompletionRate,
                'yearly_completion_color' => $yearlyColor,
            ];
        }

        return $performanceData;
    }

    /**
     * Get monthly chart data for the current year
     */
    private function getMonthlyData()
    {
        $currentYear = Carbon::now()->year;
        $omsetData = [];
        $grossProfitData = [];
        $targetCompletionData = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     // Get total omset (revenue) for the month
        //     $monthlyOmset = Quotation::where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->sum('total_amount');

        //     // Simulate gross profit as 60% of omset
        //     $monthlyGrossProfit = $monthlyOmset * 0.6;

        //     // Calculate target completion rate based on monthly data
        //     $monthlyQuotations = Quotation::whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();

        //     $acceptedQuotations = Quotation::where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();

        //     $targetCompletion = $monthlyQuotations > 0 ? round(($acceptedQuotations / $monthlyQuotations) * 100) : 0;

        //     $omsetData[] = $monthlyOmset;
        //     $grossProfitData[] = $monthlyGrossProfit;
        //     $targetCompletionData[] = $targetCompletion;
        // }

        return [
            'omset' => $omsetData,
            'gross_profit' => $grossProfitData,
            'target_completion' => $targetCompletionData,
        ];
    }

    /**
     * Get sales teams from users
     */
    private function getSalesTeams()
    {
        return User::whereNotNull('no_quotation')
            ->role('sales')
            ->where('no_quotation', '>', 0)
            ->select('id', 'name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => strtolower(str_replace(' ', '', $user->name)),
                    'name' => strtoupper($user->name),
                ];
            })
            ->toArray();
    }

    /**
     * Get all prospects with their relationships
     */
    private function getProspects($userId = null)
    {
        $query = Prospect::with(['quotations', 'prospectStatus']);

        if ($userId) {
            $query->whereHas('quotations', function ($q) use ($userId) {
                $q->where('created_by', $userId);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * ========== SALES ROLE METHODS ==========
     */

    /**
     * Get total revenue for specific sales person
     */
    private function getSalesRevenue($userId)
    {
        return Quotation::where('created_by', $userId)
            ->where('status', 'accepted')
            ->sum('total_amount');
    }

    /**
     * Get quotation count for specific sales person
     */
    private function getSalesQuotationCount($userId)
    {
        return Quotation::where('created_by', $userId)->count();
    }

    /**
     * Get acceptance rate for specific sales person
     */
    private function getSalesAcceptanceRate($userId)
    {
        $total = Quotation::where('created_by', $userId)->count();
        $accepted = Quotation::where('created_by', $userId)
            ->where('status', 'accepted')
            ->count();

        return $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
    }

    /**
     * Get performance data for specific sales person
     */
    private function getSalesPerformanceData($userId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $companies = Prospect::whereHas('quotations', function ($query) use ($userId) {
            $query->where('created_by', $userId);
        })
            ->select('company')
            ->distinct()
            ->whereNotNull('company')
            ->get()
            ->pluck('company');

        $performanceData = [];

        foreach ($companies as $company) {
            $monthlyQuotations = Quotation::where('created_by', $userId)
                ->whereHas('prospect', function ($query) use ($company) {
                    $query->where('company', $company);
                })
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);

            $monthlyTotal = $monthlyQuotations->where('status', 'accepted')->sum('total_amount');
            $monthlyTarget = $monthlyTotal * 1.5;

            $yearlyQuotations = Quotation::where('created_by', $userId)
                ->whereHas('prospect', function ($query) use ($company) {
                    $query->where('company', $company);
                })
                ->whereYear('created_at', $currentYear);

            $yearlyTotal = $yearlyQuotations->where('status', 'accepted')->sum('total_amount');
            $yearlyTarget = $yearlyTotal * 1.3;

            $monthlyCompletionRate = $monthlyTarget > 0 ? round(($monthlyTotal / $monthlyTarget) * 100, 1) : 0;
            $yearlyCompletionRate = $yearlyTarget > 0 ? round(($yearlyTotal / $yearlyTarget) * 100, 1) : 0;

            $monthlyColor = $monthlyCompletionRate >= 80 ? 'green' : ($monthlyCompletionRate >= 60 ? 'blue' : 'yellow');
            $yearlyColor = $yearlyCompletionRate >= 80 ? 'green' : ($yearlyCompletionRate >= 60 ? 'blue' : 'yellow');

            $performanceData[] = [
                'company' => $company,
                'monthly_target' => $monthlyTarget,
                'completion' => $monthlyTotal,
                'monthly_completion_rate' => $monthlyCompletionRate,
                'monthly_completion_color' => $monthlyColor,
                'yearly_target' => $yearlyTarget,
                'accumulative_total' => $yearlyTotal,
                'yearly_completion_rate' => $yearlyCompletionRate,
                'yearly_completion_color' => $yearlyColor,
            ];
        }

        return $performanceData;
    }

    /**
     * Get monthly chart data for specific sales person
     */
    private function getSalesMonthlyData($userId)
    {
        $currentYear = Carbon::now()->year;
        $omsetData = [];
        $grossProfitData = [];
        $targetCompletionData = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $monthlyOmset = Quotation::where('created_by', $userId)
        //         ->where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->sum('total_amount');
        //
        //     $monthlyGrossProfit = $monthlyOmset * 0.6;
        //
        //     $monthlyQuotations = Quotation::where('created_by', $userId)
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();
        //
        //     $acceptedQuotations = Quotation::where('created_by', $userId)
        //         ->where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();
        //
        //     $targetCompletion = $monthlyQuotations > 0 ? round(($acceptedQuotations / $monthlyQuotations) * 100) : 0;
        //
        //     $omsetData[] = $monthlyOmset;
        //     $grossProfitData[] = $monthlyGrossProfit;
        //     $targetCompletionData[] = $targetCompletion;
        // }

        return [
            'omset' => $omsetData,
            'gross_profit' => $grossProfitData,
            'target_completion' => $targetCompletionData,
        ];
    }

    /**
     * ========== PROJECT ROLE METHODS ==========
     */

    /**
     * Get total projects count
     */
    private function getProjectCount()
    {
        return Project::count();
    }

    /**
     * Get completed projects count
     */
    private function getCompletedProjectsCount()
    {
        return Project::where('status', 'completed')->count();
    }

    /**
     * Get projects currently in progress
     */
    private function getProjectsInProgress()
    {
        return Project::where('status', 'in_progress')
            ->with(['wbsItems'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get project timeline data
     */
    private function getProjectTimeline()
    {
        return Project::orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get team productivity metrics
     */
    private function getTeamProductivity()
    {
        // Implement based on your business logic
        return [];
    }

    /**
     * Get project budget status
     */
    private function getProjectBudgetStatus()
    {
        return Project::select('id', 'client_name', 'company', 'description')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'client_name' => $project->client_name,
                    'company' => $project->company,
                    'description' => $project->description,
                ];
            });
    }

    /**
     * ========== LOGISTIC ROLE METHODS ==========
     */

    /**
     * Get total installations count
     */
    private function getTotalInstallations()
    {
        return Installation::count();
    }

    /**
     * Get pending installations count
     */
    private function getPendingInstallations()
    {
        return Installation::where('status', 'pending')->count();
    }

    /**
     * Get completed installations count
     */
    private function getCompletedInstallations()
    {
        return Installation::where('status', 'completed')->count();
    }

    /**
     * Get installation schedule
     */
    private function getInstallationSchedule()
    {
        return Installation::select('id', 'name', 'scheduled_date', 'status')
            ->orderBy('scheduled_date', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * Get accommodation status
     */
    private function getAccommodationStatus()
    {
        return Accommodation::select('id', 'name', 'capacity', 'status')
            ->get();
    }

    /**
     * Get logistic performance metrics
     */
    private function getLogisticPerformanceMetrics()
    {
        return [
            'on_time_delivery_rate' => $this->getOnTimeDeliveryRate(),
            'installation_efficiency' => $this->getInstallationEfficiency(),
            'accommodation_utilization' => $this->getAccommodationUtilization(),
        ];
    }

    /**
     * Get on-time delivery rate
     */
    private function getOnTimeDeliveryRate()
    {
        $total = Installation::count();
        $onTime = Installation::where('status', 'completed')
            ->whereRaw('completed_date <= scheduled_date')
            ->count();

        return $total > 0 ? round(($onTime / $total) * 100, 1) : 0;
    }

    /**
     * Get installation efficiency rate
     */
    private function getInstallationEfficiency()
    {
        // Implement based on your business logic
        return 0;
    }

    /**
     * Get accommodation utilization rate
     */
    private function getAccommodationUtilization()
    {
        // Implement based on your business logic
        return 0;
    }

    /**
     * ========== FINANCE ROLE METHODS ==========
     */

    /**
     * Get total expenses
     */
    private function getTotalExpenses()
    {
        // Implement based on your expense tracking model
        return 0;
    }

    /**
     * Get profit margin
     */
    private function getProfitMargin()
    {
        $revenue = $this->getTotalRevenue();
        $expenses = $this->getTotalExpenses();

        if ($revenue === 0) {
            return 0;
        }

        $profit = $revenue - $expenses;

        return round(($profit / $revenue) * 100, 1);
    }

    /**
     * Get invoice status
     */
    private function getInvoiceStatus()
    {
        // Implement based on your invoice model
        return [];
    }

    /**
     * Get payment analysis
     */
    private function getPaymentAnalysis()
    {
        // Implement based on your payment tracking
        return [];
    }

    /**
     * Get quotation metrics for finance
     */
    private function getQuotationMetrics()
    {
        return [
            'total_quotations' => Quotation::count(),
            'accepted_quotations' => Quotation::where('status', 'accepted')->count(),
            'pending_quotations' => Quotation::where('status', 'pending')->count(),
            'total_value' => Quotation::sum('total_amount'),
        ];
    }

    /**
     * Get cashflow trend
     */
    private function getCashflowTrend()
    {
        // Implement based on your business logic
        return [];
    }

    /**
     * Get team-specific data for AJAX request
     */
    public function getTeamData($teamId)
    {
        // Handle "all teams" case
        if ($teamId === 'all') {
            $performanceData = $this->getPerformanceData();
            $monthlyData = $this->getMonthlyData();
            $prospects = $this->getProspects();

            return response()->json([
                'performanceData' => $performanceData,
                'monthlyData' => $monthlyData,
                'prospects' => $prospects,
                'teamName' => 'ALL TEAMS',
            ]);
        }

        // Find user by team ID (convert back from formatted ID)
        $user = User::whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$teamId])
            ->whereNotNull('no_quotation')
            ->where('no_quotation', '>', 0)
            ->first();

        if (! $user) {
            return response()->json(['error' => 'Sales team not found'], 404);
        }

        // Get performance data filtered by this sales person's quotations
        $performanceData = $this->getPerformanceDataByUser($user->id);

        // Get monthly chart data filtered by this sales person
        $monthlyData = $this->getMonthlyDataByUser($user->id);

        // Get prospects filtered by this sales person
        $prospects = $this->getProspects($user->id);

        return response()->json([
            'performanceData' => $performanceData,
            'monthlyData' => $monthlyData,
            'prospects' => $prospects,
            'teamName' => strtoupper($user->name),
        ]);
    }

    /**
     * Get performance data filtered by specific user/sales person
     */
    private function getPerformanceDataByUser($userId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get companies from prospects related to this sales person's quotations
        $companies = Prospect::whereHas('quotations', function ($query) use ($userId) {
            $query->where('created_by', $userId);
        })
            ->select('company')
            ->distinct()
            ->whereNotNull('company')
            ->get()
            ->pluck('company');

        $performanceData = [];

        foreach ($companies as $company) {
            // Get monthly data for this sales person
            $monthlyQuotations = Quotation::where('created_by', $userId)
                ->whereHas('prospect', function ($query) use ($company) {
                    $query->where('company', $company);
                })
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);

            $monthlyTotal = $monthlyQuotations->where('status', 'accepted')->sum('total_amount');
            $monthlyTarget = $monthlyTotal * 1.5; // Simulate target as 150% of current achievement

            // Get yearly data for this sales person
            $yearlyQuotations = Quotation::where('created_by', $userId)
                ->whereHas('prospect', function ($query) use ($company) {
                    $query->where('company', $company);
                })
                ->whereYear('created_at', $currentYear);

            $yearlyTotal = $yearlyQuotations->where('status', 'accepted')->sum('total_amount');
            $yearlyTarget = $yearlyTotal * 1.3; // Simulate target as 130% of current achievement

            // Calculate completion rates
            $monthlyCompletionRate = $monthlyTarget > 0 ? round(($monthlyTotal / $monthlyTarget) * 100, 1) : 0;
            $yearlyCompletionRate = $yearlyTarget > 0 ? round(($yearlyTotal / $yearlyTarget) * 100, 1) : 0;

            // Determine colors based on completion rate
            $monthlyColor = $monthlyCompletionRate >= 80 ? 'green' : ($monthlyCompletionRate >= 60 ? 'blue' : 'yellow');
            $yearlyColor = $yearlyCompletionRate >= 80 ? 'green' : ($yearlyCompletionRate >= 60 ? 'blue' : 'yellow');

            $performanceData[] = [
                'company' => $company,
                'monthly_target' => $monthlyTarget,
                'completion' => $monthlyTotal,
                'monthly_completion_rate' => $monthlyCompletionRate,
                'monthly_completion_color' => $monthlyColor,
                'yearly_target' => $yearlyTarget,
                'accumulative_total' => $yearlyTotal,
                'yearly_completion_rate' => $yearlyCompletionRate,
                'yearly_completion_color' => $yearlyColor,
            ];
        }

        return $performanceData;
    }

    /**
     * Get monthly chart data filtered by specific user/sales person
     */
    private function getMonthlyDataByUser($userId)
    {
        $currentYear = Carbon::now()->year;
        $omsetData = [];
        $grossProfitData = [];
        $targetCompletionData = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     // Get total omset (revenue) for the month for this sales person
        //     $monthlyOmset = Quotation::where('created_by', $userId)
        //         ->where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->sum('total_amount');

        //     // Simulate gross profit as 60% of omset
        //     $monthlyGrossProfit = $monthlyOmset * 0.6;

        //     // Calculate target completion rate based on monthly data for this sales person
        //     $monthlyQuotations = Quotation::where('created_by', $userId)
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();

        //     $acceptedQuotations = Quotation::where('created_by', $userId)
        //         ->where('status', 'accepted')
        //         ->whereMonth('created_at', $month)
        //         ->whereYear('created_at', $currentYear)
        //         ->count();

        //     $targetCompletion = $monthlyQuotations > 0 ? round(($acceptedQuotations / $monthlyQuotations) * 100) : 0;

        //     $omsetData[] = $monthlyOmset;
        //     $grossProfitData[] = $monthlyGrossProfit;
        //     $targetCompletionData[] = $targetCompletion;
        // }

        return [
            'omset' => $omsetData,
            'gross_profit' => $grossProfitData,
            'target_completion' => $targetCompletionData,
        ];
    }
}
