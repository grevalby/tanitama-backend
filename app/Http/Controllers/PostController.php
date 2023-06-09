<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DetailPostResource;
use App\Http\Resources\UpdatedPostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        //$posts = Post::all();
        $posts = Post::with(['author:id,name,photo','comments'])->latest()->get();
        //return response()->json(['data' => $posts]);
        return PostResource::collection($posts);
    }

    public function myposts()
    {
        $user = Auth::user()->id;
        $myposts = Post::where('user_id','=',$user)->latest()->get();
        return PostResource::collection($myposts->loadMissing(['author:id,name','comments']));
    }

    public function show($id)
    {
        $post = Post::with('author:id,name,photo')->findOrFail($id);
        return new DetailPostResource($post->loadMissing('comments:id,post_id,user_id,content,created_at'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'file' => 'image'
        ]);

        //upload file
        $newName = null;
        if ($request->file) {
            $randomName = Str::random(20);
            $extension = $request->file->extension();
            $newName = $randomName.'.'.$extension;

            Storage::putFileAs('image', $request->file, $newName);
            $newName = 'https://storage.googleapis.com/tanitama_bucket/image/'.$newName;
        }

        $request['image_url'] = $newName;
        $request['user_id'] = Auth::user()->id;
        $post = Post::create($request->all());
        return new DetailPostResource($post->loadMissing('author:id,name'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'file' => 'image'
        ]); 

        
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new UpdatedPostResource($post->loadMissing('author:id,name'));
    }


    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        $post->delete();
        return response()->json(['message' => 'Post Deleted'], 204);

    }
 
}
