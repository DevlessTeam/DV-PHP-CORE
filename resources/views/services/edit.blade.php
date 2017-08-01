@extends('layout')

@section('header')
   <!-- page head start-->
            <div class="page-head">
                <h3>Service</h3><span class="sub-title">{{ucwords($service->name)}}</span>
           </div><!-- page head end-->
           <div class="code-console center" style="margin-left: 50%;"></div>
@endsection

@section('content')
    @include('error')
    <!-- Edit Table Name and Description -->
    <div id="editTable" class="modal fade" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
                    <div class="form-group" id="addform">
                        <label><b>Name</b></label>
                        <input type="text" id="newTableName" class="form-control" name="tableName" placeholder="table name">
                        <label><b>Description</b></label>
                        <input type="text" id="newDesc" class="form-control" name="tableDesc" placeholder="table desc">
                        <input type="hidden" id="edit-serviceName">
                        <input type="hidden" id="edit-tableName">
                    </div>
                    <div class="pull-right">
                    <button  class="btn btn-default" onclick="updateTable()"><i class="fa fa-edit"></i> Update Table</button>
                    <button  data-dismiss="modal" class="btn btn-warning">Cancel</button>
                    </div>
            </div>
        </div>
        </div>
    </div>
<!-- End Edit Table Name and Description -->
    <!-- Add field to table -->
    <div id="addFieldToTable" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
                        <div class="form-group" id="addform">
                            <label for="heroku_users_urls_id"><b>New Field Name</b></label>
                            <input type="text" class="form-control" id="newFieldName" placeholder="Field Name">
                        </div>
                        <div  class="form-group">
                            <label for="field-type">Field Type</label>

                            <?php /*'REFERENCE'*/$options = ['TEXT','TEXTAREA','INTEGER','DECIMALS','PASSWORD','URL','EMAIL', 'BASE64'] ?>
                            <select class="form-control"  name="field-type" id="fieldType">
                                @foreach($options as  $option)
                                    <option value="{{$option}}">{{$option}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="add-serviceName">
                        <input type="hidden" id="add-tableName">
                        <div class="pull-right">
                            <button class="btn btn-default" onclick="addNewField()"><i class="fa fa-plus"></i> Add Field</button>
                            <button type="#" data-dismiss="modal" class="btn btn-warning">Cancel</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add field to table -->
     <!-- Edit Fields -->
    <div id="editFields" class="modal fade" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="container col-md-12 col-sm-12"  style="color: #000; background-color: #f3f3f3; padding: 10px;">
            <h4>Fields<h4>
                   <div class="input-group" id="fieldTemplate">
                       <input type="text" class="form-control" readonly name="user_bets" value="id">
                       <span class="input-group-btn">
                            <button class="btn btn-default" id="user_bets" disabled onclick="updateFieldName(this.id)" type="button">Update</button>
                            <button disabled class="btn btn-danger" id="user_bets" onclick="deleteFieldName(this.id)" type="button" >Delete</button>
                       </span>
                       <br>
                   </div>
                    <input type="hidden" id="ef-serviceName">
                    <input type="hidden" id="ef-tableName">
                    <div id="fieldList"></div>
                    <br>
                    <div class="pull-right">
                    <button  data-dismiss="modal" class="btn btn-warning">Cancel</button>
                    </div>
            </div>
        </div>
        </div>
    </div>
<div style="display:none;" id="script_url">
  {{ route('services.update', $service->id) }}</div>
<!-- End field edit -->
    <div id="schema-table" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Tables</h4>
      </div>
      <div class="modal-body">
          <form id="form">
         <div  class="form-group">
            <label for="name-field">Table Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Table Name"/>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
        </div>
          <HR>
          <center> Add Fields </center>

          <div class="removeIndicator" id="fields" >
            <div  class="form-group">
            <label for="name-field">Field Name</label>
            <button type="button" class="btn btn-danger pull-right" id="delete-field" onclick="destroy_field('removeIndicator')">Remove</button>
            <input type="text" id="field-name"  name="field-name" class="form-control" placeholder="Field Name"/>
             </div>
           <div  class="form-group">
            <label for="field-type">Field Type</label>

            <?php /*'REFERENCE'*/$options = ['TEXT','TEXTAREA','INTEGER','DECIMALS','PASSWORD','URL','EMAIL', 'BASE64', 'REFERENCE'] ?>
            <select class="form-control"  name="field-type" id="field-type">
                @foreach($options as  $option)
                <option value="{{$option}}">{{$option}}</option>
                @endforeach
            </select>
        </div>
        <div  class="form-group">
            <div style="display:block;" >
            <label forƒ="field-reference">Referenced Table</label>
            <select class="form-control"  name="field-reference" id="field-reference" >
                <option value="none" selected>None</option>
                @foreach($table_meta as $table_data)
                <option value="{{$table_data['name']}}">{{$table_data['name']}}</option>
                @endforeach
                <option value="_devless_users">DevLess User</option>
            </select>
            </div>
        </div>
          <div class="form-group">
           <label for="default-field">Default Value(optional)</label>
            <input type="text" id="default" name="default" class="form-control" />
           </div>

             <div class="form-group">
                <label for="option-field">Field Options</label>

                  <input type="checkbox" id="required"  name="required"/>REQUIRED?


                 <div style="display:none;">
                     <input type="checkbox" id="validate" name="validate"/>VALIDATE?
                 </div>


                    <input type="checkbox" id="unique" name="unique"/> UNIQUE FIELD?
            </div>
      </div>
              <div class="dynamic-space"></div>

      <div class="modal-footer">
          <button type="button" onclick="append_field()" class="btn btn-info pull-left" >Add a Field</button>
          <button type="button" id="crt-tbl" onclick="create_table('{{$service->name}}')" class="btn btn-info pull-right" >Create Table</button>
      </div>
      </div></form></div>
  </div>
</div>
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
                                    <input type="text" readonly id="name-field" name="name" class="form-control" value="{{ $service->name }}"/>
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
                                <button class="btn btn-info pull-right" type=
                                            "submit">Update</button>
                        </div>
                    </aside>
                    <aside class="p-aside">
                        <section class="isolate-tabs">
                            <header class="panel-heading tab-dark ">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a data-toggle="tab" href="#mtab">Tables</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" onclick="set_script()" href="#jtab">Rules<span id="saving" style="display:none;color:green;"><i class="fa fa-save"> saving...</i></span></a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" href="#jus">Remote DB Config</a>
                                    </li>
                                </ul>
                            </header>

                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="jus">
                                        <form role="form">
                                            <div class="form-group">
                                                <label for="g-title">Name</label>
                                                <input class="form-control" name="database" id="database"
                                                placeholder="Database Name" value="{{$service->database}}" type="text">

                                        @if($errors->has("database"))
                                        <span class="help-block">{{ $errors->first("database") }}</span>
                                        @endif
                                         </div>

                                            <div class="form-group">
                                                <label for="g-txt" >Database Type</label>
                                                <select id="db-type" name="driver"  class="form-control m-b-10">
                                                    <?php $options = ['Default'=>'default','Sqlite'=>'sqlite',
                                                        'MySql'=>'mysql','Postgres'=>'pgsql','SQL Server'=>'sqlsrv'];?>
                                                    @foreach($options as $option_index => $option_value )
                                                    <option value="{{$option_value}}" @if($option_value == $service->driver)selected @endif >{{$option_index}}</option>
                                                    @endforeach
                                                </select>
                                                 @if($errors->has("driver"))
                                                  <span class="help-block">{{ $errors->first("driver") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Host Name</label>
                                                <input class="form-control" name="hostname" id="hostname"
                                                       type="text" placeholder="127.0.0.1" value="{{$service->hostname}}">
                                                 @if($errors->has("hostname"))
                                                  <span class="help-block">{{ $errors->first("hostname") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Username</label>
                                                <input class="form-control" name="username" id="username"
                                                        type="text" placeholder="root" value="{{$service->username}}">
                                                @if($errors->has("username"))
                                                  <span class="help-block">{{ $errors->first("username") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Password</label>
                                                <input class="form-control" id="password" name="password" placeholder="password" value="{{$service->password}}" type="password">
                                                @if($errors->has("password"))
                                                  <span class="help-block">{{ $errors->first("password") }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="g-txt">Port</label>
                                                <input class="form-control" id="port" name="port" placeholder="port" value="{{$service->port}}" type="port">
                                                @if($errors->has("port"))
                                                  <span class="help-block">{{ $errors->first("port") }}</span>
                                                @endif
                                            </div>
                                            <button class="btn btn-info" type=
                                            "submit">Update</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane active " id="mtab">
                                                <section class="panel">
                    <header class="panel-heading head-border">

                    </header>
                    <div class="table-responsive" id="service-tables">
                        <table class="table">
                            <thead>
                                 @if(sizeOf($table_meta) > 0)
                                <tr>
                                    <th>#</th>
                                    <th>Table Name</th>
                                    <th>Description</th>
                                    <th>Fields</th>`

                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php $count = 0; ?>
                                    @foreach($table_meta as $table_data)
                                <tr id="{{$table_data['name']}}">
                                    <th scope="row">{{$count}}</th>
                                    <td><button id="editTable" onclick="tableFieldPopulation([{'name':'newTableName', 'value':'{{$table_data['name']}}'},{'name':'newDesc', 'value':'{{$table_data['description']}}'},{'name':'edit-serviceName', 'value':'{{$service->name}}'},{'name':'edit-tableName', 'value':'{{$table_data['name']}}'}])" data-toggle="modal" data-target="#editTable"  class="btn btn-default" title="{{$table_data['name']}}">{{substr($table_data['name'], 0, 25)}} <i class="fa fa-edit"></i></button></td>
                                    <td><button  onclick="tableFieldPopulation([{'name':'newTableName', 'value':'{{$table_data['name']}}'},{'name':'newDesc', 'value':'{{$table_data['description']}}'},{'name':'edit-serviceName', 'value':'{{$service->name}}'},{'name':'edit-tableName', 'value':'{{$table_data['name']}}'}])" class="btn btn-default" data-toggle="modal" data-target="#editTable" title="{{$table_data['description']}}">{{substr($table_data['description'], 0, 25)}} ...  <i class="fa fa-edit"></i></button></td>
                                    <td><button onclick="displayAllFields('{{$service->name}}','{{$table_data['name']}}')" class="btn btn-default" data-target="#editFields" data-toggle="modal" >{{sizeOf($table_data['field'])}} field(s) <i class="fa fa-edit"></i></button> </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4"><a class="btn btn-default" data-toggle="modal" data-target="#addFieldToTable" onclick="tableFieldPopulation([{'name':'add-serviceName', 'value':'{{$service->name}}'},{'name':'add-tableName', 'value':'{{$table_data['name']}}'}])"><i class="fa fa-plus"></i></a> </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                        <a href="/datatable?service_name={{$service->name}}&table_name={{$table_data['name']}}" class="btn btn-default"><i class="fa fa-table"></i></a>
                                            </div><div class="col-lg-4 col-md-4 col-sm-4">
                                                <a href="#" onclick="destroy_table('{{$table_data['name']}}','{{$service->name}}')" class="btn btn-danger delete-{{$table_data['name']}}"><i class="fa fa-trash"></i> </a>
                                            </div>
                                            </div>
                                    </td>
                                </tr>
                                    <?php $count++; ?>
                                    @endforeach
                                 @else
                                <h3 class="text-center alert alert-info">Empty!</h3>
                                @endif
                            </tbody>
                        </table>
                        <br>
                        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#schema-table"><i class="fa fa-table"></i> New Table</button>
                    </div>
                </section>
                                    </div>
                                                                   <div class="tab-pane" id="jtab">


<textarea  class="code-box" id="code-box" name="script" rows="20" style="width: 100%">
@if(strlen($service->raw_script) < 1)
  {{$service->script}}
@else
  {{$service->raw_script}}
@endif
</textarea>
                                                                       <br>

                                                                       <button class="btn btn-info " onclick="run_script()" type="button"> Save </button><br><br>
<span id="code-console" class="code-console" style="background-color:black;margin-left:14%;width:400px;height:300px;color:greenyellow">
   </span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div><!--body wrapper end-->

<!--body wrapper end-->
<script type="text/javascript">
    window.devless_edit_url = window.location.origin+'/services/'+{{$id}}+'/edit';
</script>

<script src="{{ Request::secure(Request::root()).'/js/service_edit.js' }}"></script>
@endsection
