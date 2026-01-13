<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Setting;
use \App\Models\User;
use App\Models\ChatFlag;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $lastWeekDates = collect(range(6, 0))->map(function ($days) {
                return Carbon::now()->subDays($days)->format('Y-m-d');
            });

            $lastWeekTransactions = $lastWeekDates->map(function ($date) {
                return Transaction::whereDate('created_at', $date)->count();
            });

            $totalProducts = Product::count();
            $newProductsThisWeek = Product::where('created_at', '>=', now()->subDays(7))->count();
            $totalUsers = User::count();
            $newUsersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();
            $productLimit = optional(Setting::where('key','max_products_user')->first())->value ?? 0;
            $flaggedMessages = ChatFlag::count();
            $approvedProducts = Product::where('status', 'approved')->count();
            $rejectedProducts = Product::where('status', 'rejected')->count();
            $flaggedProducts = Product::where('status', 'flagged')->count();

            return view('admin.dashboard', compact(
                'lastWeekDates',
                'lastWeekTransactions',
                'totalProducts',
                'newProductsThisWeek',
                'totalUsers',
                'newUsersThisWeek',
                'productLimit',
                'flaggedMessages',
                'approvedProducts',
                'rejectedProducts',
                'flaggedProducts'
            ));
        } catch (\Exception $e) {
            return view('admin.dashboard')->with('error', 'An error occurred while loading the dashboard data.');
        }
    }
}
