<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\page;
use App\person;
use App\target;
use InstagramAPI\Request\People;
use Illuminate\Database\QueryException;
use Illuminate\Console\Command;

class pageControll extends Controller
{
    //
    public function updatePage(Request $request)
    {
        $request->validate(
            [
                'page'=>'required'
            ]
            );
            $command=new Command;
            $command->call('Follow:SFA');
            $command->call('Follow:mapfollowing '.$request->input('page'));



    }
    public function getAll()
    {
        $pageM=new page;
        $resulte=$pageM->getAll();
        $personM=new person;
        $following=[];
        $follower=[];
        $followed=[];
        $unfollowed=[];

        
        foreach($resulte['page'] as $r)
        {
            $p=$personM->getAllFollower($r->id);
            $p=[$r->id=>$p[0]->num];
            $follower=$follower+$p;
            $p=$personM->getAllFollowing($r->id);
            $p=[$r->id=>$p[0]->num];
            $following=$following+$p;
            $p=$personM->getAllFollowed($r->id);
            $p=[$r->id=>$p[0]->num];
            $followed=$followed+$p;
            $p=$personM->getAllUnfollowed($r->id);
            $p=[$r->id=>$p[0]->num];
            $unfollowed=$unfollowed+$p;
            


        }
        $resulte=[0=>$resulte,1=>$follower,2=>$following,3=>$followed,4=>$unfollowed];
        
        
        return view('index')->with('allPage',$resulte);

    }
    public function deletePage(Request $request)
    {
        $request->validate([
            'id'=>'required'
        ]);
        $page_id=$request->input('id');
        $pageM=new page;
        $pageM->deletePage($page_id);


    }
    public function changeCondition()
    {
        $id=$_POST['id'];
        $page=page::find($id);
        
        if($page->cond==1)
        {
           
            page::where('id',$id)->update(['cond'=>0]);
            return 0;

        } 
        elseif($page->cond==0){
            
            page::where('id',$id)->update(['cond'=>1]);
            return 1;

        }
        


    }
    public function report($id)
    {
        $pageM=new page;
        $an=$pageM->report($id);
        return view('report',['analyze'=>$an,'id'=>$id]);


    }
    public function searchTarget(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'target' => 'required',
        ]);
    
        
        

        
        $id=$request->input('id');
        $pageFather=DB::select('select * from pages where id=?',[$id]);
        $ig=$this->loginInstagram($pageFather[0]->user, $pageFather[0]->pass);
        
       
        $rankToken = \InstagramAPI\Signatures::generateUUID();
        $query=$request->input('target');
        $d=$ig->people->search(
        $query,
        $excludeList = [],
        $rankToken);
        $an=[];
        $temp=json_decode($d)->users;
        
        foreach($temp as $find)
        {
            array_push($an,['username'=>$find->username,'url_pro'=>$find->profile_pic_url]);
        }
        $json_result=json_encode($an);
        echo $json_result;
}
public function addTarget(Request $request)
{
    $request->validate([
        'target_username' => 'required',
        'id' => 'required',
        'type_checkbox' => 'required',
    ]);
    
     $target_username=$request->input('target_username');
     $page_id=$request->input('id');
     $type=$request->input('type_checkbox');
     
     if($type=='true')
     {
        
        $type=1;
        
     }
     else
     {
        $type=0;
        

     }

     
     $rep=DB::select('select * from targets where username=? AND page=? AND type=?',[$target_username,$page_id,$type]);
     if(count($rep)==0)
     {

        DB::table('targets')->insert(['username' => $target_username, 'page' => $page_id,'type'=>$type]);
        $targ=DB::select('select * from targets where username=? AND type=?',[$target_username,$type]);
     
        if($type==1)
        {
           
           
           $this->addLiker($targ,$page_id);
        }
        else
        {
           
           $this->addFollower($targ,$page_id);
   
        }

     }
     else
     echo 'Target is repetitive';

}
  function addFollower($targ,$id,$recours=NUll)
  {
      if($recours==Null)
      {
        $username=$targ[0]->username;
        $targ_id=$targ[0]->id;


      }
      elseif($recours==1)
      {
        $username=$targ['username'];
        $targ_id=$targ['id'];

      }
      



      if($recours==NULL)
      echo "Page added for followeres refresh!";
      elseif($recours==1)
      echo "the number of follower refreshd!";

      
      $targ=DB::select('select * from targets where username=? AND type=?',[$username,0]);
  
        $pageM=new page;
        $page_res=page::find($id);
        $ig=$this->loginInstagram($page_res->user, $page_res->pass);
        $rankToken = \InstagramAPI\Signatures::generateUUID();
        $userId = $ig->people->getUserIdForName($username);
        $maxID = $targ[0]->Max;
        $users = array();
        $i = 0;
        $page = 0;
        $arr=[];
        
         do {
            $info=$ig->people->getFollowers($userId,$rankToken,null,$maxID);
            $res=json_decode($info);
            
            foreach ($res->users as $user) {
                $i++;
                $temp= [0=>($user->pk.'_'.$id), 1=>$user->pk,2=>$user->username,3=>0,4=>$targ_id,5=>$id];
                array_push($arr,$temp);

                                
            }
            
            
            $pageM->IMI($arr,'people',['id','userid','username','cond','target','page']);
            
            $arr=[];
                
        } while (!is_null($maxID = $info->getNextMaxId())&& $i<8);
        DB::table('targets')->where('id',$targ[0]->id)->update(['Max' =>$info->getNextMaxId() ]);
        $pageM->subtractPF($id,$targ[0]->id);

  }
  function addLiker($username,$id)
  {
    echo "Page added for likeres refresh!";

  }
  function refresh(Request $request)
  {
      $page=$request->input('id');
      $target=$request->input('target');
      $targ=target::find($target);
      if($targ->type==0)
      $this->addFollower($targ,$page,1);

      
  }
  function deleteTarget(Request $request)
  {
      $id=$request->id;
      DB::delete('delete from people WHERE target=? AND cond=?',[$id,0]);
      DB::delete('delete from targets WHERE id=?',[$id]);
      

  }
  function setUnfollow(Request $request)
  {
      $request->validate([
          'page_id'=>'required',
          'time'=>'required'
      ]);
      if($request->input('time')>=1)
      {
        $pageM=new page;
        $pageM->setUnfollowTime($request->input('page_id'),$request->input('time'));

      }
      else
      {
          echo 'unfollow time most be greater than 1 hour';
      }


  }
}
