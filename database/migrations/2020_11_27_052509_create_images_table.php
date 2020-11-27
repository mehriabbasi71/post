<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->string('name');
            $table->string('alt');
            $table->text('uri');
            $table->string('mime_type',100);
            $table->string('extension',100);
            $table->integer('width');
            $table->integer('height');
            $table->softDeletes('deleted_at', 0);
            $table->foreignId('user_id');
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
        Schema::dropIfExists('images');
    }
}
