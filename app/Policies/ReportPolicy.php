<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewSalesReports(User $user)
    {
        return $user->hasPermission('view_sales_reports');
    }

    public function viewDashboard(User $user)
    {
        return $user->hasPermission('view_dashboard');
    }
}