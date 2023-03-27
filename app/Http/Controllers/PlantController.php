<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:api"], ["except" => ["index", "show"]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Plants = Plant::with('category')->get();
        return response()->json([
            'record' => $Plants
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
        // $this->authorize('create', Auth::user());
        $user = Auth::user();
        if ($user->role_id == 3) {
            return response()->json(['error' => 'You are not authorized to create new plants'], 403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'category_id' => 'required|int'
        ]);


        $input = $request->all();
        Plant::create($input);
        return 'the plant has been created successfully';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Plant $Plant)
    {
        $Plant->find($Plant->id);
        if (!$Plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }
        return response()->json($Plant, 200);
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
    public function update(Request $request, Plant $Plant)
    {
        // $this->authorize('update', Auth::user());
        $user = Auth::user();
        if ($user->role_id == 3) {
            return response()->json(['error' => 'You are not authorized to update new plants'], 403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'category_id' => 'required|int'
        ]);

        $Plant->update($request->all());

        if (!$Plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Plant Updated successfully!",
            'Plant' => $Plant
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plant $plant)
    {
        // $this->authorize('delete', Auth::user());
        $user = Auth::user();
        if ($user->role_id == 3) {
            return response()->json(['error' => 'You are not authorized to delete the plants'], 403);
        }

        $plant->delete();

        if (!$plant) {
            return response()->json([
                'message' => 'plant not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'plant deleted successfully'
        ], 200);
    }
}
