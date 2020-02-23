<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\FoundResource;
use App\Http\Resources\FoundResourceCollection;
use App\Http\Requests\FoundRequest;

class FoundsController extends Controller
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
        return FoundResourceCollection::collection(Post::with('comments')->where('found', 1)
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
    public function store(FoundRequest $request)
    {
        Post::create($request->all());

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
        return new FoundResource(Post::find($id));
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
        $this->checkUserAuthorization(Post::find($id)->user_id);
        if(Post::find($id)){
            Post::find($id)->delete();
            return response()->json(null, 204);
        }
        return response()->json([
            'error' => 'Post does not exist'
        ], 400);
    }

    public function  checkUserAuthorization($id){
        if(auth()->user()->id !== $id){
            return response()->json([
                'error' => 'User is UN AUTHORIZED to performe this action'
            ], 400);
        }
    }
}
