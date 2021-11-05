<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bilgiler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bilgiler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kolonid');
            $table->string('baslik');
            $table->string('slug');
            $table->longText('metin')->nullable();
            $table->int('sirano')->nullable();
            $table->timestamps();
            $table->foreign("kolonid")->references("id")->on('kolonlar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bilgiler');
    }
}
