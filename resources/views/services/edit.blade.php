@extends('layout')

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> Services / Edit #{{$service->id}}</h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('services.update', $service->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('name')) has-error @endif">
                       <label for="name-field">Name</label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ $service->name }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                       <label for="description-field">Description</label>
                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ $service->description }}</textarea>
                       @if($errors->has("description"))
                        <span class="help-block">{{ $errors->first("description") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
                       <label for="type-field">Type</label>
                    <input type="text" id="type-field" name="type" class="form-control" value="{{ $service->type }}"/>
                       @if($errors->has("type"))
                        <span class="help-block">{{ $errors->first("type") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('db_definition')) has-error @endif">
                       <label for="db_definition-field">Db_definition</label>
                    <input type="text" id="db_definition-field" name="db_definition" class="form-control" value="{{ $service->db_definition }}"/>
                       @if($errors->has("db_definition"))
                        <span class="help-block">{{ $errors->first("db_definition") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('script')) has-error @endif">
                       <label for="script-field">Script</label>
                    <textarea class="form-control" id="script-field" rows="3" name="script">{{ $service->script }}</textarea>
                       @if($errors->has("script"))
                        <span class="help-block">{{ $errors->first("script") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('pre_script')) has-error @endif">
                       <label for="pre_script-field">Pre_script</label>
                    <textarea class="form-control" id="pre_script-field" rows="3" name="pre_script">{{ $service->pre_script }}</textarea>
                       @if($errors->has("pre_script"))
                        <span class="help-block">{{ $errors->first("pre_script") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('post_script')) has-error @endif">
                       <label for="post_script-field">Post_script</label>
                    <textarea class="form-control" id="post_script-field" rows="3" name="post_script">{{ $service->post_script }}</textarea>
                       @if($errors->has("post_script"))
                        <span class="help-block">{{ $errors->first("post_script") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('pre_set')) has-error @endif">
                       <label for="pre_set-field">Pre_set</label>
                    <input type="text" id="pre_set-field" name="pre_set" class="form-control" value="{{ $service->pre_set }}"/>
                       @if($errors->has("pre_set"))
                        <span class="help-block">{{ $errors->first("pre_set") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('post_set')) has-error @endif">
                       <label for="post_set-field">Post_set</label>
                    <input type="text" id="post_set-field" name="post_set" class="form-control" value="{{ $service->post_set }}"/>
                       @if($errors->has("post_set"))
                        <span class="help-block">{{ $errors->first("post_set") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('active')) has-error @endif">
                       <label for="active-field">Active</label>
                    <input type="text" id="active-field" name="active" class="form-control" value="{{ $service->active }}"/>
                       @if($errors->has("active"))
                        <span class="help-block">{{ $errors->first("active") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('calls')) has-error @endif">
                       <label for="calls-field">Calls</label>
                    <input type="text" id="calls-field" name="calls" class="form-control" value="{{ $service->calls }}"/>
                       @if($errors->has("calls"))
                        <span class="help-block">{{ $errors->first("calls") }}</span>
                       @endif
                    </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{ route('services.index') }}"><i class="glyphicon glyphicon-backward"></i>  Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection