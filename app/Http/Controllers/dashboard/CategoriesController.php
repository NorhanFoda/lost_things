<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Image;
use Auth;

class CategoriesController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }

    public function getCategories(){
        return view('admin.categories.index')->with('categories', Post::where('category_id', null)->get(['id', 'title']));
    }

    public function addCategory(){
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request){
        
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //Create category
        $cat = Post::create([
            'title' => $request->title,
            'user_id' => auth()->user()->id
        ]);

        //Make image name unique
        $full_file_name = $request->image;
        $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
        $extension = $request->image->getClientOriginalExtension();
        $file_name_to_store = $file_name.'_'.time().'.'.$extension;
        
        //Upload image
        $path = $request->image->move(public_path('/images/'), $file_name_to_store);
        $url = url('/images/'.$file_name_to_store);
        Image::create(['path' => $url, 'post_id' => $cat->id]);

        session()->flash('message', trans('admin.caregory_created'));
        return redirect()->route('categories.getCategory');
    }

    public function editCategory($id){
        return view('admin.categories.edit')->with('cat', Post::find($id));
    }

    public function updateCategory(Request $request, $id){

        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //Create category
        $cat = Post::find($id)->update([
            'title' => $request->title,
            'user_id' => auth()->user()->id
        ]);

        //Make image name unique
        $full_file_name = $request->image;
        $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
        $extension = $request->image->getClientOriginalExtension();
        $file_name_to_store = $file_name.'_'.time().'.'.$extension;
        
        //Upload image
        $path = $request->image->move(public_path('/images/'), $file_name_to_store);
        $url = url('/images/'.$file_name_to_store);

        $old_image = Image::where('post_id', $id)->first();
        if($old_image != null){
            $old_image->update(['path' => $url, 'post_id' => $id]);
        }
        else{
            Image::create(['path' => $url, 'post_id' => $id]);
        }

        session()->flash('message', trans('admin.category_updated'));
        return redirect()->route('categories.getCategory');
    }

    public function deleteCategory(Request $request){
        if($request->ajax()){
            $posts = Post::where('category_id', $request->id)->get();
            if(count($posts) > 0){
                session()->flash('error', 'category can not be deleted');
                return response()->json([
                    'data' => 0,
                ], 200);
            }
            Post::find($request->id)->delete();
            session()->flash('success', 'category deleted');
            return response()->json([
                'data' => 1,
            ], 200);
        }
    }
}
