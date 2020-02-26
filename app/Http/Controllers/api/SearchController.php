<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LostResourceCollection;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function search(Request $request){
        //type 1: search in 1 column
        //type 2: search in 2 column
        //type 3: search in 3 column
        //type 4: search in 4 column
        if($request->type == 1){
            return $this->searchOneCol($request);
        }
        if($request->type == 2){
            return $this->searchTwoCol($request);
        }
        if($request->type == 3){
            return $this->searchThreeCol($request);
        }
        if($request->type == 4){
            return $this->searchFourCol($request);
        }
        return response()->json([
            'error' => 'type is required'
        ], 400);
    }

    private function searchOneCol($request){
        if($request->category_id != null){
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->where('category_id', $request->category_id)->get()
            );
        }
        if($request->place != null){
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->where('place', 'LIKE', '%'.$request->place.'%')->get()
            );
        }
        if($request->location != null){
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->where('location', 'LIKE', '%'.$request->location.'%')->get()
            );
        }
        if($request->date1 != null && $request->date2 != null){
            $from = date("Y-m-d", strtotime($request->date1));
            $to = date("Y-m-d", strtotime($request->date2));
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->whereBetween('published_at', [$from, $to])->get()
            );
        }
    }

    private function searchTwoCol($request){

        if($request->category_id != null){
            //search category + date
            if($request->date1 != null && $request->date2 != null){
                $from = date("Y-m-d", strtotime($request->date1));
                $to = date("Y-m-d", strtotime($request->date2));

                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('category_id', $request->category_id)->whereBetween('published_at', [$from, $to])->get()
                );
            }

            //search category + place
            if($request->place != null){
                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('category_id', $request->category_id)->where('place', 'LIKE', '%'.$request->place.'%')->get()
                );  
            }

            //search category + location
            if($request->location != null){
                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('category_id', $request->category_id)->where('location', 'LIKE', '%'.$request->location.'%')->get()
                );  
            }
            
        }

        if($request->location != null){
            //search location + place
            if($request->place != null){
                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('location', 'LIKE', '%'.$request->location.'%')->where('place', 'LIKE', '%'.$request->place.'%')->get()
                );  
            }

            //search location + date
            if($request->date1 != null && $request->date2 != null){
                $from = date("Y-m-d", strtotime($request->date1));
                $to = date("Y-m-d", strtotime($request->date2));

                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('location', 'LIKE', '%'.$request->location.'%')->whereBetween('published_at', [$from, $to])->get()
                );
            }
        }

        if($request->place != null){
            //search place + date
            if($request->date1 != null && $request->date2 != null){

                $from = date("Y-m-d", strtotime($request->date1));
                $to = date("Y-m-d", strtotime($request->date2));

                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])->where('place', 'LIKE', '%'.$request->place.'%')->whereBetween('published_at', [$from, $to])->get()
                );
            }
        }
    }

    private function searchThreeCol($request){
        // dd($request->all());
        //search by date - place - category
        if($request->date1!= null && $request->date2 != null && $request->place != null && $request->category_id !=  null){
            $from = date("Y-m-d", strtotime($request->date1));
            $to = date("Y-m-d", strtotime($request->date2));
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->whereBetween('published_at', [$from, $to])->where('place', 'LIKE', '%'.$request->place.'%')
                        ->where('category_id', $request->category_id)->get()
            );
        }

        //search by date - location - category
        if($request->date1 != null && $request->date2 != null && $request->location != null && $request->category_id != null){
            $from = date("Y-m-d", strtotime($request->date1));
            $to = date("Y-m-d", strtotime($request->date2));
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->whereBetween('published_at', [$from, $to])->where('location', 'LIKE', '%'.$request->location.'%')
                        ->where('category_id', $request->category_id)->get()
            );
        }

        //search by location - category - place
        if($request->location != null && $request->category_id != null && $request->place != null){
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->where('location', 'LIKE', '%'.$request->location.'%')->where('category_id', $request->category_id)
                        ->where('place', 'LIKE', '%'.$request->place.'%')->get()
            );
        }

        //search by location - place - date
        if($request->location != null && $request->place != null && $request->date1 != null && $request->date2 != null){
            $from = date("Y-m-d", strtotime($request->date1));
            $to = date("Y-m-d", strtotime($request->date2));
            return LostResourceCollection::collection(
                Post::with(['images', 'comments'])->whereBetween('published_at', [$from, $to])->where('place', 'LIKE', '%'.$request->place.'%')
                        ->where('location', 'LIKE', '%'.$request->location.'%')->get()
            );
        }
    }

    private function searchFourCol($request){
        //search by all fileds
        if($request->date1 != null && $request->date2 != null && $request->place != null 
            && $request->location != null && $request->category_id != null){
                $from = date("Y-m-d", strtotime($request->date1));
                $to = date("Y-m-d", strtotime($request->date2));
                return LostResourceCollection::collection(
                    Post::with(['images', 'comments'])
                        ->whereBetween('published_at', [$from, $to])
                        ->where('location', 'LIKE', '%'.$request->location.'%')
                        ->where('place', 'LIKE', '%'.$request->place.'%')
                        ->where('category_id', $request->category_id)
                        ->get()
                );  
            }
    }
}
