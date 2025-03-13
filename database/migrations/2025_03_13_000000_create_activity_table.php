<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tx_activity', function (Blueprint $table) {
            $table->id();
            $table->text('activity_text')->nullable();
            $table->text('activity_image')->nullable();
            $table->text('file_id')->nullable();
            $table->string('activity_time', 60)->nullable();
            $table->integer('status')->default(1)->comment('状态');
            $table->unsignedInteger('is_pin')->nullable();
            $table->text('mp4')->nullable();
            $table->unsignedInteger('type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
}

