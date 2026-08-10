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
        Schema::create('delivery_zone_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_zone_id')->constrained('delivery_zones')->onDelete('cascade');
            $table->foreignId('to_zone_id')->constrained('delivery_zones')->onDelete('cascade');
            $table->enum('package_size', ['small', 'medium', 'large']); // petit, moyen, gros colis
            $table->decimal('price', 10, 2); // prix fixe pour ce trajet + cette taille

            $table->timestamps();

            // Un seul prix par combinaison (départ, arrivée, taille)
            $table->unique(['from_zone_id', 'to_zone_id', 'package_size'], 'unique_zone_price_per_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zone_prices');
    }
};
