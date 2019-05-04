 @extends('layouts.app')

@section('content')

      @if(count($allPage)>0)
    

    @foreach($allPage[0]['page'] as $page)

        <div class="row">

   {{-- row1 --}}
   
                <div class="col alert alert-primary alert-dismissible">
                                <input  type="hidden" value="{{$page->id}}" ></input>
                                <button onclick="delete_target(this)"  type="button" class="close" data-dismiss="alert">&times;</button>
                    <div class="row">
                            
                    <div class="col-lg-8">
                                
                    <div class="row">
                                
                        <div class="col-lg-1 badge badge-success"><h6>{{'name:'}}</h6></div>
                        <div class="col-lg-2 "></div>
                        <div class="col-lg-5 text-center" ><h4 style="font-family:fantasy;">{{$page->user}}</h4></div>
                        
                    </div>
                    <div class="row" style="margin-top:10px;">
                            <div class="col-lg-2 badge badge-secondary"><h4>{{'condition:'}}</h6></div>
                            <div class="col-lg-1 "></div>
                            <input class='num' type="hidden" value="{{$page->id}}">
                            @if($page->cond==1)
                            
                            <button   type="button" class="btn btn-primary col-lg-2 cond">active</button>
                            @else
                            <button     type="button" class="btn btn-warning col-lg-2 cond">Inactive</button>
                
                            @endif
                            <div class="col-1"></div>
                            <button style="padding:0;"   type="button" class="btn btn-success col-lg-2 "><a style="text-decoration:none;width:100;display:block;margin:0;padding:0;color:white;font-weight:800;line-height:1.5;" href="/report/{{$page->id}}">Reports</a></button>
                    </div>
                </div>
                <div class="col-lg-4 badge-secondary">
                    <div style="border:1px solid;" class="row">
                        <div    class="col-3 bg-info ">
                            <h6 style="line-height:2.85;overflow:hidden" >
                            follower:
                            </h6>

                        </div>
                        <div    class="col-3 ">
                                <h6 style="line-height:2.85;font-family:initial;" class="text-center" >
                                        {{$allPage[1][$page->id]}}
                                </h6>
    
                        </div>
                        <div    class="col-3 bg-danger">
                                <h6 style="line-height:2.85;overflow:hidden" >
                                followed:
                                </h6>
    
                            </div>
                            <div    class="col-3 ">
                                    <h6 style="line-height:2.85;font-family:initial;" class="text-center" >
                                        {{$allPage[3][$page->id]}}
                                    </h6>
        
                            </div>
                    </div>
                    <div  class="row">
                            <div class="col-3 bg-info">
                                    <h6 style="line-height:2.85;overflow:hidden">
                                            following:
                                    </h6>

                            </div>
                            <div    class="col-3 ">
                                    <h6 style="line-height:2.85;font-family:initial;" class="text-center" >
                                        {{$allPage[2][$page->id]}}
                                    </h6>
        
                            </div>
                            <div class="col-3 bg-danger">
                                    <h6 style="line-height:2.85;overflow:hidden">
                                            Unfollowed:
                                    </h6>

                            </div>
                            <div    class="col-3 ">
                                    <h6 style="line-height:2.85;font-family:initial;" class="text-center" >
                                        {{$allPage[4][$page->id]}}
                                    </h6>
        
                            </div>
                    </div>
                    
                    
                </div>
                <div class="row col-12" style="margin-top:10px;">
                <p class="badge-warning" style="margin:0 5px 0 0;">{{"Massage: ".$page->massage}}</p>
                </div>
          

                
             <div class="row col-12" style="margin-top:10px;">

             <p class="badge-primary" style="margin:0 0 0 0;">{{"Unfollow after: "}}<input id="unfollow-input-{{$page->id}}" style="width:40px;height:20px;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;" value="{{($page->unfollow/3600)}}">{{" Hours"}}</p>
             <div id="unfollow-{{$page->id}}" style="width:40px;height:25px;background:green;color:white;text-align:center;box-shadow:0 0 1px black;cursor:pointer">save</div>
            </div>

        </div>
        
        <div class="row" style="margin-top:20px" >
                        <div onclick="update({{$page->id}})" class="btn btn-danger" style="color:white">
                                Update
                        </div>
        </div>

 
    
                        
                </div>

                {{--end row 1  --}}
                </div>

    @endforeach
@endif    


@endsection 
@section('home')
{{'active'}}

@endsection 
@section('js')
<script>

        $("div[id^='unfollow']").click(function(){
                var id=$(this).attr("id");
                id=id.split('-');
                id=id[1];
                var timeU=$('#unfollow-input-'+id).val();
                $.ajax({
                method: "POST",
                url: "/setUnfollow/page",
                data: { page_id: id,time: timeU,_token:'{{csrf_token()}}' }
                }).done(function( msg ) {
                        if(msg!='')
                        alert(msg);

                        $('#unfollow-input-'+id).val(timeU);


                })
                    
                

        });
        
        function delete_target(el)
        {
            var id_page=el.parentElement.childNodes[1].value;
            $.ajax({
            method: "POST",
            url: "/delete/page",
            data: { id: id_page,_token:'{{csrf_token()}}' }
            }).done(function( msg ) {
                    

               
                
           
        });
        }

$('.cond').click(function()
{
       
    var id=$(this).parent().children('.num').val();
    var tag=$(this);
        $.ajax({
        method: "POST",
        url: "change/condition",
        data: { id: id ,_token:'{{csrf_token()}}' }
        })
        .done(function( msg ) {
                if(msg==1)
                {
                        tag.html("active");
                        tag.removeClass('btn-warning').addClass('btn-primary');
                        
                }
                else if(msg==0)
                {
                        tag.html("inactive");
                        
                        tag.removeClass('btn-primary').addClass('btn-warning');
                        
                }
               
        });
    
  
});

</script>  

@endsection
  
