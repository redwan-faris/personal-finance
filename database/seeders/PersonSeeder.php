<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Person::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'phone' => '+1234567890',
            'type' => 'customer',
            'address' => '123 Main St, City, State 12345',
            'balance' => 25000, // $250.00 - They owe you money
            'note' => 'Regular customer',
        ]);

        Person::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'phone' => '+1234567891',
            'type' => 'supplier',
            'address' => '456 Oak Ave, City, State 12345',
            'balance' => -15000, // -$150.00 - You owe them money
            'note' => 'Office supplies supplier',
        ]);

        Person::create([
            'name' => 'Carol Davis',
            'email' => 'carol@example.com',
            'phone' => '+1234567892',
            'type' => 'employee',
            'address' => '789 Pine Rd, City, State 12345',
            'balance' => 0, // $0.00 - Settled
            'note' => 'Marketing team member',
        ]);

        Person::create([
            'name' => 'David Brown',
            'email' => 'david@example.com',
            'phone' => '+1234567893',
            'type' => 'customer',
            'address' => '321 Elm St, City, State 12345',
            'balance' => 75000, // $750.00 - They owe you money
            'note' => 'Premium customer',
        ]);

        Person::create([
            'name' => 'Eva Garcia',
            'email' => 'eva@example.com',
            'phone' => '+1234567894',
            'type' => 'supplier',
            'address' => '654 Maple Dr, City, State 12345',
            'balance' => -50000, // -$500.00 - You owe them money
            'note' => 'IT services provider',
        ]);
    }
}
