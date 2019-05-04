<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class SAF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Follow:SAF {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set absorbed follower';

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
        //
        $this->call('Follow:mapfollowers', [
            'id' => $this->argument('id')
        ]);
        $x=DB::UPDATE('UPDATE people SET absorb=? WHERE id IN (SELECT peoples.id FROM (select id from people WHERE cond>0 AND page=?) peoples INNER JOIN (select id from followers WHERE page=?) follower ON follower.id=peoples.id)',[1,$this->argument('id'),$this->argument('id')]);
        echo $x;

        
    }
}
