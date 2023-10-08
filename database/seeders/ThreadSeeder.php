<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Thread::factory(3)->create(['title'=>'Unique Title']);
        \App\Models\Thread::factory(50)->create();
        $threads = \App\Models\Thread::all();
        $threads->each(function ($thread, $key) {
            if($key % 2 == 0){
                $numb = 2;
            }else{
                $numb = 3;
            }
            \App\Models\Reply::factory($numb)->create(['thread_id' => $thread->id]);
        });
    }
}
