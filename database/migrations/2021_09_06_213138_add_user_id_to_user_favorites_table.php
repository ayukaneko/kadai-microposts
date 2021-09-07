<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToUserFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            $table->dropForeign('user_favorites_user_id_foreign');
            $table->dropForeign('user_favorites_micropost_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('micropost_id')->references('id')->on('microposts')->onDelete('cascade');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            //
            $table->dropForeign('user_favorites_user_id_foreign');
            $table->dropForeign('user_favorites_micropost_id_foreign');
            $table->foreign('user_id')->references('id')->on('user_favorites')->onDelete('cascade');
            $table->foreign('micropost_id')->references('id')->on('user_favorites')->onDelete('cascade');
        });
    }
}
