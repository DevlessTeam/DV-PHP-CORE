@extends('layout')
@section('header')
<div class="page-header">
        <h1>ServiceMigrations / Show #{{$service_migration->id}}</h1>
        <form action="{{ route('service_migrations.destroy', $service_migration->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('service_migrations.edit', $service_migration->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static"></p>
                </div>
                <div class="form-group">
                     <label for="service_name">SERVICE_NAME</label>
                     <p class="form-control-static">{{$service_migration->service_name}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('service_migrations.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection