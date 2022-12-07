<?php

namespace App\Http\Controllers;

use App\Models\Exiftool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ZipArchive;
use function React\Promise\all;

class ExiftoolController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Exif Tools',
            'button' => []
        ];
        return view('exiftool.index')->with(compact('header'));
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
        $totalRecords = Exiftool::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = Exiftool::select('count(*) as allcount')

            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orwhere('name_ori', 'like', '%' . $searchValue . '%')
            ->orwhereHas('user', function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%');
            })

            ->count();
        // Get records, also we have included search filter as well
        $records = Exiftool::orderBy($columnName, $columnSortOrder)
            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orwhere('name_ori', 'like', '%' . $searchValue . '%')
            ->orwhereHas('user', function ($query) use ($searchValue) {
                $query
                    ->where('name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
//                "name" => '<a id="download" href="'.route('exiftool.downloadFile',"folder=$record->name").'" target="_blank">'.$record->name.'</a>',
                "name_ori" => '<span id="download" data-folder="'.$record->name.'"  >'.$record->name_ori.'</span>',
                "name" => '<span id="download" data-folder="'.$record->name.'"  >'.$record->name.'</span>',
                "user_id" => $record->user->name ?? "",
                "created_at" => $record->created_at->format('H:i:s d/m/Y'),
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

    public function create(Request $request)
    {
        $folder = uniqid();
        $path = storage_path('app/public/exiftool/'.$folder.'/');
        if($request->zip){
            $this->extractUploadedZip( $request,$path);
            $files = Storage::disk('exiftool')->allFiles($folder);
            foreach ($files as $file){
                try {
                    $this->exiftool($file);
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . 'exiftool : ' . $exception->getLine());
                }
            }
            $this->zipFile($folder, $path);
            $data = new Exiftool();
            $data->name_ori = $request->zip->getClientOriginalName();
            $data->name = $folder;
            $data->user_id = Auth()->user()->id;
            $data->save();

        }

        if($request->file){
            $files = $request->file;
            $folder = uniqid();
            $path = storage_path('app/public/exiftool/'.$folder.'/');
            if (!file_exists($path)) {
                mkdir($path, 777, true);
            }
            $file = $request->file;
            $file_name = $file->getClientOriginalName();
            $file->move($path, $file_name);

            $data = new ApkUploadConvert();
            $data->name = $file_name;
            $data->filename = $folder;
            $data->save();
        }

        return response()->json(['success'=>'Thành công','download'=>$folder]);
    }


    function extractUploadedZip(Request $request,$path){

        $zip = new ZipArchive();
        $status = $zip->open($request->file("zip")->getRealPath());
        if ($status !== true) {
            throw new \Exception($status);
        }
        else{
            if (!\File::exists( $path)) {
                \File::makeDirectory($path, 777, true);
            }
            $zip->extractTo($path);
            $zip->close();
            return response()->json(['success'=>'Thành công']);
        }
    }

    function exiftool($file){
        $path = storage_path('app/public/exiftool/'.$file);
        $img = Image::make($path);
        $img->save($path);
    }

    function zipFile($folder,$path){

        $zip = new ZipArchive();
        $zip_file = $folder.'.zip';
        $zip->open($path.$zip_file,ZIPARCHIVE::OVERWRITE | ZIPARCHIVE::CREATE);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($files as $name => $file)
        {
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
                $relativePath = $folder.'/' . substr($filePath, strlen($path));
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public function downloadFile(){
        try {
            $folder = $_GET['folder'];
            $file = Exiftool::where('name',$folder)->first();
            if($file){
                if(Auth()->user()->id == $file->user_id){
                    $path = storage_path('app/public/exiftool/'.$folder.'/');
                    $zip_file = $folder.'.zip';
                    $headers = [ 'Content-Type' => 'application/octet-stream' ];
                    return response()->download($path.$zip_file, $zip_file,$headers);
                }else{
                    return response()->json(['error'=>'Không thể tải.']);
                }
            }else{
                return response()->json(['error'=>'File k tồn tại.']);
            }



        }catch (\Exception $exception) {
            Log::error('Message: Download' . $exception->getMessage() . '--' . $exception->getLine());
        }


    }

    public function delete($id){
        $Exiftool = Exiftool::findorFail($id);
        $Exiftool->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }


}
