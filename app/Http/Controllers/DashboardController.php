<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $queryFiles = File::query();
        $queryActivities = FileActivity::query();
        if (!$user->is_admin) {
            $queryFiles->where('user_id', $user->id);
            $queryActivities->whereHas('file', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $data = [
            'totalUsers' => $user->is_admin ? User::count() : 1, // kalau bukan admin, anggap 1 user (dirinya sendiri)
            'totalFiles' => $queryFiles->count(),
            'totalActivities' => $queryActivities->count(),
            'totalViews' => (clone $queryActivities)->where('activity_type', 'view')->count(),
            'totalDownloads' => (clone $queryActivities)->where('activity_type', 'download')->count(),
        ];
        $activitiesPerDay = $queryActivities->select(
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
