<?php

namespace App\Http\Controllers;

use App\Models\file;
use Illuminate\Http\Request;
use Validator;

class fileController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                // 'user_id' => 'required',
                'file' => 'required|mimes:pdf|max:2048',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($file = $request->file('file')) {
            $path = $file->store('./userDocuments/');

            //store your file into directory and db
            $save = new File();
            $save->file = $path;
            $save->user_id = 1;
            $save->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path,
            ]);

        }

    }

    function list() {
        $result = file::all();
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