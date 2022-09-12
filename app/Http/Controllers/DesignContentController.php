<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;

class DesignContentController extends Controller
{
    public function index(Request $request)
    {
        $projects = ProjectModel::has('lang')->where('status_design',0)->orwhere('status_design',1)->get();
        return view('design_content.index')->with(compact('projects'));

    }

    public function edit($id){
        $project = ProjectModel::find($id);
        return response()->json($project->load('lang','da'));

    }

    public function update(Request $request){
        $project = ProjectModel::find($request->project_id);
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
