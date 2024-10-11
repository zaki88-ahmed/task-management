<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('destination')->unsigned();
            $table->foreign('destination')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->text('text');
            $table->boolean('seen')->default(0);
            $table->string('url')->nullable();
            $table->string('type');                          //notification or friend request
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
