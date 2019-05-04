@extends('layouts.app')
@section('content')

<div class="form-group">
    <input id="id" type="hidden" name="id" value="{{$id}}">
    <label for="target">Add target:</label>
    <input class="form-control" id="target" placeholder="Enter target" name="target">
    <div style="min-height:100px;max-height:200px;overflow: auto;display:none;height:auto" id='result_search'>
          

    </div>
    
    <button id='search' style="margin-top:10px;" class="btn btn-primary">َsearch</button>
</div>

<div>
<div style="margin-top:10px;" class="row">
    <div class="col alert alert-success text-center">
        Targets
    </div>

    
</div>
<div style="margin-top:5px;" class="row">


@foreach($analyze as $x=>$page)
<div style="border:2px red dashed;"  class="col-12 col-sm-6 col-lg-3 alert alert-primary alert-dismissible ">
<input  type="hidden" value="{{$page['id']}}" ></input>
<input  type="hidden" value="{{$id}}"></input>
    <button onclick="delete_target(this)"  type="button" class="close" data-dismiss="alert">&times;</button>
<div class="bg-dark text-center text-white" style="width:100%;margin:auto;margin-top:5px;"><?= $x?></div>
<div class="bg-dark text-center text-white" style="width:100%;margin:auto;margin-top:5px;">{{'Followed: '.($page['cond1']+$page['cond2'])}}</div>
<div class="bg-dark text-center text-white" style="width:100%;margin:auto;margin-top:5px;">{{'Absorb: '.($page['absorb'])}}</div>
<div class="bg-dark text-center text-white" style="width:100%;margin:auto;margin-top:5px;">{{'Percent: '}} 
    @if(($page['cond1']+$page['cond2'])!=0)
    {{round((($page['absorb'])/($page['cond1']+$page['cond2'])*100),2).'%'}}
    @else
    {{'0'.'%'}}

    @endif


</div>
<button class="btn refresh" onclick="refresh(this)" style="width:100%;margin:auto;margin-top:5px;" ><img src="{{asset('img/refresh.png')}}" style="width:20px;;height:20px;"><p style="margin:auto;font-weight:700;" class="number">{{$page['cond0']}}</p></button>


</div> 

@endforeach


</div>


@endsection
@section('js')
<script>
    function targ_add(target_element)
    {
        
        var id=$('#search').parent().children('#id').val();
        var target_user=target_element.innerHTML;
        var type=target_element.parentElement.childNodes[3].checked;
    
        $.ajax({
        method: "POST",
        url: "/add/target",
        data: { id: id,target_username: target_user,type_checkbox: type,_token:'{{csrf_token()}}' }
        }).done(function( msg ) {
            alert(msg)
           
            

        });

    }

    function addTarget(json_target)
    {
        
        
         var result_search_tag=$('#result_search');
         result_search_tag.css('display','block');
         result_search_tag.html('');
         var items=JSON.parse(json_target);
         items.forEach(element => {
             var tag='<div  class="targs" style="width:100%;height:30px;border:1px solid #ccc;background:white;border-top:none;cursor:pointer;"><div style="width:2%;height:100%;float:left;"></div><p onclick="targ_add(this)" style="font-size:24;display:inline;" >'+element['username']+'</p><img src='+element['url_pro']+' style="width:20px;height:20px;float:right;border-radius:100%;margin-right:5px;margin-top:5px;"><input name="type" value="3" style="float:right;margin-right:4%;margin-top:5px;" type="checkbox" ><p style="float:right;padding:0;margin:0;">likes? </p></div>';
             result_search_tag.append(tag);
             
         });
       


    }
    $('#search').click(function()
    {
        
        var id=$(this).parent().children('#id').val();
       
       var target=$('#target').val();
       
        $.ajax({
        method: "POST",
        url: "/search/target",
        data: { id: id,target: target,_token:'{{csrf_token()}}' }
        }).done(function( msg ) {
          
            addTarget(msg);
           
            

        });

  
});
function refresh(el)
        {
            
             var id_tar=el.parentElement.childNodes[1].value;
             var id_page=el.parentElement.childNodes[3].value;
             
             
             $.ajax({
            method: "POST",
            url: "/refresh/target",
            data: { id: id_page,target:id_tar,_token:'{{csrf_token()}}' }
            }).done(function( msg ) {
                
               alert(msg);
            
                
           
        });
            

            

        }
        function delete_target(el)
        {
            var id_tar=el.parentElement.childNodes[1].value;
            $.ajax({
            method: "POST",
            url: "/delete/target",
            data: { id: id_tar,_token:'{{csrf_token()}}' }
            }).done(function( msg ) {
            
                
           
        });
        }
    


   
</script>
@endsection