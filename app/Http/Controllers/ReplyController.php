<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Channel $channel, Thread $thread)
    {
        try{
            $this->authorize('create', new Reply);
            request()->validate(['body' => ['required', new SpamFree]]);

            $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);

        }catch(ValidationException $e){
            
            return redirect($thread->path())->withErrors($e->validator->getMessageBag());
        }

        return redirect($thread->path())->with('flash', 'Your reply has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);
        try
        {   
            request()->validate(['body' => ['required', new SpamFree]]);

            $reply->update($request->only('body'));
        }catch(\Exception $e)
        {
            if(request()->expectsJson()){
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
            return redirect()->back()->with('flash', $e->getMessage());
        }
       
        if(request()->expectsJson()){
            return response()->json(['success' => true, 'reply' => $reply]);
        }
        return redirect()->back()->with('flash', 'Your reply has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);
        $reply->delete();
        
        if(request()->expectsJson()){
            return response()->json(['success' => true], 204);
        }
        return redirect()->back()->with('flash', 'Your reply has been deleted!');
    }

}
