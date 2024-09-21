<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Semester;

class GenerateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = Role::where('name', 'administrator')->first();
        $semester = Semester::find(semester_id());
        $user = User::updateOrCreate(
            [
                'email' => 'administrator@smkn3buduran.sch.id'
            ],
            [
                'name' => 'Administrator',
                'password' => bcrypt('12345678')
            ]
        );
        Team::updateOrCreate([
            'name' => $semester->nama,
            'display_name' => $semester->nama,
            'description' => $semester->nama,
        ]);
        if(!$user->hasRole('administrator', periode_aktif())){
            $user->attachRole($role, periode_aktif());
        }
        return Command::SUCCESS;
    }
}
