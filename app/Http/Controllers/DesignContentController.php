<?php

namespace App\Http\Controllers;

use App\Models\Da;
use App\Models\Project;

use Illuminate\Http\Request;

class DesignContentController extends Controller
{
    public function index(Request $request)
    {

//        $da = Da::has('project')
//            ->with('project')
//            ->whereHas('project', function ($query) {
//                return $query->whereIN('status_design',[0,1]);
//            })
//            ->get();

//        foreach ($da as $item){
//            dd($item);
//        }
//        dd($da);

        $projects = Project::has('lang')->whereIN('status_design',[0,1])->orderByDesc('projectname')->get();
        return view('design_content.index')->with(compact('projects'));

    }

    public function edit($id){
        $project = Project::find($id);
        return response()->json($project->load('lang','da','ma_template'));

    }

    public function update(Request $request){
        $project = Project::find($request->project_id);
        $project->notes_design = $request->notes_design;
        $action = $request->action;
        switch ($action){
            case 1:
                $project->status_design = 4;
                break;
            case 0:
                $project->status_design = 2;
                break;
        }
        $project->save();
        return response()->json(['success'=>'Cập nhật thành công','id'=>$project->projectid]);

    }
}
