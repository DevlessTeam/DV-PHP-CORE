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
<!--<div id="service-desc" class="modal fade" role="dialog">
  <div class="modal-dialog">

     Modal content
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Service Name </h4>
      </div>
      <div class="modal-body">
         
          body goes here 

            
              <div class="dynamic-space"></div>

      </div></form></div>
  </div>
</div>-->

<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        @if(count($services))
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
              <button class="btn btn-primary" type="submit">Install</button>
              <button class="btn btn-success" type="button">Download</button>
            </div>
            <div class="column-downloaded">
              <p class="authors"><cite>By <a href="#">{{$service['author']}}</a></cite></p>
              <span>300+ Active Installs</span>
            </div>
          </div>
        </div>  
      </div>
            @endforeach
       
    @else
       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel">Quick Tutorial</h4>
                   </div>
                   <div class="modal-body text-center">
                       You have no Services, lets walkthrough a quick tutorial on how to create a Service. Click on Getting Started to begin.
                   </div>
                   <div class="modal-footer">
                       <a href="http://devless.io/#!/tutorial/" target="_blank" class="btn btn-primary">Getting Started</a>
                   </div>
               </div>
           </div>
       </div>
       <script>
           window.onload(function(){
               $('#myModal').modal('show');
           }());
           function getService(url) {
               httpGetAsync(url,function(data) {
                console.log('results', data)
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
            @endif
    </div>
</div><!--body wrapper end-->


@endsection
