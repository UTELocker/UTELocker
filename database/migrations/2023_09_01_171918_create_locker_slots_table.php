<?php

use App\Enums\LockerSlotCoordinate;
use App\Enums\LockerSlotStatus;
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
        Schema::create('locker_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locker_id')->constrained('lockers')->cascadeOnDelete();
            $table->tinyInteger('status')->default(LockerSlotStatus::AVAILABLE);
            $table->integer('x');
            $table->integer('y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_slots');
    }
};
