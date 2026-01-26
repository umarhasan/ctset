<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Dops, DopsRotation, DopsLevel, DopsRating,
    DopsCompetencyDefinition, DopsCompetencyDefinitionDetail,
    Rotation, Level, Rating, Competency
};

class DopController extends Controller
{
    // ========== INDEX PAGE ==========
    public function index()
    {
        $dops = Dops::with(['rotations','levels','ratings','competencies.definitions'])->latest()->get();

        return view('admin.dops.index', [
            'rotations' => Rotation::all(),
            'levels' => Level::all(),
            'ratings' => Rating::all(),
            'competencies' => Competency::all(),
            'dops' => $dops
        ]);
    }

    // ========== STORE NEW DOPS ==========
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'rotation_ids'=>'required|array',
            'level_ids'=>'required|array',
            'rating_ids'=>'required|array',
            'competencies'=>'required|array',
            'rotation_html'=>'required',
            'level_html'=>'required',
            'rating_html'=>'required',
            'competency_html'=>'required'
        ]);

        DB::transaction(function() use($request) {
            $dops = Dops::create([
                'title' => $request->title,
                'steps' => $request->steps,
                'level' => $request->level_html,
                'raiting' => $request->rating_html,
                'competencies' => $request->competency_html,
                'status' => 1
            ]);

            // Rotations
            foreach($request->rotation_ids as $rid) {
                DopsRotation::create(['dopsid'=>$dops->id,'rotationid'=>$rid,'status'=>1]);
            }

            // Levels
            foreach($request->level_ids as $lid) {
                DopsLevel::create(['dopsid'=>$dops->id,'levelid'=>$lid,'status'=>1]);
            }

            // Ratings
            foreach($request->rating_ids as $rid) {
                DopsRating::create(['dropsid'=>$dops->id,'ratingid'=>$rid,'status'=>1]);
            }

            // Competencies + definitions
            foreach($request->competencies as $seq => $comp) {
                $def = DopsCompetencyDefinition::create([
                    'dopsid'=>$dops->id,
                    'cdid'=>$comp['competency_id'],
                    'dcdseq'=>$seq+1,
                    'status'=>1
                ]);

                if(!empty($comp['definitions'])){
                    foreach($comp['definitions'] as $i => $item){
                        DopsCompetencyDefinitionDetail::create([
                            'dcdid'=>$def->id,
                            'title'=>$item,
                            'dcddseq'=>$i+1,
                            'status'=>1
                        ]);
                    }
                }
            }
        });

        return redirect()->route('dops.index')->with('success','DOPS created successfully');
    }

    public function edit($id)
    {
        $dops = Dops::with(['rotations','levels','ratings','competenciesWithDefinitions.definitions'])->findOrFail($id);


        return view('admin.dops.edit', [
            'dops' => $dops,
            'rotations' => Rotation::all(),
            'levels' => Level::all(),
            'ratings' => Rating::all(),
            'competencies' => Competency::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'rotation_ids'=>'required|array',
            'level_ids'=>'required|array',
            'rating_ids'=>'required|array',
            'competencies'=>'required|array',
            'rotation_html'=>'required',
            'level_html'=>'required',
            'rating_html'=>'required',
            'competency_html'=>'required'
        ]);

        DB::transaction(function() use($request,$id){
            $dops = Dops::findOrFail($id);

            // Update main record
            $dops->update([
                'title'=>$request->title,
                'steps'=>$request->steps,
                'level'=>$request->level_html,
                'raiting'=>$request->rating_html,
                'competencies'=>$request->competency_html
            ]);

            // Delete old relations
            DopsRotation::where('dopsid',$id)->delete();
            DopsLevel::where('dopsid',$id)->delete();
            DopsRating::where('dropsid',$id)->delete();

            $defs = DopsCompetencyDefinition::where('dopsid',$id)->get();
            foreach($defs as $def) DopsCompetencyDefinitionDetail::where('dcdid',$def->id)->delete();
            DopsCompetencyDefinition::where('dopsid',$id)->delete();

            // Insert new relations
            foreach($request->rotation_ids as $rid) DopsRotation::create(['dopsid'=>$dops->id,'rotationid'=>$rid,'status'=>1]);
            foreach($request->level_ids as $lid) DopsLevel::create(['dopsid'=>$dops->id,'levelid'=>$lid,'status'=>1]);
            foreach($request->rating_ids as $rid) DopsRating::create(['dropsid'=>$dops->id,'ratingid'=>$rid,'status'=>1]);

            foreach($request->competencies as $seq => $comp){
                $def = DopsCompetencyDefinition::create([
                    'dopsid'=>$dops->id,
                    'cdid'=>$comp['competency_id'],
                    'dcdseq'=>$seq+1,
                    'status'=>1
                ]);

                if(!empty($comp['definitions'])){
                    foreach($comp['definitions'] as $i=>$title){
                        DopsCompetencyDefinitionDetail::create([
                            'dcdid'=>$def->id,
                            'title'=>$title,
                            'dcddseq'=>$i+1,
                            'status'=>1
                        ]);
                    }
                }
            }
        });

        return redirect()->route('dops.index')->with('success','DOPS updated successfully');
    }


    public function destroy($id)
    {
        DB::transaction(function() use($id){
            DopsRotation::where('dopsid',$id)->delete();
            DopsLevel::where('dopsid',$id)->delete();
            DopsRating::where('dropsid',$id)->delete();

            $defs = DopsCompetencyDefinition::where('dopsid',$id)->get();
            foreach($defs as $def) DopsCompetencyDefinitionDetail::where('dcdid',$def->id)->delete();
            DopsCompetencyDefinition::where('dopsid',$id)->delete();

            Dops::where('id',$id)->delete();
        });

        return redirect()->route('dops.index')->with('success','DOPS deleted successfully');
    }
}
