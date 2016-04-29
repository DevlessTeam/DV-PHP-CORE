@extends('layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Services
            <a class="btn btn-success pull-right" href="{{ route('services.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('content')
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
    </div>

@endsection