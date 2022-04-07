<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class ClientsSeeder extends Seeder
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
                'name'=>'Kabul UNI',
                'email'=>'fixed@gmail.com',
                'phone'=>'+9854329235',
                'website'=>'active.af',
                'address' => 'Kabul-Afghanistan',
                'logo' => 'asfddsf',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
            [
                'name'=>'Kateb Uni',
                'email'=>'percent@gmail.com',
                'phone'=>'+9854329857',
                'logo' => 'asfddsf',
                'website'=>'kabul.af',
                'address' => 'Kabul-Afghanistan',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
        ];

        // Insert the data to this table
        DB::table('clients')->insert($data);
    }
}
