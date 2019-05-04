<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class person extends Model
{
    public $timestamps = false;

    public function getAllFollower($id)
    {
        
        $followed= DB::select("select count(*) as num from followers where page=?",[$id]);
        return $followed;

    }
    public function getAllFollowing($id)
    {
        
        $follower= DB::select("select count(*) as num from following where page=? ",[$id]);
        return $follower;

    }
    public function getAllFollowed($id)
    {
        $followed= DB::select("select count(*) as num from people where page=? and cond>0 ",[$id]);
        return $followed;
    }
    public function getAllUnfollowed($id)
    {
        $unfollowed= DB::select("select count(*) as num from people where page=? and cond>1",[$id]);
        return $unfollowed;
    }
     
}
