<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->string('status')->default('SENT');
            $table->timestamps();

            $table->unique(['source_id', 'target_id']);
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->foreign('source_id')->references('id')->on('users');
            $table->foreign('target_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
};
