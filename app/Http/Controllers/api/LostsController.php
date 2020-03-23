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
use App\Http\Resources\UsersWithTokens;
use Carbon\Carbon;
use App\User;

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
        return response()->json([
            'data' => LostResourceCollection::collection(Post::with(['images', 'comments'])->where('found', 0)
                                                    ->where('category_id', '!=', null)->get())
        ]);
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
            'user_id' => $request->user_id,
            'published_at' => date("Y-m-d", strtotime(Carbon::now())),
        ]);

        foreach($request->images as $image){
            //Validate image
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
        
        $users = User::where('location', $post->location)->get();
        $users_with_tokens = array();
        if($users){
            foreach($users as $user){
                $users_with_tokens[] = new UsersWithTokens($user);
            }
        }

        return response()->json([
            'users' => $users_with_tokens,
            'post_id' => $post->id,
            'images' => $post->images
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
        return response()->json([
            'data' => new LostResource(Post::find($id))
        ]);
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
        $post = Post::find($id);
        if($post){
            $images = $post->images;
            foreach($images as $image){
                $file_name = pathinfo($image, PATHINFO_FILENAME);
                $extension = substr($image->path,strrpos($image->path,'.'));
                $full_name = $file_name.$extension;
                $file_path = 'images/'.$full_name;
                if(\File::exists($file_path)){
                    \File::delete($file_path);
                }
                
                $image->delete();
            }
            $post->delete();
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
