<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrandWardRound;
use App\Models\Hospital;
use App\Models\Rotation;
use App\Models\User;
use App\Services\WardRoundService;
use App\Helpers\WardRoundHelper;
use Illuminate\Http\Request;

class GrandWardRoundController extends Controller
{
    public function index()
    {
        return view('admin.grand_ward_rounds.index', [
            'rounds' => GrandWardRound::with(['hospital','rotation','consultant'])->latest()->get(),
            'hospitals' => Hospital::all(),
            'rotations' => Rotation::all(),
            'consultants' => User::role('consultant')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'=>'required|date',
            'from_time'=>'required',
            'hospital_id'=>'required',
            'rotation_id'=>'nullable',
            'consultant_id'=>'nullable',
            'involvement'=>'required|in:A,W',
            'consultant_fees'=>'nullable|array',
            'lat'=>'nullable',
            'long'=>'nullable'
        ]);

        $data['user_id'] = auth()->id();

        $round = GrandWardRound::create($data);
        WardRoundHelper::generateToken($round);

        return back()->with('success','Grand Ward Round Added');
    }

    public function end(GrandWardRound $round, WardRoundService $service)
    {
        $service->endActivity($round);
        return back()->with('success','Activity Ended');
    }

    public function toggleStatus(GrandWardRound $round, WardRoundService $service)
    {
        $service->toggleStatus($round);
        return response()->json(['status'=>$round->status_text]);
    }

    public function destroy(GrandWardRound $round)
    {
        $round->delete();
        return back()->with('success','Deleted');
    }

    public function performanceData()
    {
        return GrandWardRound::selectRaw('consultant_id, COUNT(*) as total')
            ->whereNotNull('consultant_id')
            ->groupBy('consultant_id')
            ->with('consultant:id,name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

}
