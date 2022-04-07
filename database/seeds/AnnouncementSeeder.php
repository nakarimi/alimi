<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get Organization IDs.
        $orgIDs = DB::table('organizations')->pluck('id');

        // Schema::disableForeignKeyConstraints();

        $faker = \Faker\Factory::create();
        $data = [
            [
                's_number' => '1230',
                'issue_date' => Carbon::now(),
                'issue_address' => 'acbar.af',
                'source_address' => 'kabul-afghanistan',
                'organization_id' => '',
                'title' => 'تیل بند امیر',
                'type' => 1,
                'duration' => '2',
                'price' => '230.0',
                'auth_number' => '2984',
                'close_date' => Carbon::now(),
                'close_date' => Carbon::now(),
                'offer_price' => '250.0',
                'project_price' => '500.0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'organization_id' => $faker->randomElement($orgIDs),
            ],
                        [
                's_number' => '120',
                'issue_date' => Carbon::now(),
                'issue_address' => 'jobs.af',
                'source_address' => 'افغانستان',
                'organization_id' => '',
                'title' => 'تیل ریاست معارف',
                'type' => 1,
                'duration' => '2',
                'price' => '230.0',
                'auth_number' => '2984',
                'close_date' => Carbon::now(),
                'close_date' => Carbon::now(),
                'offer_price' => '250.0',
                'project_price' => '500.0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'organization_id' => $faker->randomElement($orgIDs),
            ],
        ];

        // Insert the data to this table
        DB::table('announcements')->insert($data);
        
        // Schema::enableForeignKeyConstraints();
    }
}
