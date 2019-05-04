<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class page extends Model
{
    //
    public $timestamps = false;
    public function deletePage($id)
    {
        DB::delete('DELETE FROM pages WHERE id=?',[$id]);
        DB::delete('DELETE FROM targets WHERE page=?',[$id]);
        DB::delete('DELETE FROM people WHERE page=?',[$id]);
        DB::delete('DELETE FROM followers WHERE page=?',[$id]);
        DB::delete('DELETE FROM following WHERE page=?',[$id]);


    }
    public function getAll()
    {
        $page=page::all('id','user','cond','massage','unfollow');
        $Targest=target::all();
        $resulte=['target'=>$Targest,'page'=>$page];
        return $resulte;
    }
    public function report($id)
    {
        $data=DB::select('select target.type,target.username,target.id,people.cond,count(*) as num from ((select * from targets where page=?) target INNER JOIN (select * from people where cond BETWEEN 0 AND 2) people ON target.id=people.target) GROUP BY cond,id',[$id]);
        
        $data2=target::where('page',$id)->get();
        
      

  
        $an=[];

        foreach($data2 as $item)
        {
            $absorb=DB::select('SELECT count(*) AS num FROM people WHERE absorb=? AND target=?',[1,$item['id']]);
            $absorb=$absorb[0]->num;


            $p=['id'=>$item['id'],'cond0'=>0,'cond1'=>0,'cond2'=>0,'absorb'=>$absorb];
            
            
            
            foreach($data as $item1)
            {
                if($item->username==$item1->username && $item->type==$item1->type)
                {
                    

                    if($item1->cond==0)
                    {
                        $p['cond0']=$item1->num;

                    }
                    elseif($item1->cond==1)
                    {
                        $p['cond1']=$item1->num;
                    }
                    elseif($item1->cond==2)
                    {
                        $p['cond2']=$item1->num;

                    }

                }
            }
            if($item->type==1)
            {
                $type='<div style="width:20px;height:20px;background-color:red;">L</div>';

            }
            else
            {
                $type='<div style="width:20px;height:20px;background-color:green;">F</div>';

            }
            $r=[$item->username.$type=>$p];
            $an=array_merge($an,$r);

            


        }
        return $an;

    }
    public static function IMI($datas=null,$table=null,$columns=null)
    {
        
        //insert multi data with ignore primarykey dublcate
        $query="INSERT IGNORE INTO ".$table." (".$columns[0];
        foreach($columns as $x=>$column)
        {
            if($x!=0)
            $query=$query.",".$column;
            
        }
        $query=$query.") VALUES ";
        foreach($datas as $p=>$data)
        {
            if($p!=0)
            $query=$query.',(';
            else
            $query=$query.'(';

            foreach($data as $k=>$item)
            {
                if($k!=0)
                $query=$query.",'".$item."'";
                else
                $query=$query."'".$item."'";

            }
            $query=$query.")";

        }
        
        DB::insert($query);


    }
    public static function subtractPF($id ,$targ=NULL)
    {
        if($targ==NULL)
        {
            DB::delete('delete from people where page=? AND cond=? AND  id IN (select id from followers where page=? )',[$id,0,$id]);

        }
        else
        {
            DB::delete('delete from people where page=? AND cond=? AND target=? AND id IN (select id from followers where page=? )',[$id,0,$targ,$id]);
        }
       


    }
    public function setUnfollowTime($page,$time)
    {
        DB::update("UPDATE pages SET unfollow=? WHERE id=?",[($time*3600),$page]);

    }

    

}
