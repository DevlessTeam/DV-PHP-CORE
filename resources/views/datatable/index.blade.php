
@extends('layout')

@section('header')
      <div class="page-head">
        <h3>
            Data Table
        </h3>
        <button type="button" id="addbtn" class="btn btn-primary pull-right" style="position: relative; bottom: 23px;" disabled="true">Add Data</button>
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
                            <option value="{{$table->id}}">{{$table->table_name}}</option>
                        @endforeach
                    @else
                        <option disabled selected value> -- select a table -- </option>
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-12">
            <section class="panel">
                <table id="excelDataTable" class="schema-table table" width="100%" style="table-layout: fixed; word-wrap: break-word;">
                </table>
                <h3 id="empty_handler" class="text-center alert alert-info" style="margin: -5px;">Empty!</h3>
            </section>
            <nav id="page-nav">
                <ul class="pager">
                    <li class="previous"><a id="previous"><span aria-hidden="true">&larr;</span> Older</a></li>
                    <span class="badge" id="first_no"> </span> Out of <span class="badge" id="last_no"></span>
                    <li class="next"><a id="next">Newer <span aria-hidden="true">&rarr;</span></a></li>
                </ul>
            </nav>
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
      <div class="container col-md-12 col-sm-12" style="color: #000; background-color: #f3f3f3; padding: 10px;">
        <pre>
          <div id="error_display">

          </div>
        </pre>
      </div>
    </div>
  </div>
</div>

