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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type',10);
            $table->json('metadata');
            $table->foreignUuid('owner_id');
            $table->timestamps();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->foreignUuid('parent_id')
                ->after('metadata')
                ->nullable()
                ->constrained('files')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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
