<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Image;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Requests\CategoryRequest;

class CategoriesController extends Controller
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
        return CategoryResourceCollection::collection(Post::with('images')->where('category_id', null)->get());
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
    public function store(CategoryRequest $request)
    {
        $post = Post::create(['title' => $request->title]);

        //Make image name unique
        $full_file_name = $request->image;
        $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
        $extension = $request->image->getClientOriginalExtension();
        $file_name_to_store = $file_name.'_'.time().'.'.$extension;
        
        //Upload image
        $path = $request->image->move(public_path('/'), $file_name_to_store);
        $url = url('/'.$file_name_to_store);
        Image::create(['path' => $url, 'post_id' => $post->id]);

        return response()->json([
            'data' => 'category created'
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
        //
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
