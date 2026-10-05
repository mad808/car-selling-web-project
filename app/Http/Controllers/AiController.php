<?php

namespace App\Http\Controllers;

use App\Services\LocalAiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected LocalAiService $aiService;

    public function __construct(LocalAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('ai.index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $reply = $this->aiService->chat($request->message);

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}