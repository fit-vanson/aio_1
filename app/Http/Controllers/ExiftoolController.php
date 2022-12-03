<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ZipArchive;

class ExiftoolController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Exif Tools',

            'button' => [
//                'Create'            => ['id'=>'createNewApkUpload','style'=>'primary'],
//                'Build And Check'   => ['id'=>'build_check','style'=>'warning'],
//                'Status'            => ['id'=>'dev_status','style'=>'info'],
//                'KeyStore'          => ['id'=>'change_keystore','style'=>'success'],
//                'SDK'               => ['id'=>'change_sdk','style'=>'danger'],
//                'Upload Status'     => ['id'=>'change_upload_status','style'=>'secondary'],
            ]

        ];
        return view('exiftool.index')->with(compact('header'));
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {


        if($request->zip){
            $this->extractUploadedZip( $request);




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
        return response()->json(['success'=>'Thành công']);
    }


    function extractUploadedZip(Request $request){

        $zip = new ZipArchive();
        $zip1 = new ZipArchive();
        $status = $zip->open($request->file("zip")->getRealPath());
        if ($status !== true) {
            throw new \Exception($status);
        }
        else{
            $folder = uniqid();
            $path = storage_path('app/public/exiftool/'.$folder.'/');
            if (!\File::exists( $path)) {
                \File::makeDirectory($path, 777, true);
            }
            $zip->extractTo($path);
            $zip->close();

            $files = Storage::disk('exiftool')->allFiles($folder);
            foreach ($files as $file){
                try {
                    $this->exiftool($file);
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . 'exiftool : ' . $exception->getLine());
                }
            }
            $zip_file = $folder.'.zip';
            if($zip1 -> open($zip_file, ZipArchive::CREATE ) === TRUE) {
                $dir = opendir($path);

                while($file = readdir($dir)) {
                    if(is_file($path.$file)) {
                        $zip1 -> addFile($path.$file, $file);
                    }
                }
                $zip1 ->close();
            }


            return \Response::download($zip_file,  $zip_file, array('Content-Type: application/octet-stream','Content-Length: '. filesize($path.$zip_file)))->deleteFileAfterSend(true);
//            return \Response::download($zip_file,  $folder.'.zip', array('Content-Type: application/octet-stream','Content-Length: '))->deleteFileAfterSend(true);



        }
    }

    function exiftool($file){
        $path = storage_path('app/public/exiftool/'.$file);
        $img = Image::make($path);
        $img->save($path);
    }
}
