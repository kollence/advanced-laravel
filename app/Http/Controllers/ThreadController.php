<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use App\Redis\TrendingThreads;
use App\Rules\SpamFree;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ThreadController extends Controller
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
    public function index(Channel $channel, ThreadFilters $filters, TrendingThreads $trendingThreads)
    {   // logic moved to method that will be later moved to service
        $threads = $this->getThreads($channel, $filters);
        // FOR TESTING PURPOSES
        if(request()->wantsJson()){
            return $threads;
        }

        $trendingThreads = $trendingThreads->take();
        
        return view('threads.index', compact('threads', 'trendingThreads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = Channel::all();
        return view('threads.create', compact('channels') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'channel_id' => ['required'],
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => ['required','exists:channels,id'],
        ]);

        $thread = Thread::create([
            'user_id' => auth()->user()->id,
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        return redirect('/threads/'.$thread->channel->slug)->with('flash', 'Your thread has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, TrendingThreads $trendingThreads)
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $trendingThreads->put($thread);

        $thread->visits()->record();

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->delete();
        if(request()->expectsJson()){
            return response([], 200);
        }
        return redirect('/profile/'.auth()->user()->name);

    }

    protected function getThreads($channel, $filters)
    {
        // filter model with filters that i got
        $threads = Thread::latest()->filter($filters);
        // if channel model exists in optional parameter then return channels threads
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(10);
    }
}
