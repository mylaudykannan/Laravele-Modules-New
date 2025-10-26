<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('slug');
            $table->string('locale');
            $table->string('pointslug');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('analytics')->nullable();
            $table->longText('content')->nullable();
            $table->string('category')->default('[]');
            $table->tinyInteger('status')->default(0);            
            $table->timestamp('publish_on')->useCurrent();
            $table->integer('created_by');
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
        Schema::dropIfExists('page');
    }
}
