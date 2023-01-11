<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTableUserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->tinyInteger('lock_screen')->default(0);
            $table->tinyInteger('alert_tone')->default(0);
            $table->tinyInteger('vibrate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            //
            $table->dropColumn('lock_screen');
            $table->dropColumn('alert_tone');
            $table->dropColumn('vibrate');
        });
    }
}
