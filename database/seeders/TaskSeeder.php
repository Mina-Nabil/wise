<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users\Task;
use App\Models\Users\TaskComment;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     protected $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }
    public function run()
    {
        // Define an array of statuses
        $statuses = Task::STATUSES;

        // Create tasks with random data
        $userIds = User::pluck('id'); // Get user IDs

        for ($i = 0; $i < 20; $i++) {
            Task::create([
                'title' => $this->faker->sentence,
                'desc' => $this->faker->paragraph,
                'open_by_id' => $userIds->random(),
                'assigned_to_id' => $userIds->random(),
                'last_action_by_id' => $userIds->random(),
                'due' => $this->faker->dateTimeBetween('now', '+1 year'),
                'status' => $statuses[array_rand($statuses)], // Random status
            ]);
        }

        // Create task comments (optional)
        for ($i = 0; $i < 50; $i++) {
            TaskComment::create([
                'user_id' => $userIds->random(),
                'task_id' => Task::inRandomOrder()->first()->id,
                'comment' => $this->faker->paragraph,
            ]);
        }
    }
}
