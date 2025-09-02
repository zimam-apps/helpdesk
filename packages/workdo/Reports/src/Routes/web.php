<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\Reports\Http\Controllers\ReportController;

Route::group(['middleware' => ['web', 'auth', 'verified', 'XSS', 'ModuleCheckEnable:Reports']], function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        // Ticket Reports Routes
        Route::get('/ticket', [ReportController::class, 'ticket'])->name('ticket');
        Route::get('/ticket/chart-data', [ReportController::class, 'getTicketChartData'])->name('ticket.chartData');
        Route::get('/ticket/platform-data', [ReportController::class, 'getTicketPlatformData'])->name('ticket.platformData');
        Route::get('/ticket/assignment-data', [ReportController::class, 'getTicketAssignData'])->name('ticket.assignData');

        // Tag Reports Routes
        Route::get('/tag', [ReportController::class, 'tag'])->name('tag');
        Route::get('/tag/distribution', [ReportController::class, 'getTagDistributionData'])->name('tag.distribution');
        Route::get('/tag/trends', [ReportController::class, 'getTagTrendData'])->name('tag.trends');

        // Agent Reports Routes
        Route::get('/agent', [ReportController::class, 'agent'])->name('agent');
        Route::get('/agent/resolution-data', [ReportController::class, 'getAgentResolutionData'])->name('agent.resolutionData');
        Route::get('/agent/performance-data', [ReportController::class, 'getAgentPerformanceData'])->name('agent.performanceData');
        Route::get('/agent/workload-data', [ReportController::class, 'getAgentWorkloadData'])->name('agent.workloadData');

        Route::get('/agent/ratings', [ReportController::class, 'getAgentRatingChartData'])->name('agent.ratings');
        
        // User Reports Routes
        Route::get('/user', [ReportController::class, 'user'])->name('user');
        Route::get('/user/acticity-chart', [ReportController::class, 'getUserActivityChart'])->name('user.activityChart');

        // Rating Reports Routes
        Route::get('/rating', [ReportController::class, 'rating'])->name('rating');
        Route::get('/rating/agent', [ReportController::class, 'getAgentRatingData'])->name('rating.agent');
        Route::get('/rating/distribution', [ReportController::class, 'getRatingDistributionData'])->name('rating.distribution');
    });
});