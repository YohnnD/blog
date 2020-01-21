<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;

class TopicController extends Controller
{
    public function __construct(){
        $this->middleware('jwt',["only"=>['index','update','destroy','show']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::all();
        return response()->json([$topics],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);
        $validate = \Validator::make($params_array,[
            'name'=>'required|min:3|max:100',
            'description'=>'required',
            'tag' => 'required'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        
        $topic = new Topic();
        $topic->name = $params->name;
        $topic->description = $params->description;
        $topic->tag = $params->tag;
        $topic->save();
        $data = [
            'status' => 'success',   
            'message' => 'El tema se ha registrado con éxito'  
        ];
        return response()->json([$data],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        return response()->json([$topic],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $topic = Topic::findOrFail($id);
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        $validate = \Validator::make($params_array, [
            'name' => 'required|min:3',
            'description' => 'required',
            'tag' => 'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        $topic->name = $params->name;
        $topic->description = $params->description;
        $topic->tag = $params->tag;
        $topic->update();
        $data = [
            'status' => 'success',
            'message' => 'El tema se ha actualizado con éxito'
        ];
        return response()->json([$data],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();
        $data = [
            'status' => 'success',
            'message' => 'El tema se ha eliminado con éxito'
        ];
        return response()->json([$data],201);
    }
}
