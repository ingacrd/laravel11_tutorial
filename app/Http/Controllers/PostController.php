<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostMailJob;
//use App\Mail\PostMail;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all post from database

        // $name = 'Alfred';
        // $age = 32;
        // $posts = ['post 1', 'post 2', 'post 3'];
        //$posts = Post::all();
//        if(Cache::has('posts')){
//            $posts = Cache::get('posts');
//        } else {
//            sleep(4);
//            $posts = Post::paginate(6);
//            Cache::put('posts', $posts,10);
//        }

        $posts = Cache::remember('posts',10, function () {
            sleep(4);
            return Post::paginate(6);
        });
        // return view('posts.index', ['username' => $name, 'age' => $age, 'posts' => $posts]);
        return view('posts.index', ['posts' => $posts]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // if (!auth()->check()) {
        //     //abort(403);
        //     return to_route('login');
        // }
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //dd($request);
        $validated = $request->validate([
            //'title' => 'required|max:255',
            'title' => ['required', 'max:255', 'min:5'],
            //'content' => 'required',
            'content' => ['required', 'min:10'],
            'thumbnail' => ['required','image'],
        ]);

        // Post::create([
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);
        //$validated['user_id'] = auth()->id();
        $validated['thumbnail'] =$request->file('thumbnail')->store('thumbnails');
        //Post::create($validated);
        auth()->user()->posts()->create($validated);
        dispatch(new SendNewPostMailJob(['email'=>auth()->user()->email, 'name'=>auth()->user()->name, 'title'=> $validated['title']]));
        //Mail::to(auth()->user()->email)->send(new PostMail(['name'=>'Tony', 'title'=>$validated['title']]));
        //return redirect()->route('posts.index');
        return to_route('posts.index')->with('message', "Post created succesfully.");
    }

    /**
     * Display the specified resource.
     */
    //public function show($id)
    public function show(Post $post)
    {
        //try {
            //$post = Post::findOrFail($id);
        return view('posts.show', ['post' => $post]);
        //} catch (ModelNotFoundException $e) {
            //return $e->getMessage();
            //dd($e);
        //}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // if ($post->user_id != auth()->id()) {
        //     abort(403);
        // }
        Gate::authorize('update', $post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);
        $validated = $request->validate([
            'title' => ['required', 'max:255', 'min:5'],
            'content' => ['required', 'min:10'],
            'thumbnail' => ['sometimes','image'],
        ]);

        if($request->hasFile('thumbnail')){
            File::delete(storage_path('app/public/'. $post->thumbnail));
            $validated['thumbnail'] =$request->file('thumbnail')->store('thumbnails');
        }

        $post->update($validated);
        //return to_route('posts.index');
        return to_route('posts.show', ['post'=> $post])->with('message', "Post updated successfully");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        File::delete(storage_path('app/public/'. $post->thumbnail));
        $post->delete();
        return to_route('posts.index');
    }
}
