<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();

        // Who is inputting this? (The logged-in Staff)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Who is the complaint actually from? (Manual Input)
        $table->string('reporter_name'); // e.g., "Ahmad (Student)"

        // Issue Details
        $table->string('title');
        $table->text('description');
        $table->enum('category', ['Hardware', 'Software', 'Network']);
        $table->enum('priority', ['Low', 'Medium', 'High']);
        $table->enum('status', ['Open', 'Resolved'])->default('Open');

        $table->timestamps();
    });
}
};
