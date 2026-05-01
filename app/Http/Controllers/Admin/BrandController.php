<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:brands,name']);
        Brand::create(['name' => $request->name]);
        return back()->with('success', 'Brand added!');
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return back()->with('success', 'Brand deleted!');
    }
}
