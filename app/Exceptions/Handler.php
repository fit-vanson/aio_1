<?php

namespace App\Exceptions;

use App\Models\CheckApi;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        $response = parent::render($request, $exception);

        if ($response->status() === 404) {
            $url = $_SERVER['REQUEST_URI'];
            $data = CheckApi::where('checkapi_url',$url)->first();

            if(isset($data)){
                if($data->checkapi_type==0){
                    return response()->json(json_decode($data->checkapi_code));
                }else{
                    $result =  $data->checkapi_code;
                    return response()->view('errors.404',compact(['result']));
                }
            }

        }

        return $response;
    }
}
