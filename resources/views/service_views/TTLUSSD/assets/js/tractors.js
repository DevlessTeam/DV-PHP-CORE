$(function() {
    var dataSet = [];
    var table;

    SDK.queryData("ttlussd", "tractors", {related: "owners"}, function(res){

        response = res.payload.results;
        console.log(response);

        for (i=0; i < response.length; i++) {
            arr = [
                response[i].name,
                response[i].related.owners[0].name,
                response[i].chasis,
                response[i].engine,
                response[i].model,
                response[i].year,
                response[i].status
            ];

            dataSet.push(arr);
        }
        
        table = $('#tractors-table').DataTable({
            data: dataSet,
            columns: [
                {"title": "Name"},
                {"title": "Owner"},
                {"title": "Chasis No."},
                {"title": "Engine No."},
                {"title": "Model"},
                {"title": "Model Year"},
                {"title": "Available"}
            ]
        });
        
        /*
        $('#tractors-table tbody').on( 'click', 'tr', function () {
            dataArr = table.row( this ).data();
            $('#addTractor').modal('show');
            $('#owner').val(dataArr[0]);
            $('#chasis').val(dataArr[1]);
            $('#engine').val(dataArr[2]);
            $('#model').val(dataArr[3]);
            $('#year').val(dataArr[4]);
            $('#status').val(dataArr[5]);
        });
        */
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
        tractors = parseInt($('#no_of_tractors').val()) + 1;
        owner = $('#owner option:selected').text();
// /*
        SDK.addData("ttlussd", "tractors", data, function(res){
            $("#addTractor").modal('hide');
            if(res.status_code === 609) {
                SDK.updateData("ttlussd", "owners", "id", data.TTLUSSD_owners_id, {"no_of_tractors": tractors}, function(resp) {
                    if (resp.status_code === 619) {
                        
                        rowNode = table
                            .row.add( [ data.name, owner, data.chasis, data.engine, data.model, data.year, data.status ] )
                            .draw()
                            .node();
                        
                        $( rowNode )
                            .css( 'color', 'red' )
                            .animate( { color: 'black' });
                        $('form')[0].reset();
                    } else {
                        alert("Error updating the owners table. Call Adam.");
                    }
                });
            } else {
                alert("Error adding tractor. Try again");
            }
        });
// */
    });

    SDK.queryData("ttlussd", "owners", {}, function(res) {
        for(i=0; i < res.payload.results.length; i++) {
            
            $('#owner').append('<option value="'+res.payload.results[i].id+'">'+res.payload.results[i].name+'</option>');

        }
    });

    $('#owner').change(function() {
        no_tractors = $('#owner').val();
        SDK.queryData("ttlussd", "owners", {where: 'id,'+no_tractors}, function(res) {
            $('#no_of_tractors').val(res.payload.results[0].no_of_tractors);
        });
    });

});