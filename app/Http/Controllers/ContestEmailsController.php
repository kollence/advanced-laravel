<?php

namespace App\Http\Controllers;

use App\Events\NewContestEmailReceivedEvent;
use App\Models\ContestEmails;
use Illuminate\Http\Request;

class ContestEmailsController extends Controller
{
    public function index(){
        return view('contest.index');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
                'email' => 'required|email',
        ]);

        ContestEmails::create($data);
        //  or you can use ::dispatch() on NewContestEmailReceivedEvent as new syntax
        // when ::dispatch() is used on event class, you will be triggering LISTENERS::Class handler() that can be visible in TEST CASE  ~(you don't need to pass any argument)
        NewContestEmailReceivedEvent::dispatch();
        // event(NewContestEmailReceivedEvent::class);

    }
}
