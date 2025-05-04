<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AdminSeeder::class,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Emergency Relief', 'slug' => 'emergency-relief', 'color' => '#FF4444'],
            ['name' => 'Education', 'slug' => 'education', 'color' => '#33B5E5'],
            ['name' => 'Healthcare', 'slug' => 'healthcare', 'color' => '#00C851'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Campaigns
        $campaigns = [
            [
                'title' => 'COVID-19 Relief Fund',
                'description' => 'Help us support families affected by COVID-19',
                'type' => 'event',
                'goal_amount' => 100000,
                'status' => 'active',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'category_id' => 1,
                'is_urgent' => true
            ],
            [
                'title' => 'School Supplies Drive',
                'description' => 'Providing school supplies to underprivileged children',
                'type' => 'event',
                'goal_amount' => 50000,
                'status' => 'active',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'category_id' => 2
            ]
        ];

        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }

        // Create Donations
        $donations = [
            [
                'campaign_id' => 1,
                'donor_name' => 'John Doe',
                'donor_email' => 'john@example.com',
                'type' => 'monetary',
                'amount' => 1000,
                'status' => 'completed',
                'payment_method' => 'credit_card',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ],
            [
                'campaign_id' => 1,
                'donor_name' => 'Jane Smith',
                'donor_email' => 'jane@example.com',
                'type' => 'monetary',
                'amount' => 500,
                'status' => 'completed',
                'payment_method' => 'bank_transfer',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'campaign_id' => 2,
                'donor_name' => 'Bob Wilson',
                'donor_email' => 'bob@example.com',
                'type' => 'non-monetary',
                'amount' => 0,
                'item_description' => 'School Backpacks',
                'quantity' => 50,
                'status' => 'pending',
                'payment_method' => 'dropoff',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ]
        ];

        foreach ($donations as $donation) {
            $d = Donation::create($donation);
            if ($d->campaign) {
                $d->campaign->updateTotalDonations();
            }
        }
    }
}
