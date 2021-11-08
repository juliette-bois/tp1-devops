<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'hC1gcRtafm@gmail.com',
            'api_token' => 'ePljnF7eL2uwMSULFquzukvr11LKerC92jWebDcmfSPDfhhRijmU7lR28OJAhhd2L6DqGdXkMfpyj4JQyf7pTmPmaZKzA1WNeZ7S',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        DB::table('groups')->insert([
            'name' => 'test'
        ]);

        DB::table('group_user')->insert([
            'user_id' => '1',
            'group_id' => '1'
        ]);

        DB::table('budgets')->insert([
            'group_id' => '1',
            'name' => 'test'
        ]);

        DB::table('spents')->insert([
            'budget_id' => '1',
            'name' => 'test',
            'amount' => '12',
            'comment' => 'aaaa'
        ]);

        DB::table('permissions')->insert([
            'budget_id' => '1',
            'user_id' => '1',
            'perm' => '1'
        ]);

	}
}
