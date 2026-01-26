<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSession;
use App\Models\Hospital;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClinicalSessionExport;

class ClinicalSessionController extends Controller
{
    public function index()
    {
        return view('admin.clinical_sessions.index', [
            'rounds'      => ClinicalSession::with(['hospital','rotation','consultant','user'])->latest()->get(),
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
        ]);

        $data['user_id'] = auth()->id();
        ClinicalSession::create($data);

        return back()->with('success','Clinical Sessions Added');
    }

    public function end(ClinicalSession $clinical_session)
    {
        $clinical_session->update([
            'to_time' => now()->format('H:i')
        ]);

        return back()->with('success','Activity Ended');
    }

    public function destroy(ClinicalSession $clinical_session)
    {
        $clinical_session->delete();
        return back()->with('success','Deleted');
    }

    public function exportExcel()
    {
        return Excel::download(
            new ClinicalSessionExport,
            'clinical-sessions-'.date('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf()
    {
        $rounds = ClinicalSession::with(['hospital','rotation','consultant','user'])->get();
        $pdf = Pdf::loadView('admin.clinical_sessions.pdf', compact('rounds'));
        return $pdf->download('clinical-sessions.pdf');
    }

    /* ================= PERFORMANCE ================= */

    public function performanceAnalysis(Request $request)
    {
        $period = $request->period ?? '1';

        $range = match($period){
            '1' => [Carbon::now()->subMonth(), Carbon::now()],
            '3' => [Carbon::now()->subMonths(3), Carbon::now()],
            '6' => [Carbon::now()->subMonths(6), Carbon::now()],
            default => null
        };

        $query = ClinicalSession::with('user')
            ->when($range, fn($q)=>$q->whereBetween('date',$range));

        /* TOP 5 PIE */
        $top5 = $query->get()
            ->groupBy('user_id')
            ->map(fn($i)=>[
                'name'=>$i->first()->user->name,
                'type'=>$i->first()->user->getRoleNames()->first(),
                'value'=>$i->count()
            ])
            ->sortByDesc('value')
            ->take(5)
            ->values();

        /* OVERALL BAR */
        $overall = $query->get()
            ->groupBy('user_id')
            ->map(fn($i)=>[
                'name'=>$i->first()->user->name,
                'value'=>$i->count()
            ])
            ->values();

        return response()->json([
            'chart_data'=>$top5,
            'bar_data'=>$overall
        ]);
    }
}
