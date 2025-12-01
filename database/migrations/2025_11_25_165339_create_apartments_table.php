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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner_id")->constrained("users");
            $table->foreignId("city_id")->constrained("cities");
            $table->foreignId("governorate_id")->constrained("governorates");
            $table->string("title");
            $table->float("price");
            $table->integer("rooms")->default(1);
            $table->integer("floor_number");
            $table->integer("area");
            $table->enum("apartment_type", ['one_room','multipul_rooms'] )->default('one_room');
            $table->text("description");
            $table->boolean("is_internet_available")->default(false);
            $table->boolean("is_air_conditioned")->default(false);
            $table->boolean("is_cleaning_available")->default(false);
            $table->boolean("is_electricity_available")->default(false);
            $table->boolean("is_furnished")->default(false);
            $table->enum("status", ['pending', 'approved', 'rejected'])->default('pending');
            $table->text("reject_reason")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
