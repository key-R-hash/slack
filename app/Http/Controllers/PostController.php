<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\token;
use App\post;
use App\User;

class PostController extends Controller
{
    public function create(Request $request){
        $token = $request->header("Authorization");
        $token_sha = hash('sha1',$token);
        $token = token::where('token',$token_sha)->first();
        $user_id = $token->user_id;
        $topic_name = request('topic_name');
        $topic = request('topic');
        $body = request('body');
        $loggedat = request('logged_at');
        $hastag = request('has_tag');
        $tags = request('tags');
        $hasfile = request('has_file');
        $files_name = request('files_name');
        $files_url = request('files_url');

        post::create(['user_id' => $user_id,
            'topic_name' => $topic_name,
            'topic' =>  $topic,
            'body' =>  $body,
            'logged_at' =>  $loggedat,
            'has_tag' =>  $hastag,
            'tags' =>  $tags,
            'has_file' =>  $hasfile,
            'files_name' =>  $files_name,
            'files_url' =>  $files_url,
            'revoke' =>  0,
            'seen' => 0,
            'has_unread' => 0
             ]);
        return response([
            'message' => 'your post already created'
        ],200);
    }
    public function posts($token){
        $token = hash('sha1', $token);
        $tokenData = token::where('token', $token)->where('revoke', 0)->first();
        $i = 0;
        $b = 0;
        $c = 0;
        $id  = $tokenData->user_id;
        $topic_name = post::select('topic_name')->where([['user_id','=',$id],['revoke','=','0']])->distinct('topic_name')->get();
        $count = count($topic_name);
        if($tokenData && $count != 0){
            auth()->loginUsingId($tokenData->user_id);

            while($i != $count){
                $post[$i] = post::where([['user_id','=',$id],['revoke','=','0'],['topic_name','=',$topic_name[$i]->topic_name]])->get();
                $i++;
            }
            while($c != $count){
                $post_seen[$c] = post::where([['user_id','=',$id],['revoke','=','0'],['topic_name','=',$topic_name[$c]->topic_name],['seen','=','0']])->get();
                $c++;
            }
            $post_count = count($post);
            while($b != $count){
                post::where('has_unread',0)->where('topic_name',$topic_name[$b]->topic_name)->update(['has_unread' => 1]);
                $c = count($post[$b]);
                $c = $c - 1;
                $topics[$b] =  ([
                    'id' => $topic_name[$b]->topic_name,
                    'name' => $topic_name[$b]->topic_name,
                    'latest_message' => $post[$b][$c]->body,
                    'has_new' => 'true',
                    'new_count' => count($post_seen[$b]),
                    'has_unread' => $post[$b][$c]->has_unread,
                    "logged_at" => $post[$b][$c]->logged_at
                ]);
                $b++;
            }


            $array = ([
                'topics' => $topics
            ]);
            return response([
                'data' => $array
            ],200);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }
    public function post($token,$topic_post){
        $b = 0;
        $token = hash('sha1', $token);
        $tokenData = token::where('token', $token)->where('revoke', 0)->first();
        if($tokenData){
            auth()->loginUsingId($tokenData->user_id);
            $id  = $tokenData->user_id;

            $post = post::where([['user_id','=',$id],['revoke','=','0'],['topic_name','=',$topic_post]])->get();

            $count = count($post);
            while($b != $count){

                $file[$b] = ([
                    'name' => $post[$b]->files_name,
                    'url' => $post[$b]->files_url
                ]);
                $topics[$b] =  ([
                    'title' => $post[$b]->topic,
                    'body' => $post[$b]->body,
                    'logged_at' => $post[$b]->logged_at,
                    'has_tag' => $post[$b]->has_tag,
                    'tags' => $post[$b]->tags,
                    'has_file' => $post[$b]->has_file,
                    "file" => $file
                ]);
                $b++;
            }

            post::where('seen',0)->where('topic_name',$topic_post)->update(['seen' => 1]);

            $array = ([
                'topic_name' => $topic_post,
                'content' => $topics
            ]);
            return response([
                'data' => $array
            ],200);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    // public function delete(Request $request){
    //     $token = $request->header("Authorization");
    //     $token_sha = hash('sha1',$token);
    //     $token = token::where('token',$token_sha)->first();
    //     $user_id = $token->user_id;
    //     $check = post::where('user_id',$user_id)->where('id' ,$id)->get();
    //     if($check->all() == null){
    //         return response([
    //             'message' => 'you dont have access to this post'
    //         ],403);
    //     }else{
    //         post::where('user_id',$user_id)->where('id' ,$id)->update(['revoke' => 1]);
    //         return response([
    //             'message' => 'your post deleted'
    //         ],200);
    //     }
    // }
}
