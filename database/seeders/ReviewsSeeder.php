<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marketing\Review;
use App\Models\Business\SoldPolicy;
use App\Models\Users\User;
use Carbon\Carbon;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some sold policies and users
        $soldPolicies = SoldPolicy::limit(20)->get();
        $users = User::limit(10)->get();

        if ($soldPolicies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No sold policies or users found. Please seed those tables first.');
            return;
        }

        foreach ($soldPolicies as $soldPolicy) {
            $assignee = $users->random();
            $reviewedBy = $users->random();
            
            // Create base review data
            $reviewData = [
                'reviewable_type' => SoldPolicy::class,
                'reviewable_id' => $soldPolicy->id,
                'assignee_id' => $assignee->id,
                'title' => 'Review for Policy #' . $soldPolicy->policy_number,
                'desc' => 'Review client satisfaction for sold policy',
                'created_at' => now()->subDays(rand(1, 30)),
            ];

            // Randomly decide if review is completed (70% chance)
            if (rand(1, 100) <= 70) {
                $employeeRating = rand(50, 100) / 10; // 5.0 to 10.0
                $companyRating = rand(50, 100) / 10; // 5.0 to 10.0
                
                $reviewData = array_merge($reviewData, [
                    'is_reviewed' => true,
                    'reviewed_at' => now()->subDays(rand(1, 15)),
                    'reviewed_by_id' => $reviewedBy->id,
                    'employee_rating' => $employeeRating,
                    'company_rating' => $companyRating,
                ]);

                // Add comments for some reviews (60% chance)
                if (rand(1, 100) <= 60) {
                    $reviewData['client_employee_comment'] = $this->getRandomComment('employee');
                }
                
                if (rand(1, 100) <= 60) {
                    $reviewData['client_company_comment'] = $this->getRandomComment('company');
                }

                // Set manager review data if ratings are low (needs_manager_review is computed)
                if ($employeeRating < 8 || $companyRating < 8) {
                    // 50% chance manager has already reviewed
                    if (rand(1, 100) <= 50) {
                        $reviewData['is_manager_reviewed'] = true;
                        $reviewData['manager_reviewed_at'] = now()->subDays(rand(1, 10));
                        $reviewData['manager_reviewed_by_id'] = $users->where('is_admin', true)->first()?->id ?? $users->first()->id;
                        
                        // Add manager ratings and comments when manager reviewed
                        $reviewData['manager_employee_rating'] = rand(70, 100) / 10; // Manager tends to rate higher
                        $reviewData['manager_company_rating'] = rand(70, 100) / 10;
                        $reviewData['manager_client_employee_comment'] = 'Manager follow-up: Issue addressed with employee.';
                        $reviewData['manager_client_company_comment'] = 'Manager follow-up: Process improvements implemented.';
                    }
                }
            }

            Review::create($reviewData);
        }

        $this->command->info('Reviews seeded successfully!');
    }

    /**
     * Get random comment based on type
     */
    private function getRandomComment($type)
    {
        $employeeComments = [
            'Very professional and helpful.',
            'Excellent service, highly recommend.',
            'Could improve response time.',
            'Knowledgeable and courteous.',
            'Outstanding customer service.',
            'Needs better communication skills.',
            'Quick and efficient service.',
            'Very patient and understanding.',
        ];

        $companyComments = [
            'Great company with excellent policies.',
            'Process was smooth and efficient.',
            'Could improve claim processing time.',
            'Competitive rates and good coverage.',
            'Excellent customer support.',
            'Documentation process needs improvement.',
            'Quick approval process.',
            'Very satisfied with the service.',
        ];

        return $type === 'employee' ? 
            $employeeComments[array_rand($employeeComments)] : 
            $companyComments[array_rand($companyComments)];
    }
}
