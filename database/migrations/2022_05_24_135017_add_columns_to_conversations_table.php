<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->boolean('notification');
            $table->boolean('lock_screen');
            $table->boolean('alert_tone');
            $table->boolean('vibrate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('notification');
            $table->dropColumn('lock_screen');
            $table->dropColumn('alert_tone');
            $table->dropColumn('vibrate');
        });
    }
}
