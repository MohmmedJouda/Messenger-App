<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AIUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aiUser = \App\Models\User::firstOrCreate(
            ['email' => 'ai@messenger.com'],
            [
                'name' => 'Messenger AI',
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                'is_ai' => true,
            ]
        );

        $aiUser->profile()->updateOrCreate(
            ['user_id' => $aiUser->id],
            [
                'photo' => 'img/ai_avatar.png', // We copied it to public/img/ai_avatar.png
                'bio' => 'I am your friendly Messenger AI assistant.',
            ]
        );
    }
}
