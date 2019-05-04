<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use App\page;

class Add extends Controller
{
    //
    public function add(Request $request)
    {
        //gerftane darkhast
       
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required'
        ]);
        //check baraye tekrari nabodan
        $user=$request->input('username');
        $result = DB::select('select user from pages where user=?',[$user]);
        var_dump($result);
        if(count($result)==0)
        {

            $ig=$this->loginInstagram($request->input('username'), $request->input('password'));
            $result2 = DB::select('select username from user_sessions where username=?',[$request->input('username')]);
        
            //agar dar user_session insert shodeh bashad pas user pass drost bode va ela ghalt ast
            
            if(count($result2)>0)
            {
                $page = new page;
                $page->pass = $request->input('password');
                $page->user = $request->input('username');
                $page->cond = 1;
                $page->save();
                $pageAdded= DB::select('select id from pages where user=?',[$request->input('username')]);
                $t=time();
                $this->MF($pageAdded[0]->id);
                $this->MFing($pageAdded[0]->id);
                $t1=time();
                return redirect('/Add')->with('success','Page added'.$t.'//'.$t1);
            }
            else
            {
                return redirect('/Add')->with('error','Username or Password is wrong');

            }
            
        }
        else
        {
            return redirect('/Add')->with('error','Page is dobulcate');

        }


        
        DB::insert('insert into pages (user, pass, cond) values (?, ? , ?)', ['w222', 'Dayle',1]);

    }
}
