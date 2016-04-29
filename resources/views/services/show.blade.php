@extends('layout')
@section('header')
<div class="page-header">
        <h1>Services / Show #{{$service->id}}</h1>
        <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('services.edit', $service->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                     <label for="name">NAME</label>
                     <p class="form-control-static">{{$service->name}}</p>
                </div>
                    <div class="form-group">
                     <label for="description">DESCRIPTION</label>
                     <p class="form-control-static">{{$service->description}}</p>
                </div>
                    <div class="form-group">
                     <label for="type">TYPE</label>
                     <p class="form-control-static">{{$service->type}}</p>
                </div>
                    <div class="form-group">
                     <label for="db_definition">DB_DEFINITION</label>
                     <p class="form-control-static">{{$service->db_definition}}</p>
                </div>
                    <div class="form-group">
                     <label for="script">SCRIPT</label>
                     <p class="form-control-static">{{$service->script}}</p>
                </div>
                    <div class="form-group">
                     <label for="pre_script">PRE_SCRIPT</label>
                     <p class="form-control-static">{{$service->pre_script}}</p>
                </div>
                    <div class="form-group">
                     <label for="post_script">POST_SCRIPT</label>
                     <p class="form-control-static">{{$service->post_script}}</p>
                </div>
                    <div class="form-group">
                     <label for="pre_set">PRE_SET</label>
                     <p class="form-control-static">{{$service->pre_set}}</p>
                </div>
                    <div class="form-group">
                     <label for="post_set">POST_SET</label>
                     <p class="form-control-static">{{$service->post_set}}</p>
                </div>
                    <div class="form-group">
                     <label for="active">ACTIVE</label>
                     <p class="form-control-static">{{$service->active}}</p>
                </div>
                    <div class="form-group">
                     <label for="calls">CALLS</label>
                     <p class="form-control-static">{{$service->calls}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('services.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>

        </div>
    </div>

@endsection