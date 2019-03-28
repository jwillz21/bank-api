<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    // $lname = array(
    //   'Meadows',
    // 	'Morin',
    // 	'Gordon',
    // 	'Hutchinson',
    // 	'Fulton'
    // );
    // $email = array();

    public function run()
    {
      $name = array(
      'Chester Meadows',
     	'Lane Morin',
     	'Merrill Gordon',
     	'Tanya Hutchinson',
     	'Isabelle Fulton'
     );
      // DB::table('users'->insert([
      //       'name' => Str::random(10,
      //       'email' => Str::random(10.'@gmail.com',
      //       'password' => bcrypt('secret',
      //   ];
      // shuffle($name);
      for ($i = 0; $i < sizeOf($name); $i++){
        App\User::create([
          // 'name' => implode($name[$i]),
          'name' => $name[$i],
          'email' => str_replace(' ', '.', $name[$i]. '@gmail.com'),
          'password' => 'welcome',
        ]);
      }
    }
}
