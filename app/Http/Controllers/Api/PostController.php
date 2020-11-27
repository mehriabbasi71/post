<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => Post::all()], 200);
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
        $rules = [
            'title' => 'required',
            'body' => 'required',
        ];
        $messages = [
            'title.required' => 'A title is required',
            'body.required' => 'A body is required',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response(['error' => $errors], 500);
        }
        //
        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->status = true;
        $post->user_id = $request->user()->id;
        $post->save();

        return response(['data' => $post], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        if(!$post) {
            return response(['message' => "Post not found"], 404);
        }
        return response(['data' => $post], 200);
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
        //
        $post = Post::find($id);
        if(!$post) {
            return response(['message' => "Post not found"], 404);
        }
        $post->title = is_null($request->title) ? "" : $request->title;
        $post->body = is_null($request->body) ? "" : $request->body;
        $post->save();

        return response(['data' => $post], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        if(!$post) {
            return response(['message' => "Post not found"], 404);
        }
        if($post->delete()) {
            return response(['message' => "Deleted successfully"], 200);
        }
        return response(['message' => "Delete failed"], 404);
    }

    /**
     * Activate the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        //
        $post = Post::find($id);
        if(!$post) {
            return response(['message' => "Post not found"], 404);
        }
        $post->status = !$post->status;
        if($post->save()) {
            return response(['data' => $post,'message' => "Changed successfully"], 200);
        }
        return response(['message' => "Changing failed"], 404);
    }
}
