<?php

use App\Section;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section = Section::create
        ([
            'section_name' => 'Section',
            'description'  => 'Section Description',
            'created_by'   => User::first()->name,
        ]);
    }
}
