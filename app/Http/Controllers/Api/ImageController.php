<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response(['data' => Image::all()], 200);
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
        //validation
        $rules = [
            'alt' => 'required',
            'file' => 'required|file',
        ];
        $messages = [
            'alt.required' => 'A alt is required',
            'file.required' => 'A file is required',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response(['error' => $errors], 500);
        }

        //file upload
        $data = file_get_contents($request->file->getPathname());
        $dataURI = 'data:image/' . $request->file->getClientOriginalExtension() . ';base64,' . base64_encode($data);
        list($width, $height) = getimagesize($request->file->getPathname());
        $imageName = (string) time()."-".Str::of($request->file->getClientOriginalName())
            ->before('.')
            ->slug()
            ->append('.')
            ->append($request->file->getClientOriginalExtension());
        $request->file->move('uploads', $imageName);

        //new image resize
        $fileName = "small-".$imageName;
        $img = \Intervention\Image\Facades\Image::make(public_path('/uploads/' . $imageName));
        $img->stream();
        $img->resize(round($width/2), null, function ($constraint) {
            $constraint->aspectRatio();
        })->save( public_path('/uploads/' . $fileName) );

        $image = new Image();
        $image->alt = $request->alt;
        $image->name = $imageName;
        $image->uri = $dataURI;
        $image->extension = $request->file->getClientOriginalExtension();
        $image->mime_type = $request->file->getClientMimeType();
        $image->width = $width;
        $image->height = $height;
        $image->user_id = $request->user()->id;
        $image->save();

        return response(['data' => $image], 200);
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
        $image = Image::find($id);
        if(!$image) {
            return response(['message' => "Image not found"], 404);
        }
        return response(['data' => new \App\Http\Resources\Image($image)], 200);
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
        $image = Image::find($id);
        if(!$image) {
            return response(['message' => "Image not found"], 404);
        }
        $image->alt = is_null($request->alt) ? "" : $request->alt;
        $image->save();
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
        $image = Image::find($id);
        if(!$image) {
            return response(['message' => "Image not found"], 404);
        }
        if($image->delete()) {
            return response(['message' => "Deleted successfully"], 200);
        }
        return response(['message' => "Delete failed"], 404);
    }
}
