<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;

class CommentsController extends Controller
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
        //
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

        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        $validate=\Validator::make($params_array,[
            'content'=>'required|min:3|max:400',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }

        $comments= new Comment();

        $comments->user_id=$params->user_id;
        $comments->notepad_id=$params->notepad_id;
        $comments->content=$params->content;
        $comments->save();

         $data=array([
            'status'=>'success',
            'message'=>'Has comentado con exito.'
        ]);
        return response()->json($data,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comments=Comment::where('notepad_id',$id)->with('notepads')->with('users')->get();
        return response()->json(['comments'=>$comments],200);
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
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        $validate=\Validator::make($params_array,[
            'content'=>'required|min:3|max:400',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(),400);
        }

        $comment=Comment::where('user_id',$params->user_id)->where('id',$id)->get();
        $comments=Comment::findOrFail($comment[0]->id);

        $comments->content=$params->content;

        $comments->update();

        return response()->json(['status'=>"success","message"=>"Comentario actualizado con exito"],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comments=Comment::findOrFail($id);
        $comments->delete();
        return response()->json(['status'=>'success','message'=>'Comentario eliminado con exito'],201);
    }
}
