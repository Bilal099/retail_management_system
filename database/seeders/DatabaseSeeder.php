<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
//        \App\Models\Product::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Developer',
             'email' => 'dev@admin.com',
             'email_verified_at' => now(),
             'password' => Hash::make('password'),
             'remember_token' => Str::random(10),
         ]);

        \App\Models\Unit::create([
            'name'  => 'KG',
//            'parent_id' => 0,
        ]);

        \App\Models\Unit::create([
            'name'  => '5 KG',
            'parent_value' => 5,
            'parent_id' => 1,
        ]);

        \App\Models\Product::create([
            'name'  => 'Aata',
            'description'   => 'Product 1',
            'price' => 0,
            'unit_id' => 1,
            'created_by'    => 1
        ]);

        \App\Models\Product::create([
            'name'  => 'Aata HH 5kg',
            'description'   => 'Product 1',
            'price' => 0,
            'unit_id' => 1,
            'created_by'    => 1
        ]);

        $transaction_types = array(
            'Investment Deposit',
            'Sale',
            'Purchase',
            'Expense'
        );

        $sub_transaction_types = array(
            'Packaging Material Expense',
            'Rent Expense',
            'Salaries Expense',
            'Purchase Equipments Expense',
            'Utility Expense',
            'Maintainance Expense',
        );

        foreach ($transaction_types as $type) {
            \App\Models\TransactionType::create([
                'name' => $type,
//                'parent_id' => 0,
            ]);
        }

        foreach ($sub_transaction_types as $type) {
            \App\Models\TransactionType::create([
                'name' => $type,
                'parent_id' => 4,
            ]);
        }


    }
}
