<?php

namespace App\Http\Controllers;

use App\Models\file;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class fileController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'category' => '',
                'title'=>'required|max:20',
                'file' => 'required|mimes:pdf|max:2048',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($file = $request->file('file')) {
            if($category = $request->category=='assignment'){
            $path = $file->store('./userDocuments/assignment');

            //store your file into directory and db
            $save = new File();
            $save->file = $path;
            $save->user_id = 1;
            $save->title=$request->title;
            $save->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path,
                "title"=>$request->title
            ]);
          }
          if($category = $request->category==''){
            $path = $file->store('./userDocuments');

            //store your file into directory and db
            $save = new File();
            $save->file = $path;
            $save->user_id = 1;
            $save->title=$request->title;
            $save->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path,
                "title"=>$request->title
            ]);
          }
          if($category = $request->category=='govt_documents'){
            $path = $file->store('./userDocuments/govt_documents');

            //store your file into directory and db
            $save = new File();
            $save->file = $path;
            $save->user_id = 1;
            $save->title=$request->title;
            $save->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path,
            ]);
          }
          if($category = $request->category=='results'){
            $path = $file->store('./userDocuments/results');

            //store your file into directory and db
            $save = new File();
            $save->file = $path;
            $save->user_id = 1;
            $save->title=$request->title;
            $save->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path,
            ]);
          }
        }

    }

//list of all files
    function list() {
        $result = DB::table('files')->get()->where('user_id','1');
        if ($result) {
            return response()->json([
                "success" => true,
                "list" => $result,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No Data Found",
            ]);
        }
    }

    //delete function of files
    public function delete($id)
    {
        $delete = file::find($id);
        if ($delete){
            $file_delete = $delete->delete();
            if ($file_delete) {
                return response()->json([
                    "success" => true,
                    "message" => "Record has been deleted",
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "NO DATA MATCHED",
            ]);
        }
    }
}
