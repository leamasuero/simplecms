<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('simplecms_publicaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo')->unique();
            $table->string('slug')->unique();
            $table->text('extracto');
            $table->text('cuerpo');
            $table->boolean('publicada');
            $table->boolean('protegida');
            $table->boolean('destacada');
            $table->boolean('privada');
            $table->boolean('notificable');
            $table->timestamp('fecha_publicacion');
            $table->timestamp('notificada_at')->nullable();
            $table->unsignedBigInteger('categoria_id')->references('id')->on('simplecms_categorias');
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
        Schema::dropIfExists('simplecms_publicaciones');
    }
}
