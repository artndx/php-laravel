<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;

use App\Models\Article;
use App\Jobs\VeryLongJob;

use App\Notifications\CommentNotify;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Cache::rememberForever('comments', function(){
            return DB::table('comments')
                    ->join('users','users.id','=','comments.user_id')
                    ->join('articles','articles.id','=','comments.article_id')
                    ->select('comments.id','comments.title','comments.text', 'comments.accept',
                    'users.name as user_name','articles.name as article_name', 
                    )
                    ->get();
        });

        return view('comment.index', ['comments'=>$comments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Cache::forget('comments');
        Cache::forget('comments_'.request('article_id'));
        $request->validate([
            'title'=>'required',
            'text'=>'required'
        ]);

        $article = Article::findOrFail(request('article_id'));
        $comment = new Comment;
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->user_id = Auth::id();
        $comment->article_id = request('article_id');
        $moderator_id = User::where('role','moderator')->first()->id;
        if($comment->user_id == $moderator_id){
            $comment->accept = true;
        } else {
            $comment->accept = false;
        }
        $res = $comment->save();
        if($res) {
            VeryLongJob::dispatch($article);
        }
        return redirect()->route('article.show', ['article'=>request('article_id')])->with(['res'=>$res]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return view('comment.edit', ['comment'=>$comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Кэш
        Cache::forget('comments_'.$comment->article_id);

        $article_id = $comment->article_id;
        $request->validate([
            'title'=>'required|min:6',
            'text'=>'required'
        ]);
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->save();
        return redirect()->route('article.show', ['article'=>$article_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $article_id = $comment->article_id;
        Cache::forget('comments_'.$article_id);
        
        $comment->delete();
        return redirect()->route('article.show', ['article'=>$article_id]);
    }

    public function accept(Comment $comment)
    {   
        // Кэш
        Cache::forget('comments');
        Cache::forget('comments_'.$comment->article_id);
        
        $comment->accept = true;
        $res = $comment->save();
        if($res){
            $users = User::where('id', '!=', $comment->user_id)->get();
            Notification::send($users, new CommentNotify($comment->title, $comment->article_id));
        }
        return redirect()->route('new_comments');
    }

    public function reject(Comment $comment)
    {
        // Кэш
        Cache::forget('comments');
        Cache::forget('comments_'.$comment->article_id);

        $comment->accept = false;
        $comment->save();
        
        return redirect()->route('new_comments');
    }
}
