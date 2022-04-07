<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'label' => 'شایق علیمی',
                'sign' => 'SHA',
                'bossname' => 'M.Ehsan Hakimi',
                'logo' => 'cologo.jpg',
                'TIN' => '56754SFSAF54',
                'lisense' => '9984545'
                // 'created_at'        => Carbon::now(),
                // 'updated_at'        => Carbon::now(),
            ],
            [
                'label' => 'ورنا بهفر',
                'sign' => 'WB',
                'bossname' => 'Abul Hanif Rasa',
                'logo' => 'co2logo.jpg',
                'TIN' => '56754SFSA54F54',
                'lisense' => '99845fs45'
                // 'created_at'        => Carbon::now(),
                // 'updated_at'        => Carbon::now(),
            ],
        ];

        // Insert the data to this table
        DB::table('companies')->truncate();
        DB::table('companies')->insert($data);
    }
}
