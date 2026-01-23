<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyWardRound;
use App\Models\Hospital;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyWardRoundsExport;

class DailyWardRoundController extends Controller
{
    public function index()
    {
        return view('admin.daily_ward_rounds.index', [
            'rounds'      => DailyWardRound::with(['hospital','rotation','consultant'])->latest()->get(),
            'hospitals'   => Hospital::all(),
            'rotations'   => Rotation::all(),
            'consultants' => User::role('consultant')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'          => 'required|date',
            'from_time'     => 'required',
            'hospital_id'   => 'required',
            'rotation_id'   => 'nullable',
            'consultant_id' => 'nullable',
            'involvement'   => 'required|in:A,W',
            'lat'           => 'nullable',
            'long'          => 'nullable'
        ]);

        $data['user_id'] = auth()->id();
        DailyWardRound::create($data);

        return back()->with('success','Daily Ward Round Added');
    }

    public function end(DailyWardRound $dailyWardRound)
    {
        $dailyWardRound->update(['to_time' => now()->format('H:i')]);
        return back()->with('success','Activity Ended');
    }

    public function destroy(DailyWardRound $dailyWardRound)
    {
        $dailyWardRound->delete();
        return back()->with('success','Deleted');
    }

    public function exportExcel()
    {
        return Excel::download(new DailyWardRoundsExport, 'daily-ward-rounds-'.date('Y-m-d').'.xlsx');
    }

    public function exportPdf()
    {
        $rounds = DailyWardRound::with(['hospital','rotation','consultant','user'])->get();
        $pdf = Pdf::loadView('admin.daily_ward_rounds.pdf', compact('rounds'));
        return $pdf->download('daily-ward-rounds.pdf');
    }

    public function performanceAnalysis(Request $request)
    {
        $period = $request->period ?? '1';

        $range = match($period){
            '1' => [Carbon::now()->subMonth(), Carbon::now()],
            '3' => [Carbon::now()->subMonths(3), Carbon::now()],
            '6' => [Carbon::now()->subMonths(6), Carbon::now()],
            default => null
        };

        $query = DailyWardRound::with('user')->when($range, fn($q)=>$q->whereBetween('date',$range));

        $top5 = $query->get()->groupBy('user_id')->map(fn($i)=>[
            'name'=>$i->first()->user->name,
            'type'=>$i->first()->user->getRoleNames()->first(),
            'value'=>$i->count()
        ])->sortByDesc('value')->take(5)->values();

        $overall = $query->get()->groupBy('user_id')->map(fn($i)=>[
            'name'=>$i->first()->user->name,
            'value'=>$i->count()
        ])->values();

        return response()->json(['chart_data'=>$top5,'bar_data'=>$overall]);
    }
}
