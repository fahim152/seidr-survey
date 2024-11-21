<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            // Adding the question_id column
            $table->unsignedBigInteger('question_id')->nullable()->after('answers');

            // Adding a foreign key constraint to the questions table
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            // Dropping the foreign key constraint and column
            $table->dropForeign(['question_id']);
            $table->dropColumn('question_id');
        });
    }
};
