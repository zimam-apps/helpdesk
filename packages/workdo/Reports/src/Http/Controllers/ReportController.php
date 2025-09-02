<?php

namespace Workdo\Reports\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Workdo\Ratings\Entities\TicketRating;
use Workdo\Tags\Entities\Tags;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function ticket()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                return view('reports::ticket.index');
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getTicketChartData(Request $request)
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $type = $request->input('type', 'this_month'); // last_7_days, this_month, last_month, this_year, custom
                $labels = [];
                $data = [];

                switch ($type) {
                    case 'last_7_days':
                        for ($i = 6; $i >= 0; $i--) {
                            $date = now()->subDays($i);
                            $labels[] = $date->format('D');
                            $data[] = Ticket::whereDate('created_at', $date)->count();
                        }
                        break;

                    case 'this_year':
                        for ($i = 1; $i <= 12; $i++) {
                            $labels[] = Carbon::create()->month($i)->format('M');
                            $data[] = Ticket::whereMonth('created_at', $i)
                                ->whereYear('created_at', now()->year)
                                ->count();
                        }
                        break;

                    case 'last_month':
                        $start = now()->subMonth()->startOfMonth();
                        $end = now()->subMonth()->endOfMonth();
                        $period = $start->diffInDays($end);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            $data[] = Ticket::whereDate('created_at', $date)->count();
                        }
                        break;

                    case 'custom':
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        $period = $startDate->diffInDays($endDate);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $startDate->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            $data[] = Ticket::whereDate('created_at', $date)->count();
                        }
                        break;

                    case 'this_month':
                    default:
                        $start = now()->startOfMonth();
                        $end = now()->endOfMonth();
                        $period = $start->diffInDays($end);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            $data[] = Ticket::whereDate('created_at', $date)->count();
                        }
                        break;
                }

                return response()->json([
                    'labels' => $labels,
                    'data' => $data,
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getTicketPlatformData()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $platforms = [];

                if (moduleIsActive('WhatsAppChatBotAndChat')) {
                    $platforms['WhatsApp'] = Ticket::where('type', 'Whatsapp')->count();
                }

                if (moduleIsActive('FacebookChat')) {
                    $platforms['Facebook'] = Ticket::where('type', 'Facebook')->count();
                }

                if (moduleIsActive('InstagramChat')) {
                    $platforms['Instagram'] = Ticket::where('type', 'Instagram')->count();
                }

                if (moduleIsActive('Mail2Ticket')) {
                    $platforms['Mail2Ticket'] = Ticket::where('type', 'Mail')->count();
                }

                if (moduleIsActive('TicketWidget')) {
                    $platforms['Widget'] = Ticket::where('type', 'Widget')->count();
                }

                if (moduleIsActive('Zendesk')) {
                    $platforms['Zenddesk'] = Ticket::where('type', 'Zenddesk')->count();
                }

                $platforms['LiveChat'] = Ticket::where('type', 'LiveChat')->count();
                $platforms['TicketForm'] = Ticket::where('type', 'TicketForm')->count();

                return response()->json([
                    'labels' => array_keys($platforms),
                    'data' => array_values($platforms)
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getTicketAssignData()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $assigned = Ticket::where('is_ticket_assign_to_agent', 'Assigned')->count();
                $unassigned = Ticket::where('is_ticket_assign_to_agent', 'Unassigned')->count();

                return response()->json([
                    'labels' => ['Assigned', 'Unassigned'],
                    'data' => [$assigned, $unassigned]
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function agent()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                return view('reports::agent.index');
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    // For Agent Dashboard
    public function getAgentRatingChartData(Request $request)
    {
        try {
            if (Auth::user()->hasRole('agent')) {
                $agentId = Auth::id();
                $type = $request->input('type', 'this_month'); // last_7_days, this_month, last_month, this_year, custom

                $labels = [];
                $data = [];

                switch ($type) {
                    case 'last_7_days':
                        for ($i = 6; $i >= 0; $i--) {
                            $date = Carbon::now()->subDays($i)->format('Y-m-d');
                            $labels[] = Carbon::parse($date)->format('D');

                            $averageRating = TicketRating::where('user_id', $agentId)
                                ->whereDate('rating_date', $date)
                                ->avg('rating') ?? 0;

                            $data[] = round($averageRating, 2);
                        }
                        break;

                    case 'this_year':
                        for ($i = 1; $i <= 12; $i++) {
                            $labels[] = Carbon::create()->month($i)->format('M');

                            $avg = TicketRating::where('user_id', $agentId)
                                ->whereMonth('rating_date', $i)
                                ->whereYear('rating_date', now()->year)
                                ->avg('rating') ?? 0;

                            $data[] = round($avg, 2);
                        }
                        break;

                    case 'last_month':
                        $start = now()->subMonth()->startOfMonth();
                        $end = now()->subMonth()->endOfMonth();
                        $period = $start->diffInDays($end);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');

                            $avg = TicketRating::where('user_id', $agentId)
                                ->whereDate('rating_date', $date)
                                ->avg('rating') ?? 0;

                            $data[] = round($avg, 2);
                        }
                        break;

                    case 'custom':
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        $period = $startDate->diffInDays($endDate);

                        for ($i = 0; $i <= $period; $i++) {
                            $date = $startDate->copy()->addDays($i);
                            $labels[] = $date->format('d M');

                            $avg = TicketRating::where('user_id', $agentId)
                                ->whereDate('rating_date', $date)
                                ->avg('rating') ?? 0;

                            $data[] = round($avg, 2);
                        }
                        break;

                    case 'this_month':
                    default:
                        $start = now()->startOfMonth();
                        $end = now()->endOfMonth();
                        $period = $start->diffInDays($end);

                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');

                            $avg = TicketRating::where('user_id', $agentId)
                                ->whereDate('rating_date', $date)
                                ->avg('rating') ?? 0;

                            $data[] = round($avg, 2);
                        }
                        break;
                }

                return response()->json([
                    'labels' => $labels,
                    'data' => $data
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getAgentResolutionData(Request $request)
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $type = $request->input('type', 'this_month'); // last_7_days, this_month, this_year, custom
                $labels = [];
                $series = [];

                // Get all agents
                $agents = User::where('type', 'agent')->get();
                
                switch ($type) {
                    case 'last_7_days':
                        for ($i = 6; $i >= 0; $i--) {
                            $date = Carbon::now()->subDays($i);
                            $labels[] = $date->format('D');
                            
                            foreach ($agents as $agent) {
                                $count = Ticket::where('is_assign', $agent->id)
                                    ->whereDate('reslove_at', $date)
                                    ->count();
                                
                                if (!isset($series[$agent->name])) {
                                    $series[$agent->name] = [
                                        'name' => $agent->name,
                                        'data' => []
                                    ];
                                }
                                $series[$agent->name]['data'][] = $count;
                            }
                        }
                        break;

                    case 'this_year':
                        for ($i = 1; $i <= 12; $i++) {
                            $labels[] = Carbon::create()->month($i)->format('M');
                            
                            foreach ($agents as $agent) {
                                $count = Ticket::where('is_assign', $agent->id)
                                    ->whereMonth('reslove_at', $i)
                                    ->whereYear('reslove_at', now()->year)
                                    ->count();
                                
                                if (!isset($series[$agent->name])) {
                                    $series[$agent->name] = [
                                        'name' => $agent->name,
                                        'data' => []
                                    ];
                                }
                                $series[$agent->name]['data'][] = $count;
                            }
                        }
                        break;

                    case 'last_month':
                        $start = now()->subMonth()->startOfMonth();
                        $end = now()->subMonth()->endOfMonth();
                        $period = $start->diffInDays($end);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            foreach ($agents as $agent) {
                                $count = Ticket::where('is_assign', $agent->id)
                                    ->whereDate('reslove_at', $date)
                                    ->count();
                                
                                if (!isset($series[$agent->name])) {
                                    $series[$agent->name] = [
                                        'name' => $agent->name,
                                        'data' => []
                                    ];
                                }
                                $series[$agent->name]['data'][] = $count;
                            }
                        }
                        break;

                    case 'custom':
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        $period = Carbon::parse($startDate)->diffInDays($endDate);

                        for ($i = 0; $i <= $period; $i++) {
                            $date = $startDate->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            
                            foreach ($agents as $agent) {
                                $count = Ticket::where('is_assign', $agent->id)
                                    ->whereDate('reslove_at', $date)
                                    ->count();
                                
                                if (!isset($series[$agent->name])) {
                                    $series[$agent->name] = [
                                        'name' => $agent->name,
                                        'data' => []
                                    ];
                                }
                                $series[$agent->name]['data'][] = $count;
                            }
                        }
                        break;

                    case 'this_month':
                    default:
                        $start = now()->startOfMonth();
                        $end = now()->endOfMonth();
                        $period = $start->diffInDays($end);
                        for ($i = 0; $i <= $period; $i++) {
                            $date = $start->copy()->addDays($i);
                            $labels[] = $date->format('d M');
                            foreach ($agents as $agent) {
                                $count = Ticket::where('is_assign', $agent->id)
                                    ->whereDate('reslove_at', $date)
                                    ->count();
                                
                                if (!isset($series[$agent->name])) {
                                    $series[$agent->name] = [
                                        'name' => $agent->name,
                                        'data' => []
                                    ];
                                }
                                $series[$agent->name]['data'][] = $count;
                            }
                        }
                        break;
                }

                return response()->json([
                    'labels' => $labels,
                    'series' => array_values($series)
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getAgentWorkloadData()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $agents = User::where('type', 'agent')->get();
                $workloadData = [];

                foreach ($agents as $agent) {
                    $newTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'New Ticket')
                        ->count();
                    $inProgressTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'In Progress')
                        ->count();
                    $onHoldTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'On Hold')
                        ->count();
                    $closedTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'Closed')
                        ->count();
                    $resolvedTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'Resolved')
                        ->count();

                    $workloadData[] = [
                        'name' => $agent->name,
                        'new' => $newTickets,
                        'in_progress' => $inProgressTickets,
                        'on_hold' => $onHoldTickets,
                        'closed' => $closedTickets,
                        'resolved' => $resolvedTickets
                    ];
                }

                return response()->json($workloadData);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getAgentPerformanceData()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $agents = User::where('type', 'agent')->get();
                $performanceData = [];

                foreach ($agents as $agent) {
                    $totalTickets = Ticket::where('is_assign', $agent->id)->count();
                    $resolvedTickets = Ticket::where('is_assign', $agent->id)
                        ->where('status', 'Resolved')
                        ->count();

                    $performanceData[] = [
                        'name' => $agent->name,
                        'resolution_rate' => $totalTickets > 0 ? ($resolvedTickets / $totalTickets) * 100 : 0
                    ];
                }

                return response()->json($performanceData);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function user()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                return view('reports::user.index');
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getUserActivityChart()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                $ticketData = DB::table('tickets')
                    ->select(
                        'tickets.email',
                        'tickets.name as ticket_name',
                        DB::raw('COUNT(tickets.id) as ticket_count')
                    )
                    // ->whereNot('status', 'Closed')
                    // ->whereNull('reslove_at')
                    ->leftJoin('users', function($join) {
                        $join->on('users.email', '=', 'tickets.email');
                    })
                    ->groupBy('tickets.email')
                    ->orderBy('ticket_count', 'desc')
                    ->get();

                $labels = [];
                $data = [];
                foreach ($ticketData as $ticket) {
                    $labels[] = $ticket->ticket_name;
                    $data[] = $ticket->ticket_count;
                    
                }

                return response()->json([
                    'labels' => $labels,
                    'data' => $data,
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function tag()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                if (moduleIsActive('Tags')) {
                    return view('reports::tag.index');
                } else {
                    return redirect()->back()->with('error', __('Tags module is not active.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getTagDistributionData(Request $request)
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                if (!moduleIsActive('Tags')) {
                    return response()->json(['error' => 'Tags module is not active.'], 403);
                }

                $tags = Tags::where('created_by', creatorId())->get();
                
                $labels = [];
                $data = [];
                $colors = [];

                foreach ($tags as $tag) {
                    $count = Ticket::whereNotNull('tags_id')
                        ->whereRaw("FIND_IN_SET(?, tags_id)", [$tag->id])
                        ->count();

                    if ($count > 0) {
                        $labels[] = $tag->name;
                        $data[] = $count;
                        $colors[] = $tag->color;
                    }
                }

                return response()->json([
                    'labels' => $labels,
                    'data' => $data,
                    'colors' => $colors
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getTagTrendData(Request $request)
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                if (!moduleIsActive('Tags')) {
                    return response()->json(['error' => 'Tags module is not active.'], 403);
                }

                $tags = Tags::where('created_by', creatorId())->get();
                $labels = [];
                $series = [];

                // Get last 12 months data
                $currentDate = Carbon::now();
                for ($i = 11; $i >= 0; $i--) {
                    $date = $currentDate->copy()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    
                    foreach ($tags as $tag) {
                        $count = Ticket::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->whereNotNull('tags_id')
                            ->whereRaw("FIND_IN_SET(?, tags_id)", [$tag->id])
                            ->count();
                        
                        if (!isset($series[$tag->name])) {
                            $series[$tag->name] = [
                                'name' => $tag->name,
                                'data' => [],
                                'color' => $tag->color
                            ];
                        }
                        $series[$tag->name]['data'][] = $count;
                    }
                }

                return response()->json([
                    'labels' => $labels,
                    'data' => array_values($series)
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function rating()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                if (moduleIsActive('Ratings')) {
                    return view('reports::rating.index');
                } else {
                    return redirect()->back()->with('error', __('Ratings module is not active.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getAgentRatingData()
    {
        try {
            if (!Auth::user()->isAbleTo('reports manage')) {
                return redirect()->back()->with('error', __('Permission Denied'));
            }

            if (!moduleIsActive('Ratings')) {
                return redirect()->back()->with('error', __('Ratings module is not active.'));
            }

            $agents = User::select('id', 'name', 'type', 'avg_rating')
                ->where('type', 'agent')
                ->orderByDesc('avg_rating')
                ->get();

            $data = [];
            foreach ($agents as $agent) {
                $ratingCount = TicketRating::where('user_id', $agent->id)->count();

                $data[] = [
                    'name'    => $agent->name,
                    'average' => round($agent->avg_rating, 1),
                    'count'   => $ratingCount,
                ];
            }

            return response()->json($data);

        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function getRatingDistributionData()
    {
        try {
            if(Auth::user()->isAbleTo('reports manage')) {
                if (!moduleIsActive('Ratings')) {
                    return redirect()->back()->with('error', __('Ratings module is not active.'));
                }

                $ratings = DB::table('ticket_ratings')
                    ->select(
                        DB::raw('rating'),
                        DB::raw('COUNT(*) as count')
                    )
                    ->groupBy('rating')
                    ->orderBy('rating')
                    ->get();

                return response()->json([
                    'labels' => $ratings->pluck('rating'),
                    'data' => $ratings->pluck('count')
                ]);
            } else {
                return redirect()->back()->with('error', __('Permission Denied'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }
}
