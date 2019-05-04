<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function loginInstagram($username,$password)
    {
        set_time_limit(0);
        date_default_timezone_set('UTC');
        include 'vendor/autoload.php';
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram(false,true, [
            'storage'    => 'mysql',
            'dbhost'     => 'localhost',
            'dbname'     => 'insta',
            'dbusername' => 'root',
            'dbpassword' => '',
        ]);
          
          $ig->login($username,$password);
          return $ig;

    }
    public static function MF($id)
    {
               
                
                $command='php '.__DIR__.'/MFA.php '.$id;
                shell_exec($command);
    }
    public static function MFing($id)
    {
               
                
                $command='php '.__DIR__.'/MFingA.php '.$id;
                shell_exec($command);
    }

}
