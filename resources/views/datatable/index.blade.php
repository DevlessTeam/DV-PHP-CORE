
@extends('layout')

@section('header')
      <div class="page-head">
        <h3>
            Data Table
        </h3>
        <button type="button" id="addbtn" class="btn btn-primary pull-right" style="position: relative; bottom: 23px;" disabled="true"><i class="fa fa-plus"></i> Add Data</button>
        <span class="sub-title">Data Table/</span>
    </div>
@endsection

@section('content')
<style media="screen">
table {
    table-layout: fixed;
    width: 300px;
}

td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#dtRow {
    cursor: pointer;
}
</style>

<div class="wrapper">
    <div class="row">
        <div id="datatable">
            <label for="service" class="col-lg-2 col-sm-2 control-label">Service </label>
            <div class="col-md-3">
                <select id="service" name="service" class="form-control m-b-10">
                    @if(\Request::has('service_name'))
                        <option value="{{$service->id}}">{{$service->name}}</option>
                    @else
                        <option disabled selected value> -- select a service -- </option>
                        @foreach($services as $service)
                            <option value="{{$service->id}}">{{$service->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <label for="table_name" class="col-sm-2 control-label col-md-offset-1">Table </label>
            <div class="col-md-3">
                <select id="table_name" name="table_name" class="form-control m-b-10">
                    @if(\Request::has('table_name'))
                        @foreach($tables as $table)
                            <option value="{{$table->id}}">{{json_decode($table->schema)->name}}</option>
                        @endforeach
                    @else
                        <option disabled selected value> -- select a table -- </option>
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-12">
            <section class="panel"></section>
        </div>
    </div>
</div>

<div id="flash_msg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
        <form id="modalform" action="" method="post">
          <div class="form-group" id="formData">
          </div>
          <button type="submit" class="btn btn-default">Update</button>
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="submit" class="btn btn-warning" id="cancel">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="add_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
        <form id="modaladd" action="" method="post">
          <div class="form-group" id="addform">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
          <button type="submit" class="btn btn-warning">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="error_flash" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <textarea id="error_display" cols="30" rows="5" wrap="hard"></textarea>
    </div>
  </div>
</div>

<script charset="utf-8">
window.onload(function() {

    var c;
    var payload;
    var last_id;
    var module_id;
    var element_id;
    var module_name;
    var module_table;

    $(window).load(function() {
        
        /* Handles Service and table name build when view data is click from the Service Panel */        
        if ($('#service option:selected').val() != '' && $('#table_name option:selected').val() != '') {
            var tb_name = $('#service option:selected').text() + '_' + $('#table_name option:selected').text();
            module_name = $('#service option:selected').text();
            tableCall(tb_name);
        }
    });

    var entries;
    var metas;
    let Datatable;

    // Initiate table build
    function tableCall(table_entries) {
        $.get('/datatable/'+table_entries+'/metas', function(response){
            delete response[1];
            metas = response;
        });

        $.get('/datatable/'+table_entries+'/entries', function(resp) {
            $('#addbtn').prop("disabled", false);
            navOption(resp);

        });
    }

    // Ajax to retrieve table names and append it to the DOM on module name change
    $('#service').change(function() {
        module_id = $('#service').val();
        module_name = $('#service option:selected').text();
        
        $('#table_name').find('option').remove().end().append('<option disabled selected value> -- select a table -- </option>');
        $.get('/datatable/'+module_id, function(data) {
            var tables = data;
            for (var i = 0; i < tables.length; i++) {
                $('#table_name').append('<option value="'+JSON.parse(tables[i].id)+'">'+JSON.parse(tables[i].schema).name+'</option>');
            }
        });
    });

    // setting module table name when viewing from module edit
    if (window.location.search !== '') {
        module_table = $('#table_name option:selected').text();
    }

    // Handles removal of table from the DOM on table option change
    $('#table_name').change(function(data) {
        module_table = $('#table_name option:selected').text();
        var table_entries = module_name + '_' + module_table;
        $('#dataOne').remove();
        $('#dataOne_wrapper').remove();
        tableCall(table_entries);
    });

    // Handle table creation with row & columns
    function buildHtmlTable() {
        const table = '<table id="dataOne" cellspacing="0" width="100%" class="display compact"><thead id="table_head"></thead><tbody id="table_body"></tbody></table>';
        $('.panel').append(table);
        var columns = addAllColumnHeaders(entries);

        for(i = 0; i < entries.length; i++) {
            table_bd = '<tr id="dtRow">';
            for(j = 0; j < columns.length; j++) {

                table_bd += '<td>'+entries[i][columns[j]]+'</td>';
            }
            table_bd += '</tr>';
            $('#table_body').append(table_bd);
        }

        Datatable = $('table').DataTable();
    }

    // Creation of table headers
    function addAllColumnHeaders(entries) {
        let table_head = '<tr>';
        let header = [];

        metas.map((v, i) => {
            if (v !== 'devless_user_id'){
                header.push(v)
                table_head += '<th>'+v.toUpperCase()+'</th>';
            }
        });
        
        table_head += '</tr>';
        $('#table_head').append(table_head);

        return header;
    }

    // Building of table
    function navOption(data) {
        entries = data;
        buildHtmlTable();
    }

    // Code snippet for converting form data into an object (key & value)
    function jQExtn() {
      $.fn.serializeObject = function()
      {
        var obj = {};
        var arr = this.serializeArray();
        $.each(arr, function() {
          if (obj[this.name] !== undefined) {
            if (!obj[this.name].push) {
              obj[this.name] = [obj[this.name]];
            }
            obj[this.name].push(this.value || '');
          } else {
            obj[this.name] = this.value || '';
          }
        });
        return obj;
      };
    }

    // Handles the form creation with data when a row is clicked
    $(document).on('click', '#dtRow', function () {
        // grab row id 
        element_id = $(this).find('tr').context._DT_RowIndex;  

        c = $(this).find('td').map(function(){
            return $(this).html();
        }).get();

      $(function modal() {
          $('#flash_msg').modal({show: true, backdrop: 'static'});
          $('#formData').html(" ");
          for (var i = 2; i < metas.length; i++) {
            $('#formData').append('<label for="'+metas[i]+'"><b>'+metas[i].toUpperCase()+'</b></label><input type="text" class="form-control" name="'+metas[i]+'" id="'+metas[i]+'" value="'+c[i-1]+'">');
          }
      });
      jQExtn();
    })

    // Handle submission of data to the backend
    $(function() {
        $('form').submit(function(e) {
          e.preventDefault();
          payload = $(this).serializeObject();
          // Grabs the last id in the table & increases it
          last_id = Datatable.data()[Datatable.data().length - 1][0];
          table_array = [parseInt(last_id)+1];

          // Grabs values from the payload (form data) and push them into an array for DataTable library
          $.map(payload, function(v, i) {
              table_array.push(v);
          });

          switch ($(this).find("button:focus")[0].innerText) {
            case "Cancel":
                alertHandle();
                break;
            case "Submit":
                var info = {resource:[{name:module_table, field: [payload]}]};
                $.post("api/v1/service/"+module_name+"/db", info).success(function(data){
                    alertHandle();
                    if(data.status_code === 609){
                        Datatable.row.add(table_array).draw();
                        row_index = Datatable.row([Datatable.data().length - 1]);
                        new_row = $('#dataOne').DataTable().row(row_index).node();
                        $(new_row).attr('id', 'dtRow');
                    } else {
                        $('#error_flash').modal('show');
                        $('#error_display').text(JSON.stringify(data.message));
                    }
                });
                break;
            case "Update":
                var info = {resource:[{name:module_table, params: [{where: "id,"+c[0], data:[payload]}]}]};
                
                // Grab id from the row since it doesn't need to be changed during update
                update_array = [Datatable.row(element_id).data()[0]];
                // Push data into array for the row to be updated
                $.map(payload, function(v, i) {
                    update_array.push(v);
                });

                $.ajax({
                    url: "api/v1/service/"+module_name+"/db",
                    type: "PATCH",
                    data: info
                })
                .done(function(data) {
                    alertHandle();
                    if(data.status_code === 619){
                        Datatable.row(element_id).data(update_array);
                    } else {
                      $('#error_flash').modal('show');
                      $('#error_display').text(JSON.stringify(data.message));
                    }
                });
                break;
            case "Delete":
                var info = {resource:[{name:module_table, params: [{delete:true, where: "id,"+c[0]}]}]};
                $.ajax({
                    url: "api/v1/service/"+module_name+"/db",
                    type: "DELETE",
                    data: info
                })
                .done(function(data) {
                    alertHandle();
                    if(data.status_code === 636){
                        Datatable.row(element_id).remove().draw();
                    } else {
                        $('#error_flash').modal('show');
                        $('#error_display').text(JSON.stringify(data));
                    }
                });
                break;
          }

            return false;
        });
    });

    // Handles form creation when the add btn is clicked
    $('#addbtn').click(function(){
      $(function modal() {
          $('#add_form').modal({show: true, backdrop: 'static'});

          $('#addform').html(" ");
          for (var i = 2; i < metas.length; i++) {
            $('#addform').append('<label for="'+metas[i]+'"><b>'+metas[i].toUpperCase()+'</b></label><input type="text" class="form-control" name="'+metas[i]+'" id="'+metas[i]+'">');
          }
      });
      jQExtn();
    });

    // Hides form modal
    function alertHandle() {
        $('#formData').html(" ");
        $('#add_form').modal('hide');
        $('#flash_msg').modal('hide');
    }

}());

</script>
@endsection
