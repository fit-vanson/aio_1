<?php

namespace App\Http\Controllers;

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

    public function create(Request $request)
    {
//        $folder = $request->folder;
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
            $this->deleteDirectory($path);


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
//        return response()->download($folder.'.zip', $folder.'.zip', array('Content-Type: application/octet-stream','Content-Length: '. filesize($folder.'.zip')))->deleteFileAfterSend(true);

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
        $zip->open($zip_file,ZIPARCHIVE::OVERWRITE | ZIPARCHIVE::CREATE);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
                // extracting filename with substr/strlen
                $relativePath = $folder.'/' . substr($filePath, strlen($path) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
//        return true;
//        return response()->download($zip_file, $zip_file)->deleteFileAfterSend(true);

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
            $zip_file = $folder.'.zip';
            return \Response::download($zip_file, $zip_file,array('Content-Type: application/octet-stream','Content-Length: '. filesize($zip_file)))->deleteFileAfterSend(true);
        }catch (\Exception $exception) {
            Log::error('Message: Download' . $exception->getMessage() . '--' . $exception->getLine());
        }


    }


}
