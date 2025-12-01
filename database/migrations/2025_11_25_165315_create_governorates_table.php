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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->enum("name", [
                "Damascus",
                "Damascus countryside",
                "Aleppo",
                "Homs",
                "Latakia",
                "Tartus",
                "Hama",
                "Deir ez-Zor",
                "Raqqa",
                "Hasakah",
                "Daraa",
                "Quneitra",
                "Idlib",
            ])->default("Damascus");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorates');
    }
};
