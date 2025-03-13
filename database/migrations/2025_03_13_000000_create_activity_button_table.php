<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityButtonTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tx_activity_button', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('activity_id')->nullable();
            $table->string('button_text', 60);
            $table->string('button_link', 200)->nullable();
            $table->string('button_inline', 60)->nullable();
            $table->unsignedInteger('one_line')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_button');
    }
}


