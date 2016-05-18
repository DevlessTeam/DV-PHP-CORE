@extends('layout')

@section('header')
   <!-- page head start-->
            <div class="page-head">
                <h3>Service</h3><span class="sub-title">Welcome to
                Devless</span>
@endsection

@section('content')
    @include('error')

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
                                 <div  class="form-group @if($errors->has('active')) has-error @endif">
                                    <label for="active-field">State</label>
                                    <?php $options = ['Inactive','Active'] ?>
                                    <select class="form-control"  name="active" id="active-field">
                                        @foreach($options as $option_index => $option)
                                        <option @if($service->active == $option_index )selected @endif value="{{$option_index}}">{{$option}}</option>
                                        @endforeach
                                    </select>
                                     @if($errors->has("active"))
                                        <span class="help-block">{{ $errors->first("active") }}</span>
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
                                </div>
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
                                                <input class="form-control" name="database" id="database"
                                                placeholder="" type="text">
                                                
                                        @if($errors->has("database"))
                                        <span class="help-block">{{ $errors->first("database") }}</span>
                                        @endif
                                         </div>
                                            
                                            <div class="form-group">
                                                <label for="g-txt" >Database Type</label>
                                                <select id="db-type" name="driver"  class="form-control m-b-10">
                                                    <option value="sqlite">SQLite</option>
                                                    <option value="mysql">MySQL</option>
                                                    <option value="">Postgress</option>
                                                    <option value="">SQL Server</option>
                                                </select>
                                                 @if($errors->has("driver"))
                                                  <span class="help-block">{{ $errors->first("driver") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Host Name</label>
                                                <input class="form-control" name="hostname" id="hostname"
                                                placeholder="" type="text" value="127.0.0.1">
                                                 @if($errors->has("hostname"))
                                                  <span class="help-block">{{ $errors->first("hostname") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Username</label>
                                                <input class="form-control" name="username" id="username"
                                                placeholder="" type="text" value="root">
                                                @if($errors->has("username"))
                                                  <span class="help-block">{{ $errors->first("username") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Password</label>
                                                <input class="form-control" id="password"name="password" placeholder="" type="password">
                                                @if($errors->has("password"))
                                                  <span class="help-block">{{ $errors->first("password") }}</span>
                                                @endif
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
                                 @if(sizeOf($table_meta) > 0)
                                <tr>
                                    <th>#</th>
                                    <th>Table Name</th>
                                    <th>Description</th>
                                    <th>#N0 of Fields</th>
                                    
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    @foreach($table_meta as $table_data)
                                <tr id="{{$table_data['name']}}">
                                    <th scope="row">1</th>
                                    <td>{{$table_data['name']}}</td>
                                    <td>{{$table_data['description']}}</td>
                                    <td>{{sizeOf($table_data['field'])}} field(s)</td>
                                    <td><button class="btn btn-default">View Data</button>
                                        <button onclick="destroy_table('{{$table_data['name']}}','{{$service->name}}')" class="btn btn-danger">Destroy Table</button></td>
                                </tr>
                                    @endforeach
                                 @else
                                <h3 class="text-center alert alert-info">Empty!</h3>    
                                @endif
                            </tbody>
                        </table>
                         <button class="btn btn-info " type="button" >New Table  </button>
                    </div>
                </section>
                                    </div>
                                                                   <div class="tab-pane" id="jtab">

                                                                       
<textarea class="code-area" name="script" rows="20" style="width: 100%">

@if(!$service->script == "")
<?php echo "<?php \n"; ?>
{{$service->script}}
@else
<?php echo "<?php \n ";  ?> 
 echo "Happy scripting";
@endif
</textarea>
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
        $('.code-area').ace({ theme: 'github', lang: 'php' })
    }
    function destroy_table(table_name, service_name){
            var settings = {
           "async": true,
           "crossDomain": true,
           "url": "/api/v1/service/"+service_name+"/db",
           "method": "DELETE",
           "headers": {
             "content-type": "application/json",
             "cache-control": "no-cache",
           },
           "processData": false,
            "data": "{\"resource\":[{\"name\":\""+table_name+"\",\"params\":[{\"drop\":\"true\"}]}]}"
           }

         $.ajax(settings).done(function (response) {
           console.log(response);
           response_object = JSON.parse(response);
           status_code = response_object.status_code;
           if (status_code == 613) {
                $("").fadeOut();
           }
           else
           {
               alert('could not delete table ');
           }
         });
    }
</script> 
@endsection