<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLangTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group');
            $table->string('namespace')->nullable();
            $table->string('key');
            $table->timestamps();

            $table->index(['group', 'namespace']);
        });
        Schema::create('lang_translations', function (Blueprint $table) {
            $table->translates('lang');
            $table->text('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lang_translations');
        Schema::dropIfExists('lang');
    }
}
