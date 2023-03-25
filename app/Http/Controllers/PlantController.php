<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use GuzzleHttp\Psr7\Request;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Plants = Plant::with('category_id')->get();
        return $Plants->toJson();
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
        // $rules = [
        //     'title'  => 'required|min:2',
        //     'date' => 'required',
        //     'artist_ID' => 'required',
        //     'album_ID' => 'required',
        //     'user_ID' => 'required'
        // ];
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', //unique:users check if the user is already exist in the database
            'price' => 'required|float',
        ]);


        // $input = $request->all();
        // Plant::create($input);
        // return 'creation sucss';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Plant = Plant::findOrFail($id);
        if (is_null($Plant)) {
            return $this->returnError('E016', 'Somthing not correct for this show Plant please try again!');
        }
        return $Plant->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Plant = Plant::findOrFail($id);
        if (is_null($Plant)) {
            return $this->returnError('E016', 'Somthing not correct for this EDIT lyric please try again!');
        }
        return $Plant->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Plant = Plant::findOrFail($id);
        if (is_null($Plant)) {
            return $this->returnError('E016', 'Somthing not correct for this update lyric please try again!');
        }
        $input = $request->all();
        $Plant->update($input);
        return $Plant->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Plant = Plant::find($id);
        if (is_null($Plant)) {
            return $this->returnError('E013', 'deleted failed!');
        }
        $Plant->delete();
        return $this->returnError('E013', 'Deleted Success');
    }
}