<script charset="utf-8">
window.onload(function() {

    document.getElementById('empty_handler').style.display = 'none';
    document.getElementById('page-nav').style.display = 'none';
    document.getElementById('previous').classList.add = 'disabled';

    $(window).load(function() {
        $('#page-nav').hide();
        $('.header-control').hide();
    });

    var entries;
    var nextUrl;
    var prevUrl;
    var metas;

    function tableCall(table_entries) {
        $.get('/datatable/'+table_entries+'/metas', function(response){
          metas = response;
        });

        $.get('/datatable/'+table_entries+'/entries', function(data) {
            $('#addbtn').prop("disabled", false);
            navOption(data);
            if (data.data.length == 0){
                $('#empty_handler').show('fast', function(){
                    $('.header-control').hide();
                    $('#page-nav').hide();
                });
            } else {
                $(window).load(function() {
                    $('#page-nav').show();
                });
            }

            $('#page-nav').show();

        });
    }

    if ($('#service option:selected').val() != '' && $('#table_name option:selected').val() != '') {
        var tb_name = $('#service option:selected').text() + '_' + $('#table_name option:selected').text();
        tableCall(tb_name);
    }

    var service_id;
    var service_name;
    var service_table;
    var c;
    $('#service').change(function() {
        service_id = $('#service').val();
        service_name = $('#service option:selected').text();
        console.log(service_name);
        $('#excelDataTable').html(' ');
        $('#empty_handler').hide('fast', function() {
            $('#page-nav').hide();
        });
        $('#table_name').find('option').remove().end().append('<option disabled selected value> -- select a table -- </option>');
        $.get('/datatable/'+service_id, function(data) {
            var tables = data;
            for (var i = 0; i < tables.length; i++) {
                $('#table_name').append('<option value="'+JSON.parse(tables[i].id)+'">'+JSON.parse(tables[i].schema).name+'</option>');
            }
        });
    });

    $('#table_name').change(function(data) {
        service_table = $('#table_name option:selected').text();
        var table_entries = service_name + '_' + service_table;
        $('#excelDataTable').html(' ');
        tableCall(table_entries);
    });

    function buildHtmlTable() {
        var columns = addAllColumnHeaders(entries);

        for (var i = 0 ; i < entries.length ; i++) {
            var row$ = $('<tr/>').addClass('details');
            row$.append($('<td/>'));
            for (var colIndex = 0 ; colIndex < columns.length ; colIndex++) {
                var cellValue = entries[i][columns[colIndex]];

                row$.append($('<td/>').html(cellValue));
            }
            $("#excelDataTable").append(row$);
        }
    }

    function addAllColumnHeaders(entries) {
        var columnSet = [];
        var headerTr$ = $('<tr/>');
        headerTr$.append($('<th/>'));

        for (var i = 0 ; i < entries.length ; i++) {
            var rowHash = entries[i];
            for (var key in rowHash) {
                if ($.inArray(key, columnSet) == -1){
                    columnSet.push(key);
                    headerTr$.append($('<th/>').html(key.toUpperCase()));
                }
            }
        }
        $("#excelDataTable").append(headerTr$);

        return columnSet;
    }

    $('#previous').click(function(){
        $.get(prevUrl, function(data) {
            $('#excelDataTable').html(' ');
            navOption(data);
        });
    });

    $('#next').click(function() {
        $.get(nextUrl, function(data) {
            $('#excelDataTable').html(' ');
            navOption(data);
        });
    });

    function navOption(data) {
        entries = data.data;
        prevUrl = data.prev_page_url;
        nextUrl = data.next_page_url;
        $('#first_no').html(data.current_page);
        $('#last_no').html(data.last_page);
        buildHtmlTable();
        checkPage(data);
    }

    function checkPage(data) {
        if (data.from == data.last_page) {
            $('#previous').addClass('disabled');
            $('#next').addClass('disabled');
        } else if (data.current_page == data.last_page) {
            $('#previous').removeClass('disabled');
            $('#next').addClass('disabled');
        } else if (data.current_page == data.from ) {
            $('#previous').addClass('disabled');
            $('#next').removeClass('disabled');
        } else {
            $('#next').removeClass('disabled');
            $('#previous').removeClass('disabled');
        }
    }

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


    $(document).on('click', '.details', function () {

      c = $(this).find('td').map(function(){
        return $(this).html();
      }).get();
      console.log(c);

      $(function modal() {
          $('#flash_msg').modal({show: true, backdrop: 'static'});
          $('#formData').html(" ");
          for (var i = 2; i < metas.length; i++) {
            $('#formData').append('<label for="'+metas[i]+'"><b>'+metas[i].toUpperCase()+'</b></label><input type="text" class="form-control" name="'+metas[i]+'" id="'+metas[i]+'" value="'+c[i+1]+'">');
          }
      });
      jQExtn();
    })

    var form_value;
    $(function() {
        $('form').submit(function(e) {
          e.preventDefault();
          payload = $('form').serializeObject();

          switch ($(this).find("button:focus")[0].innerText) {
            case "Cancel":
                $('#add_form').modal('hide');
                $('#flash_msg').modal('hide');
                break;
            case "Submit":
                var info = {resource:[{name:service_table, field: [payload]}]};
                $.post("api/v1/service/"+service_name+"/db", info).success(function(data){
                  console.log(data);
                  if(data.status_code === 609){
                    location.reload();
                  } else {
                    $('#add_form').modal('hide');
                    $('#flash_msg').modal('hide');
                    $('#error_flash').modal('show');
                    $('#error_display').text(JSON.stringify(data.message));
                  }
                });
                break;
            case "Update":
                var info = {resource:[{name:service_table, params: [{where: "id,"+c[1], data:[payload]}]}]};
                $.ajax({
                    url: "api/v1/service/"+service_name+"/db",
                    type: "PATCH",
                    data: info
                })
                .done(function(data) {
                    if(data.status_code === 619){
                      location.reload();
                    } else {
                      $('#add_form').modal('hide');
                      $('#flash_msg').modal('hide');
                      $('#error_flash').modal('show');
                      $('#error_display').text(JSON.stringify(data.message));
                    }
                });
                break;
            case "Delete":
                var info = {resource:[{name:service_table, params: [{delete:true, where: "id,"+c[1]}]}]};
                console.log('payload', info);
                $.ajax({
                    url: "api/v1/service/"+service_name+"/db",
                    type: "DELETE",
                    data: info
                })
                .done(function(data) {
                  console.log(data);
                  if(data.status_code === 636){
                    location.reload();
                  } else {
                    $('#add_form').modal('hide');
                    $('#flash_msg').modal('hide');
                    $('#error_flash').modal('show');
                    $('#error_display').text(JSON.stringify(data));
                  }
                });
                break;
          }

            return false;
        });
    });

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

}());

</script>
@endsection
