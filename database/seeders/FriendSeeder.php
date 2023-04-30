<?php

namespace Database\Seeders;

use App\Models\Friend;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->data() as $friend){
            Friend::create($friend);
        }
    }

    private function data(): array
    {
        $data = [
            [
                'source_id' => 1,
                'target_id' => 2,
                'status' => Friend::STATUS_SENT,
            ],
            [
                'source_id' => 1,
                'target_id' => 3,
                'status' => Friend::STATUS_ACTIVE
            ],
            [
                'source_id' => 1,
                'target_id' => 4,
                'status' => Friend::STATUS_REJECTED,
            ],
            [
                'source_id' => 1,
                'target_id' => 5,
                'status' => Friend::STATUS_DELETED,
            ],
            [
                'source_id' => 6,
                'target_id' => 1,
                'status' => Friend::STATUS_SENT,
            ],
            [
                'source_id' => 7,
                'target_id' => 1,
                'status' => Friend::STATUS_ACTIVE,
            ],
            [
                'source_id' => 8,
                'target_id' => 1,
                'status' => Friend::STATUS_REJECTED,
            ],
            [
                'source_id' => 9,
                'target_id' => 1,
                'status' => Friend::STATUS_DELETED,
            ],
        ];

        return $data;
    }
}
