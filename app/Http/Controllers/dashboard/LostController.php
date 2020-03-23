<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Auth;
use App\Http\Requests\DashLostRequest;
use App\Http\Requests\DashEditLostRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;

class LostController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLosts()
    {
        return view('admin.losts.index')->with('losts', Post::with('images', 'user')->where('found', 0)->where('category_id', '!=', null)->paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createLost()
    {
        return view('admin.losts.create')->with('categories', Post::where('category_id', null)->get(['id', 'title']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLost(DashLostRequest $request)
    {
        //Check number of uploaded images min:1 max:3
        if(count($request->images) < 0 || count($request->images) > 3){
            session()->flash('error', 'من فضلك ادخل صوره واحده على الاقل و ثلاثه على الاكثر');
            return redirect()->route('losts.getLosts');
        }
        $imageRules = array(
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );

        //Create post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'place' => $request->place,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
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
            $path = $image->move(public_path('/images/'), $file_name_to_store);
            $url = url('/images/'.$file_name_to_store);
            Image::create(['path' => $url, 'post_id' => $post->id]);
        }

        session()->flash('message', trans('admin.post_created'));
        return redirect()->route('losts.getLosts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLost($id)
    {
        $post = Post::with('images', 'comments', 'category', 'user')->where('id', $id)->first();
        return view('admin.losts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editLost($id)
    {
        $lost = Post::find($id);
        $categories = Post::where('category_id', null)->get(['id', 'title']);
        return view('admin.losts.edit', compact('lost', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLost(DashEditLostRequest $request, $id)
    {
        //update post
        $post = Post::find($id);
        $post->update($request->all());

        //handle image update
        if($request->images != null){
            //if the post already has 3 images, then return 
            $post_images = Image::where('post_id', $id)->get();
            if(count($post_images) == 3){
                session()->flash('message', trans('admin.max_images'));
                return redirect()->route('losts.getLosts');
            }
            //if the post has less than 3 images then upload the rest up to 3 images
            else if(count($post_images) < 3){
                $imageRules = array(
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                );

                $free_space = 3 - count($post_images);

                if(count($request->images) >= $free_space){
                    for($i = 0; $i < $free_space; $i++){

                        //Validate image
                        $image_to_validate = array('image' => $request->images[$i]);
                        $imageValidator = Validator::make($image_to_validate, $imageRules);
                        if ($imageValidator->fails()) {
                            return $imageValidator->messages();
                        }
                        
                        //Make image name unique
                        $full_file_name = $request->images[$i];
                        $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
                        $extension = $request->images[$i]->getClientOriginalExtension();
                        $file_name_to_store = $file_name.'_'.time().'.'.$extension;

                        //Upload image
                        $path = $request->images[$i]->move(public_path('/images/'), $file_name_to_store);
                        $url = url('/images/'.$file_name_to_store);
                        Image::create(['path' => $url, 'post_id' => $post->id]);
                    }
                }
                else{
                    if(count($request->images) < $free_space){
                        for($i = 0; $i < count($request->images); $i++){
                            
                            //Validate image
                            $image_to_validate = array('image' => $request->images[$i]);
                            $imageValidator = Validator::make($image_to_validate, $imageRules);
                            if ($imageValidator->fails()) {
                                return $imageValidator->messages();
                            }
                            
                            //Make image name unique
                            $full_file_name = $request->images[$i];
                            $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
                            $extension = $request->images[$i]->getClientOriginalExtension();
                            $file_name_to_store = $file_name.'_'.time().'.'.$extension;

                            //Upload image
                            $path = $request->images[$i]->move(public_path('/images/'), $file_name_to_store);
                            $url = url('/images/'.$file_name_to_store);
                            Image::create(['path' => $url, 'post_id' => $post->id]);
                        }
                    }
                }
            }
        }

        session()->flash('message', trans('admin.post_updated'));
        return redirect()->route('losts.getLosts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function deletePost(Request $request){
        if($request->ajax()){
            $post = Post::find($request->id);
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
                session()->flash('success', 'post deleted');
                return response()->json([
                    'data' => 1,
                ], 200);   
            }
        }
        else{
            return response()->json([
                'data' => 0,
            ], 400);
        }
    }
}
