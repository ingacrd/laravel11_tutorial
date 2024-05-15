<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
        $posts = Post::all();
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
        ]);

        // Post::create([
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);
        Post::create($validated);
        //return redirect()->route('posts.index');
        return to_route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    //public function show($id)
    public function show(Post $post)
    {
        //$post = Post::findOrFail($id);
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255', 'min:5'],
            'content' => ['required', 'min:10'],
        ]);

        $post->update($validated);
        return to_route('posts.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return to_route('posts.index');
    }
}
