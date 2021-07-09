<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTinyUrlToTinyUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiny_urls', function (Blueprint $table) {
            $table->string('tinyUrl')->nullable();
            $table->string('longUrl')->nullable();
            $table->date('expiryDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiny_urls', function (Blueprint $table) {
            $table->dropColumn(['tinyUrl', 'longUrl', 'expiryDate']);
        });
    }
}
