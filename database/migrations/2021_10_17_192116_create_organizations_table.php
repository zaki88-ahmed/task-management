<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum("status", ["Active", "InActive"]);
//            $table->text('image');

            $table->bigInteger('manager_id')->unsigned();
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('organizations');
    }
}
