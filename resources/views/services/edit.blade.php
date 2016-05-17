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
   
<!-- page head start-->
            <div class="page-head">
                <h3>Service</h3><span class="sub-title">Welcome to
                Devless</span>
            </div><!-- page head end-->
            <!--body wrapper start-->
            <div class="wrapper no-pad">
                <div class="profile-desk">
                    <aside class="p-short-info">
                        <div class="widget">
                            <div class="title">
                                <h1>Service Details</h1>
                            </div>
                            <form action="{{ route('services.update', $service->id) }}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div  class="form-group @if($errors->has('name')) has-error @endif">
                                    <label for="name-field">Name</label>
                                    <input type="text" id="name-field" name="name" class="form-control" value="{{ $service->name }}"/>
                                     @if($errors->has("name"))
                                        <span class="help-block">{{ $errors->first("name") }}</span>
                                     @endif
                                </div>
                                 
                                
                                <div class="form-group">
                                    <label for="description-field">Description</label>
                                    <div class="">
                                        <textarea class="form-control" id="description-field" rows="3" name="description">{{ $service->description }}</textarea>
                                        @if($errors->has("description"))
                                        <span class="help-block">{{ $errors->first("description") }}</span>
                                        @endif
                                    </div>
                                </div><button class="btn btn-info" type=
                                "submit">Update</button>
                            </form>
                        </div>
                    </aside>
                    <aside class="p-aside">
                        <section class="isolate-tabs">
                            <header class="panel-heading tab-dark ">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a data-toggle="tab" href="#jus">Database</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" href="#mtab">Tables</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" href="#jtab">Script</a>
                                    </li>
                                </ul>
                            </header>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="jus">
                                        <form role="form">
                                            <div class="form-group">
                                                <label for="g-title">Name</label>
                                                <input class="form-control" id="database"
                                                placeholder="" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Database Type</label>
                                                <select id="db-type"  class="form-control m-b-10">
                                                    <option value="sqlite">SQLite</option>
                                                    <option value="mysql">MySQL</option>
                                                    <option value="">Postgress</option>
                                                    <option value="">SQL Server</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Host Name</label>
                                                <input class="form-control" id="hostname"
                                                placeholder="" type="text" value="127.0.0.1">
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Username</label>
                                                <input class="form-control" id="username"
                                                placeholder="" type="text" value="root">
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Password</label>
                                                <input class="form-control" id="password" placeholder="" type="password">
                                            </div>
                                            <button class="btn btn-info" type=
                                            "submit">Update</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="mtab">
                                                <section class="panel">
                    <header class="panel-heading head-border">
                        
                    </header>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Table Name</th>
                                    <th>Description</th>
                                    <th>#N0 of Fields</th>
                                    
                                    <th>options</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @if(sizeOf($table_meta) > 0)
                                    @foreach($table_meta as $table_data)
                                <tr>
                                    <th scope="row">1</th>
                                    <td>{{$table_data['name']}}</td>
                                    <td>{{$table_data['description']}}</td>
                                    <td>{{sizeOf($table_data['field'])}} field(s)</td>
                                    <td><button class="btn btn-default">View Data</button>
                                        <button class="btn btn-danger">Destroy Table</button></td>
                                </tr>
                                    @endforeach
                                 @else
                                <h3 class="text-center alert alert-info">Empty!</h3>    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
                                    </div>
                                    <div class="tab-pane" id="jtab">
<pre id="script-editor">
@if(!$service->script == "")
{{$service->script}}
@else
 echo "Happy scripting";
@endif
</pre>
<button class="btn btn-info" type="submit">Run</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div><!--body wrapper end-->



<!--body wrapper end-->
<script>
    //split db_definition and populate field

    function db_definition(){
        var db_cred = '{{$service->db_definition}}';
        var sub_db_definition = db_cred.split(",")
        for(i=0; i< sub_db_definition.length; i++){
            
           var db_cred =sub_db_definition[i].split("=");
           $('#'+db_cred[0]).val(db_cred[1]);
           if(db_cred[0] == "driver"){
               driver_type = db_cred[1].toLowerCase();
               $("#db-type").val(driver_type);
           }
           
        
        }
    }
    function init(){
        db_definition();
    }
    function destroy_table(table_name){
        var settings = {
      "async": true,
      "crossDomain": true,
      "url": "/api/v1/service/auth/db",
      "method": "DELETE",
      "headers": {
        "content-type": "application/json",
        "cache-control": "no-cache"
      },
      "processData": false,
      "data": "{  \n   \"resource\":[  \n      {  \n         \"name\":"+testtable+",\n         \"params\":[  \n            {  \n               \"drop\":\"true\"    \n            }\n         ]\n      }\n\n    ]\n}        "
    }

    $.ajax(settings).done(function (response) {
      console.log(response);
    });
    }
</script>    
@endsection