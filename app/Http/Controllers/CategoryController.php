<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:api"], ["except" => ["index", "show", "getPlantesOfCategory"]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('plants')->get();
        return response()->json([
            'record' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize("create", Auth::user());
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['error' => 'You are not authorized to add new categories'], 403);
        }

        $request->validate([
            'category' => 'required|string|max:30',
        ]);

        $input = $request->all();
        Category::create($input);
        return 'the category has been created successfully';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->find($category->id);
        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }
        return response()->json($category, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // $this->authorize("update", Auth::user());
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['error' => 'You are not authorized to update the categories'], 403);
        }
        $request->validate([
            'category' => 'required|string|max:30',
        ]);

        $category->update($request->all());

        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "category Updated successfully!",
            'category' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // $this->authorize("delete", Auth::user());
        $user = Auth::user();
        if ($user->role_id != 1) {
            return response()->json(['error' => 'You are not authorized to delete categories'], 403);
        }

        $category->delete();

        if (!$category) {
            return response()->json([
                'message' => 'category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'category deleted successfully'
        ], 200);
    }

    public function getPlantesOfCategory($category_id)
    {
        // $this->authorize('view categories', Category::class);
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'category not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'all plants by category',
            'data' => $category->plantes
        ], 200);
    }
}
