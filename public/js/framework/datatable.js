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
    var Datatable;
    var metaForm;

    // Initiate table build
    function tableCall(table_entries) {
        var metas;

        $.get('/datatable/'+table_entries+'/metas', function(res) {
            metas = res;
            metaForm = metas;
            if (metas !== undefined) {
                $.get('/datatable/'+table_entries+'/entries', function(resp) {
                    $('#addbtn').prop("disabled", false);
                    navOption(resp, metas);
                })
            }
        })
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
    function buildHtmlTable(data, metaData) {
        const table = '<div class="table-responsive"><table id="dataOne" cellspacing="0" width="100%" class="display compact cell-border"><thead id="table_head"></thead><tbody id="table_body"></tbody></table></div>';
        $('.panel').append(table);
        var columns = addAllColumnHeaders(metaData);

        for(i = 0; i < data.length; i++) {
            table_bd = '<tr id="dtRow">';
            for(j = 0; j < columns.length; j++) {

                table_bd += '<td>'+data[i][columns[j]]+'</td>';
            }
            table_bd += '</tr>';
            $('#table_body').append(table_bd);
        }
        $('.loader').remove();
        Datatable = $('#dataOne').DataTable();
    }

    // Creation of table headers
    function addAllColumnHeaders(metas) {
        let table_head = '<tr>';
        let header = [];

        if ( metas === undefined) {
            alert('Please refresh your page. Xhr failed');
        }

        for (i=0; i< metas.length; i++){
            if( metas[i] !== 'devless_user_id'){
                header.push(metas[i]);
                table_head += '<th>'+metas[i].toUpperCase()+'</th>';
            }
        }

        table_head += '</tr>';
        $('#table_head').append(table_head);

        return header;
    }

    // Building of table
    function navOption(data, metas) {
        var entries = data;
        buildHtmlTable(entries, metas);
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
          for (var i = 2; i < metaForm.length; i++) {
                $('#formData').append("<label for='"+metaForm[i]+"'><b>"+metaForm[i].toUpperCase()+"</b></label><input type='text' class='form-control' name='"+metaForm[i]+"' id='"+metaForm[i]+"' value='"+c[i-1]+"'>");
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
          if(Datatable.data().length === 0){
                last_id = 0;
          } else {
                last_id = Datatable.data()[Datatable.data().length - 1][0];
          }

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
          for (var i = 2; i < metaForm.length; i++) {
            $('#addform').append('<label for="'+metaForm[i]+'"><b>'+metaForm[i].toUpperCase()+'</b></label><input type="text" class="form-control" name="'+metaForm[i]+'" id="'+metaForm[i]+'">');
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