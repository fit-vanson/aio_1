<?php

namespace App\Http\Controllers;

use App\Models\GoogleReview;
use Illuminate\Http\Request;

class GoogleReviewController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Review',
            'button' => []
        ];
        return view('review.index')->with(compact('header'));
    }

    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = GoogleReview::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = GoogleReview::select('count(*) as allcount')->count();

        // Get records, also we have included search filter as well
        $records = GoogleReview::with('project.da')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {


//            $btn = ' <a href="javascript:void(0)" onclick="editDev('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//            $btn = $btn.' <a href="javascript:void(0)"data-id="'.$record->id.'"  class="btn btn-danger deleteDev"><i class="ti-trash"></i></a>';
//            if($record->market_id == 1){
//                $btn = $btn.' <a  href="/api/get-token/'.$record->id.'" target="_blank" class="btn btn-info"><i class="mdi mdi-key"></i></a>';
//            }


            $logo = '<img class="rounded mx-auto d-block"  width="100px"  height="100px"  src="'.url('storage/projects/'.$record->project->da->ma_da.'/'.$record->project->projectname.'/lg114.png').'">';

            $logo = '<div class="media m-b-30">
                        <img class="d-flex align-self-start rounded mr-3" src="'.url('storage/projects/'.$record->project->da->ma_da.'/'.$record->project->projectname.'/lg114.png').'" alt="'.$record->project->projectname.'" height="64">
                        <div class="media-body">
                            <h5 class="mt-0 font-16">'.$record->project->projectname.'</h5>
                            <p>'.$record->package.'</p>
                        </div>
                    </div>';

            $data_arr[] = array(
                "id" => $record->id,
                "project_id" => $logo,
                "reviewId" => $record->reviewId,
                "userComment" => $record->userComment,
                "reviewerLanguage" => $record->reviewerLanguage,
                "thumbsDownCount" => $record->thumbsDownCount,
                "thumbsUpCount" => $record->thumbsUpCount,
                "starRating" => $record->starRating,
                "lastModifiedUser" => date('d-m-Y H:i:s',$record->lastModifiedUser),
                "developerComment" => $record->developerComment,
                "lastModifiedDeveloper" => date('d-m-Y H:i:s',$record->lastModifiedDeveloper),
            );

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }
}
