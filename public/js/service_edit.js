//page init function
    function init(){
        window.count = 0;
        window.main_old_fields =  $('.removeIndicator').clone();
        window.schema_json = {"resource":[{"name":"","description":"","field":[]}]  }
    }
    //destroy table
    function destroy_table(table_name, service_name){
        if(confirm('Are you sure you want to delete '+table_name+' table?')){
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
         $(".delete-"+table_name)[0].innerText = "...";
         $.ajax(settings).done(function (response) {
             console.log(response)
           status_code = response.status_code;
           if (status_code != 613) {
             alert('could not delete table ');
           }
           partialUpdate(['service-tables']);

         });}
    }
  function append_field(){
    field_names = ['name', 'description', 'field-name', 'field-type', 'field-reference',
                'default','required', 'validate', 'unique'];
    old_fields = window.main_old_fields.clone();
          field_names.forEach(
    function(i){
              field_name = i+window.count;
              old_fields.find('#'+i).attr('name', field_name ).attr('id', field_name );
         }
    )
    new_fields = old_fields;
    $( ".dynamic-space").append(new_fields);
    old_fields.attr('class', 'fields'+window.count);
    old_fields.contents().each(function () {
        if (this.nodeType === 3) this.nodeValue = $.trim($(this).text()).replace(/removeIndicator/g, "fields"+window.count)
        if (this.nodeType === 1) $(this).html( $(this).html().replace(/removeIndicator/g, "fields"+window.count) )
        })
    window.count = window.count + 1 ;

    }

    function create_table(service_name){
         $('#crt-tbl').prop('disabled', true);
         $.fn.serializeObject = function()
        {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        object = $('#form').serializeObject();
        var array = $.map(object, function(value, index) {
        return [value];
        });
        count = 0
        jQuery(
            function($)
            {
              count = 0 ;
              form_array = [];
              $.each($('#form')[0].elements,
                     function(i,o)
                     {
                      var _this=$(o);
                      field_id = _this.attr('id');
                       if(typeof field_id == "string" && field_id.indexOf("validate")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }
                       else if(typeof field_id == "string" && field_id.indexOf("required")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }
                       else if(typeof field_id == "string" && field_id.indexOf("unique")>= 0){
                           form_array[count] = $('#'+_this.attr('id')).is(':checked');
                       }else{
                           form_array[count] = $('#'+_this.attr('id')).val();
                       }
                    count++;
                     })
            }
          );
            if(form_array.length > 4){
                function trim(str){
                    console.log(str);
                    if(typeof str == "string"){

                        return str.replace(/\s+/g, '').toLowerCase();
                    }else{
                        return str;
                    }
                }
                window.schema_json.resource[0].name = trim(form_array[0]);
                window.schema_json.resource[0].description = form_array[1]  ;
                var len = ((form_array.length)-4)/8;

                for (var i = 1; i <= len; i++) {
                    position = ((len-i)*8)
                    if(form_array[6+position] == ""){ _default = null;}else{_default = form_array[6+position]; }
                    console.log("field tpe", trim(form_array[4+position]));
                    if(trim(form_array[4+position]) == "reference")
                    {
                        console.log('appended service')
                        referenced_table_name = service_name+'_'+trim(form_array[5+position])+'_id';

                    }else{
                        referenced_table_name = trim(form_array[3+position]);
                        console.log('went for else instead')
                    }
                    console.log('ref after passing', referenced_table_name);
                    window.schema_json.resource[0].field[i-1] = {
                        "name":    referenced_table_name,
                        "field_type":trim(form_array[4+position]),
                        "ref_table":trim(form_array[5+position]),
                        "default":_default,
                        "required":trim(form_array[7+position]),
                        "validation":trim(form_array[8+position]),
                        "is_unique":trim(form_array[9+position]),
                     };
                }

                if (len => 1) {
                   table_schema =   JSON.stringify(window.schema_json);
                   var settings = {
                    "async": true,
                    "crossDomain": true,
                    "url": "/api/v1/service/"+service_name+"/schema",
                    "method": "POST",
                    "headers": {
                      "content-type": "application/json",
                      "cache-control": "no-cache",
                    },
                    "processData": false,
                    "data": table_schema
                  }
                $.ajax(settings).done(function (response) {
                  console.log(response);
                  if(typeof(response) == "string")
                  {
                      response = JSON.parse(response);
                  }
                  var status_code = response.status_code;
                  var message = response.message;
                  var payload = response.payload;

                  if(status_code == 700){
                      console.log(message)
                      alert(message);
                      $('#crt-tbl').prop('disabled', false);
                  }
                  else if(status_code == 606){
                          window.location.href = window.devless_edit_url;
//                        partialUpdate(['service-tables']);
//                        $('#schema-table').click()
                  }else{
                        alert(message);

                  }
                });} else {

                     alert('Please add at least a field');
                     $('#crt-tbl').prop('disabled', false);
                }
            }
            else{
                alert('Sorry seems like you have no fields set ');
                $('#crt-tbl').prop('disabled', false);
            }


    }
   function destroy_field(field_id){
       $('.'+field_id).remove();
   }
   function set_script(){
       setTimeout(function(){ 
          $('.code-box').ace({ theme: 'github', lang: 'php', 
         
        });
        var editor = $('.code-box').data('ace');
        // var aceInstance = editor.editor.ace;
        // console.log(aceInstance);

       }, 1);
   }
   function run_script(){
       var form = new FormData();
form.append("call_type", "solo");
form.append("script", $('.code-box').val());
form.append("_method", "PUT");
var script_url = $('#script_url')[0].textContent;
var settings = {
  "async": true,
  "crossDomain": true,
  "url": script_url,
  "method": "POST",
  "headers": {
    "cache-control": "no-cache",
  },
  "processData": false,
  "contentType": false,
  "mimeType": "multipart/form-data",
  "data": form
}
$('#saving')[0].style.display = 'block';
$.ajax(settings).done(function (response) {
  result = JSON.parse(response);
    $('#saving')[0].style.display = 'none';
   (result.status_code == 626)?$('.code-console').css('color','green')
 : $('.code-console').css('color','red');
  $('.code-console').html('<font size="3">'+result.message+'</font>');
  if(result.status_code == 626){
      setTimeout(function(){
        $('.code-console').html('');
    }, 950);
  }
  
});
   }



//save script
document.addEventListener("keydown", function(e) {
    if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        e.preventDefault();
        run_script();
    }
});

