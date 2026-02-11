<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(6);
        return response()->json([
            'success' => true,
            'message' => 'Category data.',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'required|string'
        ], [
            'name.required' => 'Field name required.',
            'name.string' => 'Field name must be a string.',
            'name.max' => 'Field name max:255 characters.',

            'type.required' => 'Field type required.',
            'type.in' => 'Field type must be income or expense.',
            
            'icon.required' => 'Field icon required.',
            'icon.string' => 'Field icon must be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categories = Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created category successfully.',
            'data' => $categories
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::find($id);
        if(!$categories){
            return response()->json([
                'success' => false,
                'message' => 'Failed find category.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category Detail',
            'data' => $categories,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'required|string'
        ], [
            'name.required' => 'Field name required.',
            'name.string' => 'Field name must be a string.',
            'name.max' => 'Field name max:255 characters.',

            'type.required' => 'Field type required.',
            'type.in' => 'Field type must be income or expense.',

            'icon.required' => 'Field icon required.',
            'icon.string' => 'Field icon must be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $categories = Category::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$categories) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }

        $categories->update([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Update successfully.',
            'data' => $categories
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $categories = Category::whereId($id)
            ->whereUserId(auth()->id())
            ->first();

        if (!$categories) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }

        $categories->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delete category successfully.',
        ], 200);
    }
}
