<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Books;

class BooksController extends Controller
{
    public function index()
    {
        $data = Books::orderBy('title', 'asc')->get();
        if($data->isEmpty()){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        } else {
            return response()->json([
                'status'=>true,
                'message'=>'Data Found',
                'data'=>$data
            ],200);
        }
    }

    public function store(Request $request)
    {
        $book = new Books;

        $rules=[
            'code'=>'required',
            'title'=>'required',
            'author'=>'required',
            'stock'=>'required',
        ];

        $validator=Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Store Data Failed',
                'data'=>$validator->errors()
            ]);
        }

        $book->code = $request->code;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->stock = $request->stock;

        $post = $book->save();
        return response()->json([
            'status'=>true,
            'message'=>'Store Data success',
            // 'data'=>$book
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get a specific book by ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="message", type="string", example="Data Found"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $data = Books::find($id);
        if($data){
            return response()->json([
                'status'=>true,
                'message'=>'Data Found',
                'data'=>$data
            ],200);
        } else {
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {
        $book = Books::find($id);
        if(!$book){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        }
        
        $validator=Validator::make($request->all(), [
            'title'=>'required',
            'author'=>'required',
            'stock'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Update Data Failed',
                'data'=>$validator->errors()
            ]);
        }

        $book->title = $request->title;
        $book->author = $request->author;
        $book->stock = $request->stock;

        $post = $book->save();
        return response()->json([
            'status'=>true,
            'message'=>'Update Data Success',
        ],200);
    }

    public function destroy(string $id)
    {
        $book = Books::find($id);
        if(empty($book)){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        }

        $post = $book->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Delete Data Success',
        ],200);
    }
}
