<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Dops,
    DopsRotation,
    DopsLevel,
    DopsRating,
    DopsCompetencyDefinition,
    DopsCompetencyDefinitionDetail,
    Rotation,
    Level,
    Rating,
    Competency
};

class DopController extends Controller
{
    public function index()
    {
        return view('admin.dops.index', [
            'rotations'    => Rotation::all(),
            'levels'       => Level::all(),
            'ratings'      => Rating::all(),
            'competencies' => Competency::all(),
            'dops'         => Dops::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required',
            'rotation_ids'   => 'required|array',
            'level_id'       => 'required|array',
            'rating_id'      => 'required|array',
            'competencies'   => 'required|array',
            'level_html'     => 'required',
            'rating_html'    => 'required',
            'competency_html'=> 'required',
        ]);

        DB::transaction(function () use ($request) {

            $dops = Dops::create([
                'title'        => $request->title,
                'steps'        => $request->steps,
                'level'        => $request->level_html,
                'raiting'      => $request->rating_html,
                'competencies' => $request->competency_html,
                'status'       => 1,
            ]);

            foreach ($request->rotation_ids as $rid) {
                DopsRotation::create([
                    'dopsid'     => $dops->id,
                    'rotationid' => $rid,
                    'Status'     => 1
                ]);
            }

            foreach ($request->level_id as $lid) {
                DopsLevel::create([
                    'dopsid'  => $dops->id,
                    'levelid' => $lid,
                    'Status'  => 1
                ]);
            }

            foreach ($request->rating_id as $rid) {
                DopsRating::create([
                    'dropsid'  => $dops->id,
                    'ratingid' => $rid,
                    'Status'   => 1
                ]);
            }

            foreach ($request->competencies as $seq => $comp) {

                $def = DopsCompetencyDefinition::create([
                    'dopsid'    => $dops->id,
                    'cdid'      => $comp['competency_id'],
                    'dcdseq'    => $seq + 1,
                    'mNegative' => $comp['negative'] ?? null,
                    'mNeutral'  => $comp['neutral'] ?? null,
                    'mPositive' => $comp['positive'] ?? null,
                    'status'    => 1
                ]);

                if (!empty($comp['items'])) {
                    foreach ($comp['items'] as $i => $item) {
                        DopsCompetencyDefinitionDetail::create([
                            'dcdid'   => $def->id,
                            'title'   => $item,
                            'dcddseq' => $i + 1,
                            'Status'  => 1
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'DOPS created successfully');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            DopsRotation::where('dopsid',$id)->delete();
            DopsLevel::where('dopsid',$id)->delete();
            DopsRating::where('dropsid',$id)->delete();

            $defs = DopsCompetencyDefinition::where('dopsid',$id)->get();
            foreach($defs as $def){
                DopsCompetencyDefinitionDetail::where('dcdid',$def->id)->delete();
            }

            DopsCompetencyDefinition::where('dopsid',$id)->delete();
            Dops::where('id',$id)->delete();
        });

        return back()->with('success','DOPS deleted');
    }
}
