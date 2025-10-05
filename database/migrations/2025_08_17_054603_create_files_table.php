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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_name', 255);
            $table->text('file_path');
            $table->string('file_type', 50);
            $table->unsignedBigInteger('file_size'); // dalam byte
            $table->timestamp('upload_date')->useCurrent();
            $table->timestamp('expired_date')->nullable();
            $table->unsignedBigInteger('upload_bw')->nullable(); // bandwidth (KB/s, MB/s)
            $table->decimal('upload_duration', 8, 2)->nullable();
            $table->integer('total_views')->default(0);
            $table->integer('total_downloads')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
