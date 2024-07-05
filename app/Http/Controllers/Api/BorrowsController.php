<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Borrows;
use App\Models\Books;
use App\Models\Members;

class BorrowsController extends Controller
{
    public function index()
    {
        $data = Borrows::orderBy('borrowed_date', 'asc')->get();
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
        $id_member = $request->id_member;
        $id_book = $request->id_book;

        $member = Members::find($id_member);
        if (!$member){
            return response()->json([
                'status'=>false,
                'message'=>'Member not found'
            ],404);
        }

        if ($member->penalized_until && $member->penalized_until > now()) {
            return response()->json([
                'status'=>false,
                'message'=>'Member currently penalized until '. $member->penalized_until
            ],400);
        }

        if (is_null($member->id_borrow)) {
            $member->id_borrow = json_encode([]);
        }

        $idBorrowArray = json_decode($member->id_borrow, true);

        if (count($idBorrowArray) >= 2){
            return response()->json([
                'status'=>false,
                'message'=>'Member already borrowed 2 books'
            ],400);
        }

        $book = Books::find($id_book);
        if (!$book){
            return response()->json([
                'status'=>false,
                'message'=>'Book not found'
            ],404);
        }

        if ($book->stock == 0) {
            return response()->json([
                'status'=>false,
                'message'=>'Book out of stock, cannot be borrowed'
            ],400);
        }
        
        $validator=Validator::make($request->all(), [
            'id_book' => 'required|exists:books,id',
            'id_member' => 'required|exists:members,id',
            'borrowed_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'Borrow Book Failed',
                'data'=>$validator->errors()
            ]);
        }

        $borrow = Borrows::create([
            'id_book' => $id_book,
            'id_member' => $id_member,
            'borrowed_date' => $request->borrowed_date,
            'returned_date' => null,
        ]);

        $idBorrowArray[] = $borrow->id;

        $member->id_borrow = json_encode($idBorrowArray);
        $member->save();

        $book->stock--;
        $book->save();

        return response()->json([
            'status'=>true,
            'message'=>'Borrow Book success',
        ],200);
    }

    public function show(string $id)
    {
        $data = Borrows::find($id);
        if (!$data){
            return response()->json([
                'status'=>false,
                'message'=>'Data not found'
            ],404);
        }
        if ($data) {
            return response()->json([
                'status'=>true,
                'message'=>'Data Found',
                'data'=>$data
            ],200);
        } 
    }

    public function update(Request $request, string $id)
    {   
        $borrow = Borrows::find($id);
        if (!$borrow) {
            return response()->json([
                'status' => false,
                'message' => 'Borrow record not found'
            ], 404);
        }

        $member = Members::find($borrow->id_member);
        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Member not found'
            ], 404);
        }

        $borrowIds = json_decode($member->id_borrow, true);

        if (is_array($borrowIds)) {
            $borrowIds = array_values(array_diff($borrowIds, [$borrow->id]));
            $member->id_borrow = json_encode($borrowIds);
        }

        $member->save();

        $book = Books::find($borrow->id_book);
        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ], 404);
        }
        $book->stock++;
        $book->save();

        $validator = Validator::make($request->all(), [
            'returned_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Returned Book Failed',
                'data' => $validator->errors()
            ], 400);
        }

        $returnedDate = Carbon::parse($request->returned_date);

        $borrow->returned_date = $returnedDate;
        $borrow->save();

        $borrowedDate = Carbon::parse($borrow->borrowed_date);
        $interval = $borrowedDate->diffInDays($returnedDate);

        if ($interval > 7) {
            $penaltyEndDate = $returnedDate->copy()->addDays(3);
            $member->penalized_until = $penaltyEndDate;
            $member->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Returned Book success',
        ], 200);
    }

    public function destroy(string $id)
    {
        //
    }
}
