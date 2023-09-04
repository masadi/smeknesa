<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;

class GenerateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:role';

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
        $roles = [
            [
                'name' => 'tu',
                'display_name' => 'Tata Usaha',
                'description' => 'Tata Usaha',
            ],
            [
                'name' => 'super',
                'display_name' => 'Super Admin',
                'description' => 'Super Admin',
            ],
        ];
        foreach($roles as $role){
            Role::updateOrCreate(
                [
                    'name' => $role['name'],
                ],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
        return Command::SUCCESS;
    }
}
