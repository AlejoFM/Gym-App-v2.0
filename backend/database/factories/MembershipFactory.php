<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $membershipPayment = now();
        $membershipPaymentExpire = Carbon::parse($membershipPayment)->addDays(30);
        return [
            'user_id' => rand(1,3),
            'membership_type' => fake()->name(),
            'membership_payment' => $membershipPayment,
            'membership_payment_expire' => $membershipPaymentExpire,
        ];
    }
}
