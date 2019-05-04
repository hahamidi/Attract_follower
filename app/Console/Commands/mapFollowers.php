<?php

namespace App\Console\Commands;
use App\Http\Controllers\Controller;
use App\page;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class mapFollowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Follow:mapfollowers {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map follower to followers';

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
        $maxID = null;
        $users = array();
        $i = 0;
        $page = 0;
        $arr=[];
        DB::delete('DELETE FROM followers WHERE page=?',[$id]);
        do {
            $info=$ig->people->getSelfFollowers($rankToken,null,$maxID);
            $res=json_decode($info);
            
            foreach ($res->users as $user) {
                $i++;
                $temp= [0=>($user->pk.'_'.$id), 1=>$id];
                array_push($arr,$temp);

                                
            }
            
            $pageM->IMI($arr,'followers',['id','page']);

                $arr=[];
                
        } while (!is_null($maxID = $info->getNextMaxId()));
        
        



    }
}
