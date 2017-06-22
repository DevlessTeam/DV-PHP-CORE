@extends('layout')

@section('header')
<!-- page head start-->
<div class="page-head">
    <h3>Services</h3><span class="sub-title">Service/</span>
</div>
<!-- page head end-->

@endsection

@section('content')

<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
         <div class="col-lg-3 col-sm-6 m-b-30">
                <div class="panel panel-danger">
                     <a href="{{ route('services.create') }}">
                    <div class="panel-header"><br><h4><center>Create Service</center></h4></div>
                    <div class="panel-body">

                        <center><i class="fa fa-plus fa-5x" aria-hidden="true"></i></center>

                        <br>


                    </div>
                    </a>
                </div>

            </div>
        {{-- @if($services->count()) --}}
             @foreach($services as $service)
             <a href="{{ route('services.edit', $service->id) }}">
            <div class="col-lg-3 col-sm-6 m-b-30">
                <div class="panel panel-danger">
                    <div class="panel-header"><br><h4><center>{{substr(strtoupper($service->name),0,10)}}</center></h4></div>
                    <div class="panel-body" >
                        <center><span title="{{$service->description}}"</span>{{substr($service->description, 0, 20)}}@if(strlen($service->description)>20)...@endif</center>
                        <br><br>
                        <center>

                        <div class="btn-group" role="group">
                            <a type="button" href="{{ route('services.edit', $service->id) }}" class="btn btn-default">Edit</a>
                            <a type="button" href="/service/{{$service->name}}/view/index/" target="_blank" class="btn btn-default">Docs:UI</a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                  style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false }">
                                <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-default">Delete</button>
                                    </form>

                        </div>
                        </center>

                    </div>
                </div>

            </div>
             </a>
            @endforeach
        {!! $services->render() !!}
    {{-- @else
       <div class="modal fade" id="quick-guide" tabindex="-1" role="dialog" aria-labelledby="quickGuideLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Quick Guide</h4>
                   </div>
                   <div class="modal-body">
                        <ol>
                            <li><a href="{{URL('/services')}}"> Create a service</a> with the name <code>addressbook.</code><sub><a href="#"  data-toggle="tooltip" title="A Service is a lego peice that represents a part/feature. Many of these pieces come together to complete your app. EG: Image uploader, Payment Service etc." data-placement="bottom">what is a service?</a></sub></li><br>
                            <li>On the <code>addressbook</code> service page click on <button class="btn btn-info disabled"><i class="fa fa-table"> New Table</i> </button> to add a table with the <br><br>name <code>addresses</code> and fields <code>name, email, location. </code></li>
                            <br>
                            <li><a href="#" onclick="copyToBoard(document.getElementById('sample-frontend').innerHTML)">Click here </a>for sample frontend code, copy and paste in a <code>sample.html</code> file.</li>
                            <br>
                            <li><a href="{{URL('/app')}}">Head over to App Tab</a>, then click on <button class="btn btn-info disabled">Connect to my App</button> at the bottom to get the connection details.</li>
                            <br>
                            <li>Load <code>sample.html</code> in your browser of choice.</li>
                        </ol>
                     </div>
                   <div class="modal-footer">
                        <a href="#" target="_blank" class="btn btn-primary">Watch it all <i class="fa fa-video-camera"></i></a>
                       <a href="https://docs.devless.io/docs/1.0/html-sdk" target="_blank" class="btn btn-primary">Learn More <i class="fa fa-book"></i></a>

                   </div>
               </div>
           </div>
       </div>   
       <script>
           window.onload(function(){
               $('#quick-guide').modal('show');
           }());
       </script>
            @endif --}}
    </div>
</div><!--body wrapper end-->


@endsection
