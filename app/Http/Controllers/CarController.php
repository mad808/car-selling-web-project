<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Brand; // Import Brand
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    // Show the "Sell Car" form
    public function create()
    {
        // Get brands alphabetically for the dropdown
        $brands = Brand::orderBy('name')->get();
        return view('cars.create', compact('brands'));
    }

    // Store the car in the database
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'title'       => 'required|string|max:255',
            'brand_id'    => 'required|exists:brands,id', // Validates against brands table
            'model'       => 'required|string|max:100',
            'year'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:3000',
        ]);

        // 2. Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cars', 'public');
        }

        // 3. Create Record
        Car::create([
            'user_id'     => Auth::id(),
            'brand_id'    => $request->brand_id, // Save the ID, not the name
            'title'       => $request->title,
            'model'       => $request->model,
            'year'        => $request->year,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $imagePath,
            'is_sold'     => false,
        ]);

        return redirect()->route('home')->with('success', 'Car listed successfully!');
    }

    // Show single car details
    public function show($id)
    {
        // 1. Get current car
        $car = Car::with(['user', 'brand'])->findOrFail($id);

        // 2. Get Related Cars (Same Brand, exclude current ID, take 4)
        $relatedCars = Car::where('brand_id', $car->brand_id)
            ->where('id', '!=', $id)
            ->with('brand') // Eager load brand for efficiency
            ->latest()
            ->take(4)
            ->get();

        return view('cars.show', compact('car', 'relatedCars'));
    }

    // Delete a car
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if (Auth::id() !== $car->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return redirect()->route('home')->with('success', 'Car listing removed.');
    }
}
