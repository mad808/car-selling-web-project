<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Car;
use App\Models\Banner;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalCars = Car::count();
        $totalBanners = Banner::count();
        $latestCars = Car::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalCars', 'totalBanners', 'latestCars'));
    }
}
