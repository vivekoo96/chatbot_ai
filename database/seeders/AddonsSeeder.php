<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonsSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            // Chat Add-ons
            [
                'name' => '+5,000 Messages',
                'slug' => 'messages_5000',
                'type' => 'messages',
                'quantity' => 5000,
                'price_inr' => 499,
                'price_usd' => 6.99,
                'description' => 'Add 5,000 extra messages to your account',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '+10,000 Messages',
                'slug' => 'messages_10000',
                'type' => 'messages',
                'quantity' => 10000,
                'price_inr' => 899,
                'price_usd' => 11.99,
                'description' => 'Add 10,000 extra messages to your account',
                'is_active' => true,
                'sort_order' => 2,
            ],

            // Voice Add-ons
            [
                'name' => '+20 Voice Minutes',
                'slug' => 'voice_20',
                'type' => 'voice',
                'quantity' => 20,
                'price_inr' => 299,
                'price_usd' => 3.99,
                'description' => 'Add 20 minutes of voice interaction',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '+60 Voice Minutes',
                'slug' => 'voice_60',
                'type' => 'voice',
                'quantity' => 60,
                'price_inr' => 799,
                'price_usd' => 10.99,
                'description' => 'Add 60 minutes of voice interaction',
                'is_active' => true,
                'sort_order' => 4,
            ],

            // Image Add-ons
            [
                'name' => '+200 Images',
                'slug' => 'images_200',
                'type' => 'images',
                'quantity' => 200,
                'price_inr' => 199,
                'price_usd' => 2.99,
                'description' => 'Add 200 image uploads to your account',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => '+500 Images',
                'slug' => 'images_500',
                'type' => 'images',
                'quantity' => 500,
                'price_inr' => 399,
                'price_usd' => 5.99,
                'description' => 'Add 500 image uploads to your account',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($addons as $addon) {
            Addon::updateOrCreate(
                ['slug' => $addon['slug']],
                $addon
            );
        }

        $this->command->info('✅ Created 6 add-ons: 2 chat, 2 voice, 2 image packs!');
    }
}
