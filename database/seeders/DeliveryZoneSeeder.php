<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use App\Models\DeliveryZonePrice;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer les quartiers
        $quartier1 = DeliveryZone::create(['name' => 'Quartier 1']);
        $quartier2 = DeliveryZone::create(['name' => 'Quartier 2']);
        $quartier3 = DeliveryZone::create(['name' => 'Quartier 3']);

        // 2. Définir la grille tarifaire (trajet + taille -> prix)
        // Exemple : Quartier 1 -> Quartier 2
        DeliveryZonePrice::create([
            'from_zone_id' => $quartier1->id,
            'to_zone_id' => $quartier2->id,
            'package_size' => 'small',
            'price' => 1000,
        ]);
        DeliveryZonePrice::create([
            'from_zone_id' => $quartier1->id,
            'to_zone_id' => $quartier2->id,
            'package_size' => 'large',
            'price' => 2000,
        ]);

        // Exemple : Quartier 1 -> Quartier 3 (un autre trajet, prix différents)
        DeliveryZonePrice::create([
            'from_zone_id' => $quartier1->id,
            'to_zone_id' => $quartier3->id,
            'package_size' => 'small',
            'price' => 2000,
        ]);
        DeliveryZonePrice::create([
            'from_zone_id' => $quartier1->id,
            'to_zone_id' => $quartier3->id,
            'package_size' => 'large',
            'price' => 4000,
        ]);

        // Ne pas oublier le sens inverse si le prix retour diffère
        // (Quartier 2 -> Quartier 1, Quartier 3 -> Quartier 1, etc.)
    }
}
