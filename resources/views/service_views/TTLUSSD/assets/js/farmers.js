$(function(){

    var dataSet = [];
    
    SDK.queryData("ttlussd", "farmers", {related: "*"}, function(res) {

        response = res.payload.results;
console.log(response);

        for (i=0; i < response.length; i++) {
            arr = [
                response[i].name,
                response[i].phone,
                response[i].location,
                response[i].related.fbo[0].name,
                response[i].acres,
                response[i].source
            ];

            dataSet.push(arr);
        }

        table = $('#farmers-table').DataTable({
            data: dataSet,
            columns: [
                {"title": "Name"},
                {"title": "Phone"},
                {"title": "Location"},
                {"title": "Organization"},
                {"title": "No. of Acres"},
                {"title": "Source"}
            ]
        });
    });

    /* Serial form data into JSON obj */
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
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

    $('form').submit(function(e) {
        e.preventDefault();
        var data = $(this).serializeFormJSON();
        farmers = parseInt($('#no_of_farmers').val()) + 1;

        fbo = $('#farmer option:selected').text();
        SDK.addData("ttlussd", "farmers", data, function(res) {
            $("#addFarmer").modal('hide');
            if (res.status_code === 609) {
                SDK.updateData("ttlussd", "fbo", "id", data.TTLUSSD_fbo_id, {"no_of_farmers": farmers}, function(resp) {
                    if (resp.status_code === 619) {
                        rowNode = table
                            .row.add( [ data.name, data.phone, data.location, fbo,data.acres, "TTL" ] )
                            .draw()
                            .node();
                        
                        $( rowNode )
                            .css( 'color', 'red' )
                            .animate( { color: 'black' });
                         $('form')[0].reset();
                    } else {
                        alert("Error updating the fbo table. Call Adam.");
                    }
                });
            } else {
                alert("Error adding farmer. Try again!");
            }
        });


    });

    SDK.queryData("ttlussd", "fbo", {}, function(res) {
        for(i=0; i < res.payload.results.length; i++) {
            
            $('#farmer').append('<option value="'+res.payload.results[i].id+'">'+res.payload.results[i].name+'</option>');

        }
    });

    $('#farmer').change(function() {
        no_farmers = $('#farmer').val();
        SDK.queryData("ttlussd", "fbo", {where: 'id,'+no_farmers}, function(res) {
            $('#no_of_farmers').val(res.payload.results[0].no_of_farmers);
        });
    });


});

