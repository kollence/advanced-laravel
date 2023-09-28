<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->foreignId('best_reply_id')->nullable()->constrained('replies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            // Drop the foreign key constraint
            if (DB::getDriverName() !== 'sqlite') {
                Schema::table('threads', function (Blueprint $table) {
                    $table->dropForeign(['best_reply_id']);
                });
            }
            Schema::table('threads', function (Blueprint $table) {
                $table->dropColumn('best_reply_id');
            });
        });
    }
};
