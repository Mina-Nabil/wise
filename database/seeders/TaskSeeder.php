<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskComment;
use App\Models\Users\User;
use Carbon\Carbon;
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
            $newTask = Task::newTask(
                $this->faker->sentence,
                null,
                $userIds->random(),
                new Carbon($this->faker->dateTimeBetween('now', '+5 day')),
                $this->faker->paragraph
            );
            // Create task comments (optional)
            for ($k = 0; $i < 2; $i++) {
                $newTask->addComment($this->faker->paragraph, false);
            }
        }
    }
}
