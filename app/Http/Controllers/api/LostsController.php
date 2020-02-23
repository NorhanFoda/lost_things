<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Image;
use App\Http\Resources\LostResource;
use App\Http\Resources\LostResourceCollection;
use App\Http\Requests\LostRequest;
use Illuminate\Support\Facades\Validator;

class LostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LostResourceCollection::collection(Post::with(['images', 'comments'])->where('found', 0)
                                                    ->where('category_id', '!=', null)->get());
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
    public function store(LostRequest $request)
    {
        //Check number of uploaded images min:1 max:3
        if(count($request->images) < 0 || count($request->images) > 3){
            return response()->json([
                'error' => 'You have to upload at least 1 images and at most 3 images'
            ], 400);
        }
        $imageRules = array(
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );

        //Create post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id
        ]);

        foreach($request->images as $image){
            $image_to_validate = array('image' => $image);
            $imageValidator = Validator::make($image_to_validate, $imageRules);
            if ($imageValidator->fails()) {
                return $imageValidator->messages();
            }

            //Make image name unique
            $full_file_name = $image;
            $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $file_name_to_store = $file_name.'_'.time().'.'.$extension;
            
            //Upload image
            $path = $image->move(public_path('/'), $file_name_to_store);
            $url = url('/'.$file_name_to_store);
            Image::create(['path' => $url, 'post_id' => $post->id]);
            // return response()->json(['url' => $url], 200);
        }

        return response()->json([
            'data' => 'Post created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new LostResource(Post::find($id));
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
    }
}
