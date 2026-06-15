<?php

namespace App\Http\Controllers;

use App\Contracts\HistoryServiceInterface;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService
    ) {}

    public function index(): View
    {
        $histories = $this->historyService->getUserHistory(auth()->id());
        return view('pages.history', compact('histories'));
    }
}
