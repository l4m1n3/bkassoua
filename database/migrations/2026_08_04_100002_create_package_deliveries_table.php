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
        Schema::create('package_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Client qui commande la livraison

            // Récupération du colis
            $table->foreignId('pickup_zone_id')->constrained('delivery_zones'); // Quartier de départ
            $table->string('pickup_address'); // Précision de l'adresse dans le quartier
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();

            // Livraison du colis
            $table->foreignId('dropoff_zone_id')->constrained('delivery_zones'); // Quartier d'arrivée
            $table->string('dropoff_address'); // Précision de l'adresse dans le quartier
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();

            // Détails du colis
            $table->string('package_description')->nullable();
            $table->enum('package_size', ['small', 'medium', 'large']); // détermine le prix avec la zone

            // Coût déterminé automatiquement via delivery_zone_prices
            $table->decimal('delivery_fee', 10, 2);

            $table->enum('status', ['pending', 'accepted', 'picked_up', 'in_transit', 'delivered', 'cancelled'])
                ->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // card, mobile_money, cash, etc.

            $table->timestamp('scheduled_at')->nullable(); // Heure souhaitée de récupération
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_deliveries');
    }
};
