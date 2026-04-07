<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\StaffActivityLog;
use App\Models\User;
use App\Models\Role;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $staffActivityCount = StaffActivityLog::count();
        $reportsGeneratedCount = 0;
        $activeHealthWorkersCount = User::whereHas('role', function($q) {
                $q->whereIn('name', ['Super Admin', 'Admin', 'Worker']);
            })->where('status', 'Active')->count();
        $recentActivities = StaffActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard.index', compact(
            'staffActivityCount',
            'reportsGeneratedCount',
            'activeHealthWorkersCount',
            'recentActivities'
        ));
    }
}
