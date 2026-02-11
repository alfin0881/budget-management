<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(6);
        return response()->json([
            'success' => true,
            'message' => 'Transaction data.',
            'data' => $transactions
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'string|required',
            'type' => 'required|in:income,expense'
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transactions = Transaction::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created Transaction successfully.',
            'data' => $transactions
        ], 201);
    }

    public function show(string $id)
    {
        $transactions = Transaction::find($id);
        if(!$transactions){
            return response()->json([
                'success' => false,
                'message' => 'Failed find Transaction.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction Detail',
            'data' => $transactions,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'string|required',
            'type' => 'required|in:income,expense'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $transactions = Transaction::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$transactions) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found.'
            ], 404);
        }

        $transactions->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => $request->type,
            'transaction_date' => $request->transaction_date,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Update successfully.',
            'data' => $transactions
        ], 200);
    }

    public function destroy(string $id)
    {
         $transactions = Transaction::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$transactions) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found.'
            ], 404);
        }

        $transactions->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delete Transaction successfully.',
        ], 200);
    }
}
