<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index() {
        Gate::authorize("post");

        $posts = Post::all()->sortByDesc("created_at");
        return view("posts.index", [
            'posts' => $posts,
        ]);
    }

    public function create() {
        Gate::authorize("post");

        return view("posts.create");
    }

    public function store(Request $request) {
        Gate::authorize("post");


        $validated = $request->validate([
            'text_de' => "required",
            'text_en' => "required",
            'image' => File::image()
        ]);

        if($request->file("image")) {
            $storedImage = $request->file("image")->store("public");
            Image::make(Storage::path("") . $storedImage)
            ->resize(1920, 1200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();
            $validated['image'] = $storedImage;
        }

        $post = auth()->user()->posts()->create($validated);

        foreach(PostChannel::all() AS $channel) {
            $channel->sendMessage($post);
        }

        return back()->withSuccess("Posted!");
    }

    public function destroy(Post $post) {
        Gate::authorize("post");

        $post->delete();
        return back()->withSuccess("Deleted!");
    }
}
