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
            'user_id' => $req->user_id,
            'content' => $req->content,
            'tag' => $req->tag,
            'user_name' => $req->user_name
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
        $formatComments = array();
        foreach ($comments as $comment) {
            $formatComment = [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user_name,
                'post_id' => $comment->post_id,
                'created_at' => substr($comment->created_at, 11, 5) . ', ' . substr($comment->created_at, 0, 10),
                'updated_at' => $comment->updated_at
            ];
            array_push($formatComments, $formatComment);
        }
        return $formatComments;
    }
}
