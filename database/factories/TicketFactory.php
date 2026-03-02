<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        // Realistic IT issues
        $itIssues = [
            'PC won\'t turn on', 'Internet keeps disconnecting', 'Forgot portal password',
            'Printer on 2nd floor is jammed', 'Software license expired', 'Blue screen of death',
            'Need access to shared drive', 'Student email not syncing', 'Mouse is broken',
            'Laptop battery dying fast', 'Cannot connect to projector', 'System running very slow'
        ];

        // Realistic, urgent user messages
        $descriptions = [
            'Please fix it immediately, I have an assignment due soon.',
            'I cannot do my work because of this issue.',
            'This is extremely urgent, please help as soon as possible!',
            'It has been like this since I logged in this morning.',
            'Let me know if I should just bring my laptop down to the KTYS IT office.',
            'I tried restarting my computer but it didn\'t help at all.',
            'This happens every single time I try to open the system.',
            'Please look into this when you have a moment, thank you.'
        ];

        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
            'reporter_name' => fake()->name(),
            'title' => fake()->randomElement($itIssues),

            // THE FIX: Picks a random realistic message instead of a random paragraph
            'description' => fake()->randomElement($descriptions),

            'category' => fake()->randomElement(['Hardware', 'Software', 'Network']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'status' => fake()->randomElement(['Open', 'Assigned', 'On Hold', 'Resolved']),

            // Default dates (March 2026)
            'due_date' => fake()->dateTimeBetween('2026-03-01', '2026-03-31')->format('Y-m-d'),
            'created_at' => fake()->dateTimeBetween('2026-03-01', '2026-03-31'),
            'updated_at' => now(),
        ];
    }
}
