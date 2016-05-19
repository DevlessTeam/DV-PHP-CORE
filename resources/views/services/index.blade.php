@extends('layout')

@section('header')
<!-- page head start-->
<div class="page-head">
    <h3>Service</h3><span class="sub-title">Service/</span>
</div>
<!-- page head end-->

@endsection

@section('content')

<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
         <div class="col-lg-3 col-sm-6 m-b-30">
                <div class="panel panel-danger">
                    <div class="panel-header"><br><h4><center>Add Service</center></h4></div>
                    <div class="panel-body">
                        <a href="{{ route('services.create') }}">
                        <center><i class="fa fa-plus fa-5x" aria-hidden="true"></i></center>
                        </a>
                        <br>


                    </div>
                </div>

            </div>
        @if($services->count())
             @foreach($services as $service)
            <div class="col-lg-3 col-sm-6 m-b-30">
                <div class="panel panel-danger">
                    <div class="panel-header"><br><h4><center>{{substr(strtoupper($service->name),0,10)}}</center></h4></div>
                    <div class="panel-body">
                        <center>{{substr($service->description, 0, 20)}}@if(strlen($service->description)>20)...@endif</center>
                        <br><br>
                        <center>
                        <div class="btn-group" role="group">
                            <a type="button" href="{{ route('services.edit', $service->id) }}" class="btn btn-default">Edit</a>
                            <a type="button" href="/service/{{$service->name}}/views/index/" target="_blank" class="btn btn-default">Console</a>
                            <button type="button" class="btn btn-default">Delete</button>
                        </div>
                        </center>

                    </div>
                </div>

            </div>
            @endforeach
        {!! $services->render() !!}
            @endif
    </div>
</div><!--body wrapper end-->


@endsection

<!--

    <div class="row">
        <div class="col-md-12">
            @if($services->count())
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                        <th>DESCRIPTION</th>
                        <th>TYPE</th>
                        <th>DB_DEFINITION</th>
                        <th>SCRIPT</th>
                        <th>PRE_SCRIPT</th>
                        <th>POST_SCRIPT</th>
                        <th>PRE_SET</th>
                        <th>POST_SET</th>
                        <th>ACTIVE</th>
                        <th>CALLS</th>
                            <th class="text-right">OPTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>{{$service->id}}</td>
                                <td>{{$service->name}}</td>
                    <td>{{$service->description}}</td>
                    <td>{{$service->type}}</td>
                    <td>{{$service->db_definition}}</td>
                    <td>{{$service->script}}</td>
                    <td>{{$service->pre_script}}</td>
                    <td>{{$service->post_script}}</td>
                    <td>{{$service->pre_set}}</td>
                    <td>{{$service->post_set}}</td>
                    <td>{{$service->active}}</td>
                    <td>{{$service->calls}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-primary" href="{{ route('services.show', $service->id) }}"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                                    <a class="btn btn-xs btn-warning" href="{{ route('services.edit', $service->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $services->render() !!}
            @else
                <h3 class="text-center alert alert-info">Empty!</h3>
            @endif

        </div>
    </div>-->
