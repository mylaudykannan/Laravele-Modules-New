<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('design', function (Blueprint $table) {
            $table->id();
            $table->string('title',125);
            $table->string('image',125)->nullable()->default('NULL');
            $table->string('slug',125);
            $table->string('locale',125);
            $table->string('pointslug',125);
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('analytics')->nullable();
            $table->longText('content')->nullable();
            $table->string('category')->default('[]');
            $table->tinyInteger('status')->default('0');
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
        Schema::dropIfExists('design');
    }
}