function partialUpdate(ids) {
    $.each(ids, function(index, id){
        $('#'+id).load(document.URL +  ' #'+id);
    });

}

function tableFieldPopulation(fields) {
	for (var i=0; fields.length > i; i++) {
		$('#'+fields[i].name)[0].value = fields[i].value;
	}
}

function updateTable() {
	var service = $('#edit-serviceName')[0].value;
	var table 	= $('#edit-tableName')[0].value;
	var newTableName = $('#newTableName')[0].value;
	var newDesc = $('#newDesc')[0].value;
	var action 	= 'updateTable';
	var params  = newTableName+'-:-'+newDesc;
	SchemaEdit(action, service, table, params);
}

function addNewField() {
	var service = $('#add-serviceName')[0].value;
	var table 	= $('#add-tableName')[0].value;
	var newFieldName = $('#newFieldName')[0].value;
	var fieldType = $('#fieldType')[0].value;
	var action 	= 'addField';
	var params  = newFieldName+'-:-'+fieldType;
	SchemaEdit(action, service, table, params);
}

function updateFieldName(id) {
	var service = $('#ef-serviceName')[0].value;
	var table 	= $('#ef-tableName')[0].value;
	var newFieldName = $("input[name="+id+"]").val();
	var oldFieldName = id;
	var action 	= 'updateFieldName';
	var params  = oldFieldName+'-:-'+newFieldName;
	SchemaEdit(action, service, table, params);
}


function deleteFieldName(id) {
	var service = $('#ef-serviceName')[0].value;
	var table 	= $('#ef-tableName')[0].value;
	var fieldName = id;
	var action 	= 'deleteField';
	var params  = fieldName;
	SchemaEdit(action, service, table, params);
}
function displayAllFields(service, table) {
	var comb_tableName = service+'_'+table;
  var url = window.location.origin;
	$('#ef-serviceName')[0].value = service;
	$('#ef-tableName')[0].value = table;
  $.get(url+'/datatable/'+service+'_'+table+'/metas', 
		function(response){
      $('#fieldList')[0].textContent = '';
      response = response.reverse();
			for(var i = 0; response.length > i; i++) {
				if(response[i] !== 'devless_user_id' && response[i] !== 'id') {
					var field = $('#fieldTemplate')[0].cloneNode(true, true);
					field.id = Math.random();
					$(field).find("input[name=user_bets]")[0].value = response[i];
          $(field).find("input[name=user_bets]")[0].readOnly = false;
          $(field).find("input[name=user_bets]")[0].name = response[i];
					
					$.each($(field).find("#user_bets"), function(key, field) {
							field.id = response[i];	
              field.disabled = false;	
					});
					$('#fieldList')[0].append(field);
				}
				
				
			}

			
	});
}

function SchemaEdit(action, service, table, params) {
	var url = window.location.origin;
	var settings = {
  "async": true,
  "crossDomain": true,
  "url": url+"/edit-table/"+action+"/"+service+"/"+table+"/"+params,
  "method": "GET",
  "headers": {
    "content-type": "application/json",
    "cache-control": "no-cache",
  },
  "processData": false
}

$.ajax(settings).done(function (response) {
  if( typeof response == 'string') {
  	response = JSON.parse(response);
  }
  if(response.status == 'ok') {
  	window.location.href = window.devless_edit_url;
  } else {
  	alert('Field/Table could not be modified'+'       '+response.message);
  }
});
}





