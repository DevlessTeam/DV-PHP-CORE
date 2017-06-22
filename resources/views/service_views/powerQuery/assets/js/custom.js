var editor = ace.edit("editor");
var a_tag;
editor.getSession().setMode("ace/mode/json");

$('document').ready(function() {

    //reset editor and inputs
    function resetEditor() {
        editor.setValue('');
        $('input[name="id"]').val('');
        $('input[name="query_name"]').val('');
        $('#response').hide('slow');
    }
    
    editor.setValue(JSON.stringify({table: ["$table_name"], get: []},null, 4));

    // Handles click to retrieve saved query    
    $('a').click(function(e) {
       a_tag = this;
        var queryName = e.target.text;

        SDK.call('powerQuery', 'getQuery', [queryName], function(res){
            if(res.payload.result) {
                editor.setValue(JSON.stringify(JSON.parse(res.payload.result.query), null, 4));
                $('input[name="id"]').val(res.payload.result.id);
                $('input[name="query_name"]').val(res.payload.result.name);
            }
        });
    });

    //Update/Add new query
    $('#update_save').click(function(){
        var id = $('input[name="id"]').val();
        var query_name = $('input[name="query_name"]').val();
        var value = JSON.parse(editor.getValue());

        if(id !== "") {
            SDK.call('powerQuery', 'editStoredQuery', [query_name, value], function(resp){
                if(resp.payload.result){
                    alert('Query update successful!', '#32C867');
                } else {
                    alert('Query update failed!', '#EB3E28');
                }
            });
        } else {
            SDK.call('powerQuery', 'addQuery', [query_name, value], function(resp){
                console.log(resp, "output");
                if(resp.payload.result){
                    alert('Query added successfully!', '#32C867');
                    $('#empty').remove();
                    $('.list-item').append('<a href="#" class="list-group-item" id="">'+query_name+'<i class="fa fa-arrow-right pull-right"></i></a>');
                } else {
                    alert('Query add failed!', '#EB3E28');
                }
            })
        }
    });

    //Delete query
    $('#delete_btn').click(function() {
        var id = $('input[name="id"]').val();
        SDK.deleteData('powerQuery', 'queries', 'id', id, function(res) {
            if(res.status_code === 636) {
                $(a_tag).remove();
                resetEditor();        
                alert('Query delete successful!', '#32C867');
            } else {
                alert('Query delete failed', '#EB3E28');
            }
        })
    });

    //Query execution
    $('#exec_btn').click(function() {
        var value = JSON.parse(editor.getValue());

        SDK.call('powerQuery', 'execQuery', [value], function(res){
            if(res.payload.result) {
                alert('Query execution succesful', '#32C867');
                $('textarea').show('slow');
                $('html, body').animate({
                        scrollTop: $('textarea').offset().top
                }, 1000);
                $('#response').val(JSON.stringify(res.payload.result, null, 4));
            } else {
                alert('Query execution failed', '#EB3E28');
            }
        })
    });

    //Clears editor and input fields
    $('#clear_btn').click(function() {
        resetEditor();
    });

    window.alert = function(text, color) {
        $('#alertModal .modal-body').html('<p style="text-align: center; color: white;">'+text+'</p>');
        $('#alertModal .modal-body').css('background-color', color);
        $('#alertModal .modal-content').css('background-color', color);
        $('#alertModal').modal('show');
        setTimeout(function() {
            $('#alertModal').modal('hide');
        }, 2000);
    }

});