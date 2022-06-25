<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\DB;

class fileGet extends Controller
{
    public function index($id)
    {
        $fetchFile =  DB::table('files')->where('id', $id)->get('file');
        if($fetchFile){
            $path = public_path('documents\\'. $fetchFile[0]->file);
            return response()->file($path);
        }else{
            return response(json(['error','chla ja yahan se']));
        }
    }
}