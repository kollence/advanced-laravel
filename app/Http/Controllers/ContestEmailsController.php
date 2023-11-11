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
        //NewContestEmailReceivedEvent::dispatch(); or you can use ::dispatch() on NewContestEmailReceivedEvent as new syntax
        event(NewContestEmailReceivedEvent::class);

    }
}
