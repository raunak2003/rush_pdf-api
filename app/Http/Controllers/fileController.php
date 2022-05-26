<?php

namespace App\Http\Controllers;

use App\Models\file;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class fileController extends Controller
{
    public function store(Request $request)
    {

        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(),
                [
                    'category' => '',
                    'title' => 'required|max:20',
                    'file' => 'required|mimes:pdf|max:2048',
                ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            if ($file = $request->file('file')) {
                if ($category = $request->category == 'assignment') {
                    $path = $file->store('./userDocuments/assignment');

                    //store your file into directory and db
                    $save = new File();
                    $save->file = $path;
                    $save->user_id = $user->id;
                    $save->title = $request->title;
                    $save->save();

                    return response()->json([
                        "success" => true,
                        "message" => "File successfully uploaded",
                        "file" => $path,
                        "title" => $request->title,
                    ]);
                }
                if ($category = $request->category == '') {
                    $path = $file->store('./userDocuments');
                    //store your file into directory and db
                    $save = new File();
                    $save->file = $path;
                    $save->user_id = $user->id;
                    $save->title = $request->title;
                    $save->save();

                    return response()->json([
                        "success" => true,
                        "message" => "File successfully uploaded",
                        "file" => $path,
                        "title" => $request->title,
                    ]);
                }
                if ($category = $request->category == 'govt_documents') {
                    $path = $file->store('./userDocuments/govtDocuments');

                    //store your file into directory and db
                    $save = new File();
                    $save->file = $path;
                    $save->user_id = $user->id;
                    $save->title = $request->title;
                    $save->save();

                    return response()->json([
                        "success" => true,
                        "message" => "File successfully uploaded",
                        "file" => $path,
                        "title" => $request->title,
                    ]);
                }
                if ($category = $request->category == 'results') {
                    $path = $file->store('./public/userDocuments/results');

                    //store your file into directory and db
                    $save = new File();
                    $save->file = $path;
                    $save->user_id = $user->id;
                    $save->title = $request->title;
                    $save->save();

                    return response()->json([
                        "success" => true,
                        "message" => "File successfully uploaded",
                        "file" => $path,
                        "title" => $request->title,
                    ]);
                }
            }
        } catch (\Throwable$th) {
            return response()->json(['error' => 'some error occured'], 500);
        }
    }

    function list() {
        try {
            $user = auth()->user();
            $result = DB::table('files')->get()->where('user_id', $user->id);
            if ($result) {
                return response()->json([
                    "success" => true,
                    "list" => $result,
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "No Data Found",
                ], 404);
            }
        } catch (\Throwable$th) {
            return response()->json(['error' => 'some error occured'], 500);
        }

    }

    //delete function of files
    public function delete($id)
    {
        try {
            $delete = file::find($id);
            if ($delete) {
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
                ], 404);
            }
        } catch (\Throwable$th) {
            return response()->json(['error' => 'some error occured'], 500);
        }

    }
}
