<?php

namespace App\Http\Controllers\Zbsht;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class IndexController extends Controller
{

    public function index(){

        return view('zbsht.index.index');
    }
    //
}
