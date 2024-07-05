<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Members;

class MembersController extends Controller
{
    public function index()
    {
        $data = Members::orderBy('name', 'asc')->get();
        if(!$data){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        } else{
            return response()->json([
                'status'=>true,
                'message'=>'Data Found',
                'data'=>$data
            ],200);
        } 
    }

    public function store(Request $request)
    {
        $member = new Members;

        $rules=[
            'code'=>'required',
            'name'=>'required',
        ];

        $validator=Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Store Data Failed',
                'data'=>$validator->errors()
            ]);
        }

        $member->code = $request->code;
        $member->name = $request->name;

        $post = $member->save();
        return response()->json([
            'status'=>true,
            'message'=>'Store Data success',
            // 'data'=>$member
        ],200);
    }

    public function show(string $id)
    {
        $data = Members::find($id);
        if(!$data){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        } else{
            return response()->json([
                'status'=>true,
                'message'=>'Data Found',
                'data'=>$data
            ],200);
        } 
    }

    public function update(Request $request, string $id)
    {
        $member = Members::find($id);
        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Member not found'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Update Data Failed',
                'data' => $validator->errors()
            ]);
        }

        $member->name = $request->name;

        $member->save();

        return response()->json([
            'status' => true,
            'message' => 'Update Data success',
            'data' => $member
        ], 200);
    }

    public function destroy(string $id)
    {
        $member = Members::find($id);
        if(empty($member)){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        }

        $post = $member->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Delete Data Success',
            // 'data'=>$member
        ],200);
    }
}
