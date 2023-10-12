<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Health', 'Work', 'Food', 'Sport', 'Products', 'Traveling', 'Night Life'];
        foreach($categories as $category){
            \App\Models\Channel::factory()->create(['name'=>$category, 'slug'=> Str::slug($category)]);
        }
        $ids = fn () => \App\Models\Channel::all()->pluck('id')->random();
        \App\Models\Thread::factory(3)->create(['title'=>'Unique Title', 'channel_id' => $ids]);
        \App\Models\Thread::factory(30)->create(['channel_id' => $ids]);
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
