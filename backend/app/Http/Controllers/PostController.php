<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;


class PostController extends Controller
{
    public function postUpload(Request $req)
    { //Create
        Post::create([
            'user_id' => $req->userID,
            'content' => $req->content,
            'tag' => $req->tag,
            'user_name' => $req->userName
        ]);
        return response('Posted Successfully');
    }

    public function getPosts()
    { //Read
        $posts = Post::all();
        return $posts;
    }

    public function deletePost(Request $req)
    { //Delete
        $post = Post::find($req->id);
        $post->delete();
        return ('Post Removed');
    }

    public function postComment(Request $req)
    {
        $post = Post::find($req->input('postId'));
        $comment = new Comment([
            'content' => $req->input('content'),
            'user_id' => $req->input('userId'),
            'post_id' => $req->input('postId'),
            'user_name' => $req->input('userName')
        ]);
        if (!$post) {
            return ('Post does not exist.');
        }
        $post->comments()->save($comment);
        return ('Successfully commented.');
    }

    public function getComments(Request $req)
    {
        $postId = $req->input('postId');
        $comments = Post::find($postId)->comments;
        return $comments;
    }
}
