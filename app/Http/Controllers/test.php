<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\page;
use App\person;
use App\target;
use InstagramAPI\Request\People;
use Illuminate\Support\Facades\Artisan;

class test extends Controller
{
    //
    public function test1() {

        // 
        $pageAdded= DB::select('select id from pages where user=?',['cool2movie']);
        $command='php '.__DIR__.'/MFA.php '.$pageAdded[0]->id;
        shell_exec($command);
        
        
       
        


        
    }
    public function test2() {

    }


    
}
