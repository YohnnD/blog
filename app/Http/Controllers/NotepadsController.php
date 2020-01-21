<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notepad;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NotepadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notepads = Notepad::all();
        return response()->json([$notepads],200);
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
        $image = $request->file('image_path');
        $params = json_decode($json);
        $params_array = json_decode($json,true);
        $validate = \Validator::make($params_array,[
            'tittle' => 'required|min:3|max:100',
            'content' => 'required',
            'user_id' => 'required'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        
        $notepad = new Notepad();
        $notepad->tittle = $params->tittle;
        $notepad->content = $params->content;
        if ($image) {
            $image_path_name = time() . $image->getClientOriginalName();
            Storage::disk('notepads')->put($image_path_name, File::get($image));
            $notepad->image_path = $image_path_name;
        } else {
            $notepad->image_path = null;
        }
        // $notepad->user_id = \Auth::user()->id;
        $notepad->user_id = $params->user_id;
        $notepad->save();
        $data = [
            'status' => 'success',   
            'message' => 'La publicación se ha registrado con éxito'  
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
        $notepad = Notepad::findOrFail($id);
        return response()->json([$notepad],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $notepad = Notepad::findOrFail($id);
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        $validate = \Validator::make($params_array, [
            'tittle' => 'required|min:3|max:100',
            'content' => 'required',
            'user_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        $image = $request->file('image_path');
        $notepad->tittle = $params->tittle;
        $notepad->content = $params->content;
        $old_image = $notepad->image_path;
        if($old_image == null){
            if($image) {
                $image_name = time() . $image->getClientOriginalName(); // Nombre de la imagen
                Storage::disk('notepads')->put($image_name, File::get($image));
                $notepad->image_path = $image_name;
            }
            $notepad->update();
        }
        else{
            Storage::disk('notepads')->delete($old_image);
            if($image) {
                $image_name = time() . $image->getClientOriginalName(); // Nombre de la imagen
                Storage::disk('notepads')->put($image_name, File::get($image));
                $notepad->image_path = $image_name;
            }
            $notepad->update();
        }
        // $notepad->update();
        $data = [
            'status' => 'success',
            'message' => 'La publicación se ha actualizado con éxito'
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
        $notepad = Notepad::findOrFail($id);
        $notepad->delete();
        $data = [
            'status' => 'success',
            'message' => 'La publicación se ha eliminado con éxito'
        ];
        return response()->json([$data],201);
    }
}
