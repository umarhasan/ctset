<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CicuWardRound;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CicuWardRoundExport;
use Illuminate\Http\JsonResponse;

class CicuWardRoundController extends Controller
{
    public function index()
    {
        return view('admin.cicu_ward_rounds.index', [
            'rounds'      => CicuWardRound::with(['hospital','user'])->latest()->get(),
            'hospitals'   => Hospital::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'          => 'required|date',
            'from_time'     => 'required',
            'hospital_id'   => 'required',
            'involvement'   => 'required|in:A,W',
        ]);

        $data['user_id'] = auth()->id();
        CicuWardRound::create($data);

        return back()->with('success','CICU Ward Round Added');
    }

    public function end(CicuWardRound $cicu_ward_round)
    {
        $cicu_ward_round->update(['to_time' => now()->format('H:i')]);
        return back()->with('success', 'Activity Ended');
    }

    public function destroy(CicuWardRound $cicu_ward_round)
    {
        $cicu_ward_round->delete();
        return back()->with('success','Deleted');
    }

    public function exportExcel()
    {
        return Excel::download(
            new CicuWardRoundExport,
            'cicu-ward-rounds-'.date('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf()
    {
        $rounds = CicuWardRound::with(['hospital','user'])->get();
        $pdf = Pdf::loadView('admin.cicu_ward_rounds.pdf', compact('rounds'));
        return $pdf->download('cicu-ward-rounds.pdf');
    }

    /* ================= PERFORMANCE ================= */

    public function performanceAnalysis(Request $request): JsonResponse
{
    $period = $request->query('period', 'all');

    // Determine start date based on period
    switch($period){
        case '1': $startDate = now()->subMonth(); break;
        case '3': $startDate = now()->subMonths(3); break;
        case '6': $startDate = now()->subMonths(6); break;
        default: $startDate = null; // all
    }

    $query = CicuWardRound::with('user');

    if ($startDate) {
        $query->whereDate('date', '>=', $startDate);
    }

    $rounds = $query->get();

    // Aggregate Active rounds per user
    $userActiveRounds = $rounds
        ->groupBy(fn($r) => $r->user_id)
        ->map(function($userRounds, $userId) {
            $userName = $userRounds->first()->user->name ?? 'Unknown';
            $activeCount = $userRounds->where('involvement', 'A')->count();
            $waitingCount = $userRounds->where('involvement', 'W')->count();

            return [
                'name' => $userName,
                'type' => 'Active',
                'value' => $activeCount,
            ];
        })
        ->sortByDesc('value') // Sort by highest active rounds
        ->values();

    // Top 5 users
    $top5 = $userActiveRounds->take(5)->values();

    return response()->json([
        'chart_data' => $top5
    ]);
}
}
