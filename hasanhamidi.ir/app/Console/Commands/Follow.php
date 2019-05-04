<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Follow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Follow:followPeople';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Following handeler';

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
        $pages=DB::select('select * from pages where cond=1');
        foreach($pages as $page)
        {
          $page_id=$page->id;
          $page_selected=DB::select('SELECT * FROM people WHERE cond=0 AND page=? ORDER BY RAND() LIMIT 10',[$page_id]);
          if(count($page_selected)<10)
            {
                DB::update('UPDATE pages SET massage=?  WHERE id=?',["No one for follow chack targets!",$page_id]);
            }
            else
            {
                $ig=Controller::loginInstagram($page->user, $page->pass);
                

                foreach($page_selected as $page)
                {
                    
                   sleep(.5);
                   echo 'F=>'.$page->userid;
                    $x=$ig->people->follow($page->userid);
                    echo '/';
    
                }
                $timef=time();
                $time=getdate();
                DB::update('UPDATE people SET cond=1 ,followed=? WHERE page=? AND id IN (?,?,?,?,?)',[$timef,$page_id,$page_selected[0]->id,$page_selected[1]->id,$page_selected[2]->id,$page_selected[3]->id,$page_selected[4]->id]);
                DB::update('UPDATE pages SET massage=?  WHERE id=?',["last followed ".($time["hours"]).":".($time['minutes']),$page_id]);

            }


        }
        $this->call('Follow:unfollow');

        
    }
}
