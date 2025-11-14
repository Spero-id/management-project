# HomeController Refactoring - Role-Based Dashboards

## Overview
HomeController telah direfactor untuk mendukung dashboard khusus per role dengan struktur yang terpisah dan terorganisir.

## Struktur Baru

### Main Method
- **`index()`** - Entry point yang menggunakan `match()` expression untuk menentukan dashboard berdasarkan role user

### Dashboard Methods (Private)
- `dashboardBOD()` - Dashboard untuk BOD (Board of Directors)
- `dashboardSales()` - Dashboard untuk Sales team
- `dashboardProject()` - Dashboard untuk Project team
- `dashboardLogistic()` - Dashboard untuk Logistic team
- `dashboardFinance()` - Dashboard untuk Finance team

### Data Provider Methods

#### General Methods
- `getTotalRevenue()` - Total revenue dari accepted quotations
- `getActiveProjectsCount()` - Jumlah active projects
- `getCompletionRate()` - Overall completion rate
- `getPerformanceData()` - Performance by company
- `getMonthlyData()` - Monthly chart data
- `getSalesTeams()` - List sales teams
- `getProspects($userId = null)` - Get prospects

#### Sales Role Methods
- `getSalesRevenue($userId)` - Revenue untuk sales person tertentu
- `getSalesQuotationCount($userId)` - Jumlah quotation yang dibuat
- `getSalesAcceptanceRate($userId)` - Tingkat penerimaan quotation
- `getSalesPerformanceData($userId)` - Performance data sales person
- `getSalesMonthlyData($userId)` - Monthly data sales person

#### Project Role Methods
- `getProjectCount()` - Total projects
- `getCompletedProjectsCount()` - Projects yang completed
- `getProjectsInProgress()` - Projects dalam proses
- `getProjectTimeline()` - Timeline semua projects
- `getTeamProductivity()` - Produktivitas team
- `getProjectBudgetStatus()` - Status budget projects

#### Logistic Role Methods
- `getTotalInstallations()` - Total installations
- `getPendingInstallations()` - Pending installations
- `getCompletedInstallations()` - Completed installations
- `getInstallationSchedule()` - Schedule installations
- `getAccommodationStatus()` - Status accommodation
- `getLogisticPerformanceMetrics()` - Performance metrics
- `getOnTimeDeliveryRate()` - On-time delivery rate
- `getInstallationEfficiency()` - Installation efficiency
- `getAccommodationUtilization()` - Accommodation utilization

#### Finance Role Methods
- `getTotalExpenses()` - Total expenses
- `getProfitMargin()` - Profit margin percentage
- `getInvoiceStatus()` - Invoice status
- `getPaymentAnalysis()` - Payment analysis
- `getQuotationMetrics()` - Quotation metrics
- `getCashflowTrend()` - Cashflow trend

### AJAX Endpoint
- `getTeamData($teamId)` - Fetch team-specific data

## Views Created/Updated

### New Views
- `resources/views/dashboard/logistic.blade.php` - Dashboard untuk Logistic team
- `resources/views/dashboard/finance.blade.php` - Dashboard untuk Finance team

### Existing Views (Update needed if required)
- `resources/views/dashboard/bod.blade.php` - Dashboard BOD (existing)
- `resources/views/dashboard/sales.blade.php` - Dashboard Sales (existing)
- `resources/views/dashboard/project.blade.php` - Dashboard Project (existing)

## Cara Menggunakan

### 1. Menambahkan Role ke User
```php
$user->assignRole('sales'); // atau 'project', 'logistic', 'finance', 'BOD'
```

### 2. Login dengan Role Tertentu
Ketika user login, dashboard akan otomatis menampilkan sesuai rolenya:
- Sales → Sales Dashboard
- Project → Project Dashboard
- Logistic → Logistic Dashboard
- Finance → Finance Dashboard
- BOD (default) → BOD Dashboard

### 3. Customize Data Provider
Setiap method data provider dapat dikustomisasi sesuai kebutuhan bisnis:

```php
private function getSalesRevenue($userId)
{
    return Quotation::where('created_by', $userId)
        ->where('status', 'accepted')
        ->sum('total_amount');
}
```

## Implementasi View

Setiap view menerima data sesuai rolenya:

### Dashboard Sales
```blade
$totalRevenue, $quotationCount, $acceptanceRate, 
$performanceData, $monthlyData, $prospects
```

### Dashboard Project
```blade
$totalProjects, $activeProjects, $completedProjects,
$projectsInProgress, $projectTimeline, $teamProductivity, $budgetStatus
```

### Dashboard Logistic
```blade
$totalInstallations, $pendingInstallations, $completedInstallations,
$installationSchedule, $accommodationStatus, $performanceMetrics
```

### Dashboard Finance
```blade
$totalRevenue, $totalExpenses, $profitMargin, $invoiceStatus,
$paymentAnalysis, $quotationMetrics, $cashflowTrend
```

## Next Steps

1. **Lengkapi Implementasi Data Provider**
   - Implementasikan method yang masih placeholder (misalnya `getTeamProductivity()`, `getTotalExpenses()`, dst)
   - Sesuaikan dengan schema database dan business logic

2. **Update Views**
   - Update existing views (bod, sales, project) jika diperlukan
   - Tambahkan charts dan visualisasi yang lebih baik

3. **Testing**
   - Test setiap role untuk memastikan data tampil dengan benar
   - Verifikasi permission dan authorization

4. **Caching**
   - Pertimbangkan menambahkan caching untuk method yang heavy
   - Gunakan Laravel Cache untuk optimize query

5. **Monitoring & Logging**
   - Tambahkan logging untuk track dashboard access per role
   - Monitor performance metrics
