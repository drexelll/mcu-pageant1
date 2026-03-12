<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contestant;

class ContestantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contestant::create
        ([
            'number' => 1,
            'name' => 'Kim Chaewon',
            'course' => 'BSIT',
            'photo' => null,
        ]);

        Contestant::create
        ([
            'number' => 2,
            'name' => 'Kang Haerin',
            'course' => 'BSBA',
            'photo' => null,
        ]);

        Contestant::create
        ([
            'number' => 3,
            'name' => 'Hanni Pham',
            'course' => 'BSTM',
            'photo' => null,
        ]);
    }
}
