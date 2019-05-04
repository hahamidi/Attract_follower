@extends('layouts.app')
@section('content')
<div class="col"  style="background:#292F33;padding:10px;border-radius:3px;margin:0 0 10px 0;">
    <div class="row">
        <p>
            last followed on 12:47
        </p>
    </div>
    <div class="row">

        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="text-center" style="border:1px solid #f0284b;border-radius:2px;height:30px;margin:auto;color:#f0284b;">
                    cool2movie
                </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2" >
                {{-- <div style="border:2px solid #f0284b;width:200px;height:100px;margin:auto;position:relative;">
                    <div class="col-3" style="background:#55ACEE;height:10px;position:absolute;bottom:0;">
                    </div>
                </div> --}}
                <div class="text-center" style="border:1px solid #f0284b;border-radius:2px;height:30px;margin:auto;color:white;background:#f0284b">
                        active
                </div>
            </div>
            {{-- <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <div class="text-center" style="border:1px solid #f0284b;border-radius:2px;height:30px;margin:auto;color:#f0284b;">
                        
                    </div>
            </div> --}}
    </div>

</div>
@endsection