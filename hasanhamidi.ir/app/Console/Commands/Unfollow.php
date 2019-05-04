<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Console\Command;

class Unfollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Follow:unfollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'unfollow some people from some page';

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
        $pages=DB::select('SELECT * FROM pages WHERE cond=1 AND u <'.time().'');
       
        foreach($pages as $page)
        {
          $page_id=$page->id;
          $page_selected=DB::select('SELECT * FROM following WHERE FBS=1 AND page=? AND unfollowTime<'.time().' ORDER BY RAND() LIMIT 5',[$page_id]);
          if(count($page_selected)<5)
            {
                
                $this->call('Follow:mapfollowing', [
                    'id' => $page_id
                ]);
                $page_selected=DB::select('SELECT * FROM following WHERE FBS=1 AND page=? AND unfollowTime<'.time().' ORDER BY RAND() LIMIT 5',[$page_id]);
                if(count($page_selected)<5)
                {
                DB::update('UPDATE pages SET u=?  WHERE id=?',[(time()+5000),$page_id]);
                }
            }
            else
            {
                $ig=Controller::loginInstagram($page->user, $page->pass);
                

                foreach($page_selected as $page)
                {
                    
                    sleep(0.5);
                    
                    $page=explode('_',$page->id);
                    $page=$page[0];
                    echo 'U=>'.$page;
                    $ig->people->unfollow($page);
    
                }
                $timef=time();
                $time=getdate();
                DB::update('UPDATE people SET cond=2,unfollowed=? WHERE page=? AND id IN (?,?,?,?,?)',[$timef,$page_id,$page_selected[0]->id,$page_selected[1]->id,$page_selected[2]->id,$page_selected[3]->id,$page_selected[4]->id]);
                DB::delete('DELETE FROM following WHERE page=? AND id IN (?,?,?,?,?)',[$page_id,$page_selected[0]->id,$page_selected[1]->id,$page_selected[2]->id,$page_selected[3]->id,$page_selected[4]->id]);

            }


        }
        
    }
}
