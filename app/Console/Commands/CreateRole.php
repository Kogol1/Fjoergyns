<?php

namespace App\Console\Commands;

use App\Role;
use Illuminate\Console\Command;

class CreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:create {role_name} {order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $command;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $role = new Role();
      $role->role_name = $this->argument('role_name');
      $role->order = $this->argument('order');
      $role->save();
      echo 'Role '.$this->argument('role_name').' vytvořena';
    }
}
