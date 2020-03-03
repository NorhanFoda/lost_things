<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\DashFoundRequest;
use Carbon\Carbon;
use Auth;

class FoundController extends Controller
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
    public function getfounds()
    {
        return view('admin.founds.index')->with('founds', Post::where('found', 1)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFound()
    {
        return view('admin.founds.create')->with('categories', Post::where('category_id', null)->get(['id', 'title']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFound(DashFoundRequest $request)
    {
        //Create post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'place' => $request->place,
            'category_id' => $request->category_id,
            'found' => 1,
            // 'user_id' => auth()->user()->id,
            'user_id' => 1, //مؤقتا
            'published_at' => date("Y-m-d", strtotime(Carbon::now())),
        ]);
        session()->flash('message', trans('admin.post_created'));
        return redirect()->route('founds.getFounds');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFound($id)
    {
        $post = Post::with('comments', 'category', 'user')->where('id', $id)->first();
        return view('admin.founds.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editFound($id)
    {
        $found = Post::find($id);
        $categories = Post::where('category_id', null)->get(['id', 'title']);
        return view('admin.founds.edit', compact('found', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFound(Request $request, $id)
    {
        Post::find($id)->update($request->all());
        session()->flash('message', trans('admin.post_updated'));
        return redirect()->route('founds.getFounds');
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
}
