<?php

namespace App\Http\Controllers;

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
        // Dashboard statistics from real database data
        $totalRevenue = $this->getTotalRevenue();
        $activeProjects = $this->getActiveProjectsCount();
        $completionRate = $this->getCompletionRate();

        // Performance data from database
        $performanceData = $this->getPerformanceData();

        // Monthly chart data from database
        $monthlyData = $this->getMonthlyData();

        // Sales teams from database
        $salesTeams = $this->getSalesTeams();

        // Get all prospects with their relationships
        $isBOD = Auth::user()->hasRole('BOD');

        if ($isBOD) {
            $prospects = $this->getProspects();
        } else {
            $userId = Auth::id();
            $prospects = $this->getProspects($userId);
        }

        $view = $isBOD ? 'dashboard.bod' : 'dashboard.sales';

        return view($view, compact(
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
