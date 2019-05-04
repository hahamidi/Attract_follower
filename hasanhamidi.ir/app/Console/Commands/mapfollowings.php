<?php

namespace App\Console\Commands;
use App\Http\Controllers\Controller;
use App\page;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class mapfollowings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Follow:mapfollowing {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'map following of account in following table';

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
        $pageM=new page;
        $page=DB::select('select * from pages where id=?',[$this->argument('id')]);
        
        $ig=Controller::loginInstagram($page[0]->user,$page[0]->pass);
        
        $rankToken = \InstagramAPI\Signatures::generateUUID();
        $id=$page[0]->id;
        $timeS=(time()+$page[0]->unfollow);
        $maxID = null;
        $users = array();
        $i = 0;
        $page = 0;
        $arr=[];
        
        
        do {
            $info=$ig->people->getSelfFollowing($rankToken,null,$maxID);
            $res=json_decode($info);
            
            foreach ($res->users as $user) {
                $i++;
                $temp= [0=>($user->pk.'_'.$id), 1=>$id,2=>$timeS];
                array_push($arr,$temp);

                                
            }
            
            $pageM->IMI($arr,'following',['id','page','unfollowTime']);

                $arr=[];
                
        } while (!is_null($maxID = $info->getNextMaxId()));


        DB::update('UPDATE following SET FBS=1 WHERE page=? AND id IN (SELECT id FROM people WHERE cond=1 AND page=?)',[$id,$id]);
        

        
    }
}
