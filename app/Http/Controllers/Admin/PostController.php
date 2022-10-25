<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|min:3',
                'content' => 'required|min:10',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'category_id' => 'nullable|exists:categories,id'
            ]
        );

        $data = $request->all();

        if(isset($data['image'])){
            $cover_path = Storage::put('post_covers', $data['image']);
            $data['cover'] = $cover_path;
        }

        // creo slug
        $slug = Str::slug($data['title']);
        $counter = 1;

        while(Post::where('slug', $slug)->first()) {
            $slug= Str::slug($data['title']) .'-'. $counter;
            $counter++;
        }

        $data['slug'] = $slug;

        $post = new Post();
        $post->fill($data);
        $post->save();

        return redirect()->route('admin.posts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.post.edit', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate(
            [
                'title' => 'required|min:3',
                'content' => 'required|min:10',
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
                'category_id' => 'nullable|exists:categories,id'
            ]
            );
 
            $data = $request->all();

            if(isset($data['image'])){
                
                if($post->cover){
                    Storage::delete($post->cover);
                }
                
                $cover_path = Storage::put('post_covers', $data['image']);
                $data['cover'] = $cover_path;
            };

            $slug= Str::slug($data['title']);
            if($post->slug != $slug) {
                $counter = 1;
                while(Post::where('slug',$slug)->first()) {
                     $slug= Str::slug($data['title']) . '-' . $counter;
                     $counter++;
                }
                $data['slug'] = $slug;
            }
            $post->update($data);
            $post->save();

            if(isset(($data['tags']))){
                $post->tags()->sync($data['tags']);
            }

            return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->cover){
            Storage::delete($post->cover);
        }

        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
