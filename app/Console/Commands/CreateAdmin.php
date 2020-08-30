<?php

namespace App\Console\Commands;

use App\Admin;
use App\Role;
use App\VoteUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = Role::where('role_name', $this->argument('role'))->first();
        if (is_null($role)){
            $roles = array_values(Role::all()->pluck('role_name')->toArray());
            echo 'Nebyla nalezena role. Vyberte z těchto rolí:'."\n";
            echo implode(', ', $roles);
            return false;
        }
      $admin = new Admin();
      $admin->name = $this->argument('name');
      $admin->role_id = $role->id;
      $admin->save();
      echo 'Admin '.$this->argument('name').' úspěšně vytvořen';
    }
}
