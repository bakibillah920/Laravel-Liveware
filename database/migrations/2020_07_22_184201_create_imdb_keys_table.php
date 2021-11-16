<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImdbKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imdb_keys', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('upload_id');
            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade');

            $table->text('key');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imdb_keys');
    }
}
