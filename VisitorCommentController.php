<?php

namespace App\Http\Controllers\Web;

use App\Events\PostHasNewComment;
use App\Http\Controllers\Controller;
use App\Models\PostComments;
use App\Models\PostCommentsReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorCommentController extends Controller
{


    public function index(Request  $request){
        $comments=PostComments::query()->with(['user','replies'=>function($q){
                return $q->orderByDesc('created_at');
        },'replies.user','replies.replies'=>function($q){
            return $q->orderByDesc('created_at');
        },'replies.replies.user','likes',"replies.likes","replies.replies.likes"])->withCount(['likes'])->where(['post_slug'=>$request->post_slug,'parent_id'=>null])->orderByDesc('created_at')->get();
        return response()->json(['comments'=>$comments]);
    }




    public function saveComment(Request $request){

        $comment=new PostComments();
        $comment->user_id=$request->user_id;
        $comment->post_slug=$request->post_slug;
        $comment->post_id=$request->post_id;
        $comment->comment=$request->comment;
        $comment->save();
        $comment['user']=User::find($request->user_id);
        event(new PostHasNewComment($comment));
        $comment["replies"]=[];
        $comment["likes"]=[];
        return response()->json(['comment'=>$comment,'success'=>"Comment submitted successfully"]);
    }

    public function delComment(Request $request){
        $comment =PostComments::find($request->comment_id);
        if($comment->replies()){
            $comment->replies()->delete();
        }
        $comment->delete();
        return response()->json(['comment'=>$comment,'success'=>"Comment deleted successfully"]);

    }
    public function saveCommentReply(Request $request){

        $comment=new PostComments();
        $comment->user_id=$request->user_id;
        $comment->post_slug=$request->post_slug;
        $comment->post_id=$request->post_id;
        $comment->parent_id=$request->comment_id;
        $comment->comment=$request->comment;
        $comment->save();
        $comment['user']=User::find($request->user_id);
        event(new PostHasNewComment($comment));
        $comment["replies"]=[];
        $comment["likes"]=[];
        return response()->json(['comment'=>$comment,'success'=>"Comment submitted successfully"]);
    }
}
