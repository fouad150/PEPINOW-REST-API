<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Role::with('users')->get();
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

        $request->validate([
            'role' => 'required|string|max:30',
        ]);

        $input = $request->all();
        Role::create($input);
        return 'the role has been created successfully';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->find($role->id);
        if (!$role) {
            return response()->json(['message' => 'role not found'], 404);
        }
        return response()->json($role, 200);
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
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role' => 'required|string|max:30',
        ]);

        $role->update($request->all());

        if (!$role) {
            return response()->json(['message' => 'role not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "role Updated successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        if (!$role) {
            return response()->json([
                'message' => 'role not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'role deleted successfully'
        ], 200);
    }
}
