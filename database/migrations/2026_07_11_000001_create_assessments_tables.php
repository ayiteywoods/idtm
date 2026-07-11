<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('assignment');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->decimal('max_score', 5, 2)->default(100);
            $table->dateTime('due_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->boolean('allow_submissions')->default(true);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->text('note')->nullable();
            $table->dateTime('submitted_at');
            $table->boolean('is_late')->default(false);
            $table->decimal('score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('faculty_profiles')->nullOnDelete();
            $table->dateTime('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'student_profile_id']);
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('assessment_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assessment_id');
        });

        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('assessments');
    }
};
