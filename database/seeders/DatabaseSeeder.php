<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //Permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // CREATE ADMIN
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'confirmed_email' => true,
        ]);
        $role = Role::create(['name' => 'Admin']);
       
        $permissions = Permission::pluck('id','id')->all();
     
        $role->syncPermissions($permissions);
       
        $user->assignRole([$role->id]);

        // CREATE USERS
        \App\Models\User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@example.com',
            'confirmed_email' => true,
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'confirmed_email' => false,
        ]);
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
