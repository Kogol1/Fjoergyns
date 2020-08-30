<?php

namespace App\Console\Commands;

use App\Admin;
use App\AdminAlias;
use App\Role;
use Illuminate\Console\Command;

class CreateAlias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alias:create {admin_name} {alias}';

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
        $admin = Admin::where('name', $this->argument('admin_name'))->first();

        if (!is_null($admin)){
            $alias = new AdminAlias();
            $alias->alias_name = $this->argument('alias');
            $alias->admin_id = $admin->id;
            $alias->save();
            echo 'Alias '.$this->argument('alias').' vytvořen';
            return true;
        }
        echo 'Admin '.$this->argument('admin_name').' nenalezen';
    }
}
