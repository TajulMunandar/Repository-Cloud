<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalFiles' => File::count(),
            'totalActivities' => FileActivity::count(),
            'totalViews' => FileActivity::where('activity_type', 'view')->count(),
            'totalDownloads' => FileActivity::where('activity_type', 'download')->count(),
        ];
        $activitiesPerDay = FileActivity::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // pisahkan label & data untuk chart
        $labels = $activitiesPerDay->pluck('date');
        $totals = $activitiesPerDay->pluck('total');

        $data['activityLabels'] = $labels;
        $data['activityTotals'] = $totals;
        return view('pages.index', $data);
    }
}
