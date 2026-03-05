<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_delete_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Stores name as text
            $table->string('user_email'); // Stores email as text
            $table->foreignId('admin_id')->constrained('users'); // Who did it
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }
};
