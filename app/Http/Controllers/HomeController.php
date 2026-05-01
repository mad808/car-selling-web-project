<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Banner;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Active Banners
        $banners = Banner::where('is_active', true)->latest()->get();

        // 2. Start Car Query
        $query = Car::query();

        // --- Search Logic (Keyword) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
                // Optional: Search by brand name via relationship
                // $q->orWhereHas('brand', function($b) use ($search) {
                //      $b->where('name', 'like', "%{$search}%");
                // });
            });
        }

        // --- Filter Options ---

        // Filter by Brand ID (Dropdown)
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by Year
        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }
        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        // Filter by Price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. Execute Query with Pagination
        // We use 'with' to eager load the brand and user to prevent database lag
        $cars = $query->with(['user', 'brand'])->latest()->paginate(12);

        // 4. Get brands for the filter dropdown
        $brands = Brand::orderBy('name')->get();

        return view('home', compact('banners', 'cars', 'brands'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
