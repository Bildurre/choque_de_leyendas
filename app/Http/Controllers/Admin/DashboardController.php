<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
  protected DashboardService $dashboardService;

  public function __construct(DashboardService $dashboardService)
  {
    $this->dashboardService = $dashboardService;
  }

  public function index(Request $request): View
  {
    $selectedFactionId = $request->get('faction_id');
    
    $stats = $this->dashboardService->getAllStats($selectedFactionId);
    
    return view('admin.dashboard.index', compact('stats'));
  }
}