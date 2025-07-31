<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
  protected DashboardService $dashboardService;

  public function __construct(DashboardService $dashboardService)
  {
    $this->dashboardService = $dashboardService;
  }

  public function index(): View
  {
    $stats = $this->dashboardService->getAllStats();
    
    return view('admin.dashboard.index', compact('stats'));
  }
}