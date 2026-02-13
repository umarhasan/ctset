<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrandWardRound;
use App\Models\Hospital;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GrandWardRoundsExport;

class GrandWardRoundController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if($user->hasRole('Admin')){
            $rounds = GrandWardRound::with(['user','hospital','rotation'])->latest()->get();
        }
        elseif($user->hasRole('Trainee')){
            $rounds = GrandWardRound::where('user_id',$user->id)
                ->with(['consultant','hospital','rotation'])->latest()->get();
        }
        else{
            $rounds = GrandWardRound::where('consultant_id',$user->id)
                ->with(['user','consultant','hospital','rotation'])->latest()->get();
        }
        
        return view('admin.grand_ward_rounds.index',[
            'rounds'=>$rounds,
            'hospitals'=>Hospital::get(),
            'rotations'=>Rotation::get(),
            'consultants' => User::role('Assessor')->get()
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
            //'involvement'   => 'required|in:A,W',
        ]);

        $data['user_id'] = auth()->id();
        GrandWardRound::create($data);

        return back()->with('success','Grand Ward Round Added');
    }

    public function end(GrandWardRound $grand_ward_round)
    {
        $grand_ward_round->update([
            'to_time' => now()->format('H:i')
        ]);

        return back()->with('success','Activity Ended');
    }

    public function destroy(GrandWardRound $grand_ward_round)
    {
        $grand_ward_round->delete();
        return back()->with('success','Deleted');
    }

    public function exportExcel()
    {
        return Excel::download(
            new GrandWardRoundsExport,
            'grand-ward-rounds-'.date('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf()
    {
        $rounds = GrandWardRound::with(['hospital','rotation','consultant','user'])->get();
        $pdf = Pdf::loadView('admin.grand_ward_rounds.pdf', compact('rounds'));
        return $pdf->download('grand-ward-rounds.pdf');
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

        $query = GrandWardRound::with('user')
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

    public function unmap($id)
    {
        GrandWardRound::where('id',$id)->update([
            'consultant_id'=>null
        ]);

        return back()->with('success','Supervisor unmapped');
    }
    
    public function toggle(Request $request, $id)
    {
        
        $round = GrandWardRound::findOrFail($id);

        $round->involvement = $round->involvement=='A'?'W':'A';
        $round->save();

        return response()->json(['status'=>'success']);
    }
}