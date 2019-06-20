<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UsersTableSeeder::class);

        $this->call(DiseasesTableSeeder::class);

        $this->call(SymptomsTableSeeder::class);

        $this->call(MedicationsTableSeeder::class);
        
        $this->call(RulesTableSeeder::class);

        $this->call(DiseaseMedicationSeeder::class);

        $this->call(DiseaseSymptomsTableSeeder::class);


       // $this->call(PatientsTableSeeder::class);


        $this->call(FactorsTableSeeder::class);


        $this->call(RuleFactorsTableSeeder::class);

        $this->call(RuleMedicationsTableSeeder::class);

    }
}
