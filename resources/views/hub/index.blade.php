@extends('layout')

@section('header')
<!-- page head start-->
<div class="page-head">
    <h3>Hub</h3>
    <span class="sub-title">Hub/</span>
    <form method="post" action="index.html" class="search-content">
        <input type="text" placeholder="Search module or service..." name="keyword" class="form-control">
    </form>
</div>
<!-- page head end-->

@endsection

@section('content')

<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        @if(count($services) > 0)
             @foreach($services as $service)
             <div class="col-lg-4 col-md-6 col-sm-6 mr-b-20 plugin-card">
        <div class="dv-prod">
          <div class="plugin-card-top">
            <div class="name column-name">
              <h3>
                <a href="#" data-toggle="modal" data-target="#service-desc">
                    <!--src="{{$service['image_url']}}"--> 
                    {{$service['name']}}<img width=2 class="plugin-icon img-responsive" alt="N/A">
                </a>
              </h3>
            </div>
            <div class="desc column-description">
              <p>{{$service['description']}} </p>
              <!--<a href="#" data-toggle="modal" data-target="#service-desc">More Details</a>-->
            </div>
          </div>
          <div class="plugin-card-bottom">
            <div class="column-updated">
                <button class="btn btn-primary"  onclick="install('{{$service['url']}}', '{{$service['name']}}')"><span id="{{$service['name']}}">Install</span></button>
              <a class="btn btn-success" type="button" href="{{$service['url']}}">Download</a>
            </div>
            <div class="column-downloaded">
              <p class="authors"><cite>By <a href="#">{{$service['author']}}</a></cite></p>
              <span>300+ Installs</span>
            </div>
          </div>
        </div>  
      </div>
            @endforeach
       
    @else
       <h3 class="text-center alert alert-info">Sorry Services could not be fetched !</h3> 
            @endif
    </div>
</div><!--body wrapper end-->
<script>
            function install(url, service_name) {
                   $('#'+service_name).html('Installing...');
                   httpGetAsync("{{url('/')}}"+'/install_service?url='+url, function(output){
                       console.log(output);
                   state = JSON.parse(output);
                   if(state.status == "true"){
                       $('#'+service_name).html('Installed');
                   } else {
                       $('#'+service_name).html('Failed :(');
                   }
               })
           }
           function httpGetAsync(theUrl, callback){
{               var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() { 
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                        callback(xmlHttp.responseText);
                }
                xmlHttp.open("GET", theUrl, true); // true for asynchronous 
                xmlHttp.send(null);
}           }
</script>

@endsection
