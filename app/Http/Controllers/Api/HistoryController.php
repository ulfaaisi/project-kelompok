<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\HistoryServiceInterface;
use Illuminate\Http\JsonResponse; // Tambahkan ini agar return type-nya jelas
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(
        private HistoryServiceInterface $historyService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            // UBAH getByUser MENJADI getUserHistory SESUAI SERVICE
            'data' => $this->historyService->getUserHistory($request->user()->id)
        ]);
    }
}
