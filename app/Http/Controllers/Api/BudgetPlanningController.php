<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BudgetPlanning;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class BudgetPlanningController extends Controller
{
    public function index()
    {
        $budgetPlanning = BudgetPlanning::latest()->paginate(6);
        return response()->json([
            'success' => true,
            'message' => 'BudgetPlanning data.',
            'data' => $budgetPlanning
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id' => 'required|exists:categories,id',
            'month' => 'required|date',
            'budget_amount' => 'required|numeric',
        ], [
            'month.required' => 'Field month required.',
            'month.date' => 'Field must date format.',
            
            'category_id.required' => 'Field category_id required.',
            'category_id.exists' => 'Category not found.',

            'budget_amount.required' => 'Field budget_amount required.',
            'budget_amount.numeric' => 'Field budget_amount must be a numeric.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $budgetPlanning = BudgetPlanning::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'month' => $request->month,
            'budget_amount' => $request->budget_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created BudgetPlanning successfully.',
            'data' => $budgetPlanning
        ], 201);
    }

    public function show(string $id)
    {
        $budgetPlanning = BudgetPlanning::find($id);
        if(!$budgetPlanning){
            return response()->json([
                'success' => false,
                'message' => 'Failed find Budget Planning.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Budget Planning Detail',
            'data' => $budgetPlanning,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'month' => 'required|date',
            'budget_amount' => 'required|numeric',
        ], [
            'month.required' => 'Field month required.',
            'month.date' => 'Field must date format.',
            
            'category_id.required' => 'Field category_id required.',
            'category_id.exists' => 'Category not found.',

            'budget_amount.required' => 'Field budget_amount required.',
            'budget_amount.numeric' => 'Field budget_amount must be a numeric.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $budgetPlanning = BudgetPlanning::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$budgetPlanning) {
            return response()->json([
                'success' => false,
                'message' => 'Budget Planning not found.'
            ], 404);
        }

        $budgetPlanning->update([
            'category_id' => $request->category_id,
            'month' => $request->month,
            'budget_amount' => $request->budget_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Update successfully.',
            'data' => $budgetPlanning
        ], 200);
    }

    public function destroy(string $id)
    {
         $budgetPlanning = BudgetPlanning::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$budgetPlanning) {
            return response()->json([
                'success' => false,
                'message' => 'Budget Planning not found.'
            ], 404);
        }

        $budgetPlanning->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delete Budget Planning successfully.',
        ], 200);
    }
}
