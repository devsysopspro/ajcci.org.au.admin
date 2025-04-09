<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnquiryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meta_contents')->insert([
            'id' => 1,
            'title' => 'Enquiry',
            'slug' => 'Enquiry',
            'published' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('meta_fields')->insert([
            'title' => 'Name',
            'slug' => 'Name',
            'type' => 'text',
            'meta_content_id' => 1,
            'order' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('meta_fields')->insert([
            'title' => 'Email',
            'slug' => 'Email',
            'type' => 'text',
            'meta_content_id' => 1,
            'order' => 1,
            'created_at' => Carbon::now(),
        ]);

        DB::table('meta_fields')->insert([
            'title' => 'Message',
            'slug' => 'Message',
            'type' => 'text_editor',
            'meta_content_id' => 1,
            'order' => 1,
            'created_at' => Carbon::now(),
        ]);
    }
}
