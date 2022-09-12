<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;

class DesignContentController extends Controller
{
    public function index(Request $request)
    {

//        dd($project);
//        $action = $request->action;
//
//
//        switch ($action){
//            case 'checkdesign':
//                $project = ProjectModel::has('lang')->where('status_design',0)->orwhere('status_design',1)->get();
//                return view('design_content.index')->with(compact('project'));
//                break;
//            case 'faildesign':
//                $project = ProjectModel::has('lang')->where('status_design',2)->get();
//                break;
//            case 'passdesign':
//                $project = ProjectModel::has('lang')->where('status_design',4)->get();
//                break;
//        }

        $projects = ProjectModel::has('lang')->where('status_design',0)->orwhere('status_design',1)->get();

        return view('design_content.index')->with(compact('projects'));

    }

    public function edit($id){
        $project = ProjectModel::find($id);
        return response()->json($project->load('lang','da'));

    }

    public function update(Request $request){
        dd($request->all());
    }
}
