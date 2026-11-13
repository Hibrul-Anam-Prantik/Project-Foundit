<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for R1 (Lost Item Reports).
     *
     * Note on merge-conflict & dependency safety:
     * - `user_id` constrains to `users`, which exists in the base skeleton.
     * - `category` and `location` are stored directly so R1 works standalone
     *   before R7 (categories) and R13 (buildings/rooms) are merged.
     * - Nullable indexed `category_id`, `building_id`, and `room_id` columns
     *   are pre-allocated so R7/R13 can link without altering this migration.
     */
    public function up(): void
    {
        Schema::create('lost_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('item_name', 150);
            $table->string('category', 80)->default('other');
            $table->text('description');
            $table->date('lost_date');
            $table->string('location', 255);
            $table->string('photo_path')->nullable();

            $table->enum('status', ['searching', 'matched', 'recovered'])
                ->default('searching');

            // Optional foreign-key placeholders for future R7/R13 integration
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('building_id')->nullable()->index();
            $table->unsignedBigInteger('room_id')->nullable()->index();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('lost_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_reports');
    }
};
