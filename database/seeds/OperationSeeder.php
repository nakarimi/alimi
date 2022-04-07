<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operations')->truncate();
        $data = [
            [
                'title'=>'ثقلت',
                'formula'=>'ثقلت',
                'description'=>'این نوع برای ثقلت میباشد',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
            [
                'title'=>'عملیه ۱',
                'formula'=>'عملیه ۱',
                'description' => 'این عملیه بر سیستم تاثیر میگذارد',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
            [
                'title'=>'عملیه ۱۱',
                'formula'=>'عملیه ۱۱',
                'description' => 'این عملیه بر سیستم تاثیر میگذارد',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
            [
                'title'=>'عملیه ۱۲',
                'formula'=>'عملیه ۱۲',
                'description' => 'این عملیه بر سیستم تاثیر میگذارد',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
            [
                'title'=>'عملیه ۴۱',
                'formula'=>'عملیه ۴۱',
                'description' => 'این عملیه بر سیستم تاثیر میگذارد',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),

            ],
        ];

        // Insert the data to this table
        DB::table('operations')->insert($data);
    }
}
