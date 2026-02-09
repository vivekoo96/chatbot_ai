<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePlanFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update Free Plan - No voice, no images
        DB::table('plans')->where('slug', 'free')->update([
            'allows_voice_messages' => false,
            'allows_image_upload' => false,
        ]);

        // Update Starter Plan - Voice only
        DB::table('plans')->where('slug', 'starter')->update([
            'allows_voice_messages' => true,
            'allows_image_upload' => false,
        ]);

        // Update Pro Plan - Voice and Images
        DB::table('plans')->where('slug', 'pro')->update([
            'allows_voice_messages' => true,
            'allows_image_upload' => true,
        ]);

        // Update Business Plan - Voice and Images
        DB::table('plans')->where('slug', 'business')->update([
            'allows_voice_messages' => true,
            'allows_image_upload' => true,
        ]);

        // Update Enterprise Plan - Voice and Images
        DB::table('plans')->where('slug', 'enterprise')->update([
            'allows_voice_messages' => true,
            'allows_image_upload' => true,
        ]);

        $this->command->info('Plan features updated successfully!');
    }
}
