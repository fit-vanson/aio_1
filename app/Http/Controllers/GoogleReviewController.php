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
        $records = GoogleReview::with('project_market','project.da')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {

            $mess = null;
            if($record->message){
                $mess = json_decode($record->message,true)['error']['message'];
            }

            $html = '<span>
                        <p><b>Title app: </b>'.$record->project->title_app.'</p>
                        <p><b>Package: </b>'.$record->package.'</p>
                        <p><b>Rating: </b>'.$record->project_market->bot_score.'</p>
                        <p><b>Download: </b>'.$record->project_market->bot_installs.'</p>
                        <p><b>Number Review: </b>'.$record->project_market->bot_numberReviews.'</p>
                        <p><b>Version: </b>'.$record->project_market->bot_appVersion.'</p>
                        <p><b>Message: </b>'.$mess.'</p>
                     </span>';

            $logo = '<a href="'.$record->project_market->app_link.'&hl='.$record->reviewerLanguage.'" target="_blank"> <img class="rounded mx-auto d-block" data-toggle="popover" data-placement="right" title="<h5>'.$record->project->projectname.'</h5>"  data-content="'.$html.'"  width="100px"  height="100px" src="'.url('storage/projects/'.$record->project->da->ma_da.'/'.$record->project->projectname.'/lg114.png').'"></a>';
            $data_arr[] = array(
                "id" => $record->id,
                "project_id" => $logo,
                "authorName" => $record->authorName,
                "reviewId" => $record->reviewId,
                "userComment" => $record->userComment,
                "reviewerLanguage" => $record->reviewerLanguage,
                "thumbsDownCount" => $record->thumbsDownCount,
                "thumbsUpCount" => $record->thumbsUpCount,
                "starRating" => $record->starRating,
                "status" => $record->status,
                "message" => $record->message,
                "lastModifiedUser" => date('d-m-Y H:i:s',$record->lastModifiedUser),
                "developerComment" => '<a href="#" data-pk="'.$record->id.'" class="editable" data-url="">'.$record->developerComment.'</a>',
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
