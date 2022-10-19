<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleReviewController extends Controller
{
    public function index()
    {

        $header = [
            'title' => 'Review',

            'button' => [

            ]

        ];
        return view('review.index')->with(compact('header'));

    }
}
