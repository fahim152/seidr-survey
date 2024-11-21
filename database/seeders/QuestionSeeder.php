<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $filePath = public_path('questions/questions.json'); // Path to your JSON file

        if (!file_exists($filePath)) {
            $this->command->error('Questions file not found!');
            return;
        }

        $data = json_decode(file_get_contents($filePath), true);

        DB::table('questions')->insert([
            'questions' => json_encode($data['questions']),
            'version' => $data['version'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
