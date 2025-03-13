<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tx_users', function (Blueprint $table) {
            $table->id();
            $table->string('lastname', 255)->nullable()->comment('姓氏');
            $table->string('name', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('tete_id', 60)->nullable();
            $table->integer('status')->nullable();
            $table->string('tele_language_code', 20)->nullable();
            $table->integer('push_order')->nullable();
            $table->integer('lastlog')->nullable();
            $table->integer('addtime')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->index('tete_id', 'tete_id_idx');
            $table->index('username', 'username_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}

