<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\LocalAiService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected LocalAiService $aiService;

    public function __construct(LocalAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $cars = Car::with(['brand', 'user'])
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->paginate(50);

        return view('admin.cars.index', compact('cars', 'status'));
    }

    public function show(Car $car)
    {
        $car->load(['brand', 'user']);
        return view('admin.cars.show', compact('car'));
    }

    public function updateStatus(Request $request, Car $car)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $car->update([
            'status' => $request->status,
            'admin_note' => $request->status === 'rejected' ? $request->admin_note : null,
        ]);

        return redirect()->back()->with('success', 'Ulagyň statusy üstünlikli üýtgedildi!');
    }

    public function runAiCheck(Car $car)
    {
        $analysis = $this->aiService->analyzeCar($car);

        $car->update([
            'ai_review' => $analysis['review'],
            'ai_verdict' => $analysis['verdict'],
        ]);

        return redirect()->back()->with('success', "AI barlagy tamamlandy ({$analysis['source']})!");
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Ulag pozuldy!');
    }
}