$(function() {
    
    var dataSet = [];

    SDK.queryData("ttlussd", "tractor_requests", {}, function(res){
        response = res.payload.results;
        for (i = 0; i < res.payload.results.length; i++) {
            delete response[i].devless_user_id;

            arr = [
                response[i].id,
                response[i].name,
                response[i].phone,
                response[i].location,
                response[i].type,
                response[i].acres,
                response[i].cost,
                response[i].time,
                response[i].source,
                response[i].status,
                response[i].payment_check
            ];
            dataSet.push(arr);
        }
        
        table = $('#tractor-requests').DataTable({
            data: dataSet,
            columns: [
                {"title": "Id"},
                {"title": "Name"},
                {"title": "Phone"},
                {"title": "Location"},
                {"title": "Service Type"},
                {"title": "Acres"},
                {"title": "Cost"},
                {"title": "Time"},
                {"title": "Source"},
                {"title": "Status"},
                {"title": "Issued Payment"}
            ]
        });

        $('#tractor-requests tbody').on( 'click', 'tr', function () {
            $('#status').html(" ");
            SDK.queryData("ttlussd", "tractors", {}, function(res) {
                resp = res.payload.results;
                for(i=0; i < resp.length; i++) {
                    if (resp[i].status === "unassigned") {
                        $('#status').append('<option value="'+resp[i].name+'">'+resp[i].name+'</option>');
                    }
                }
                
            });

            dataArr = table.row( this ).data();
            $('#tractorRequest').modal('show');
            $('#id').val(dataArr[0]);
            $('#name').val(dataArr[1]);
            $('#phone').val(dataArr[2]);
            $('#location').val(dataArr[3]);
            $('#acres').val(dataArr[5]);
            $('#time').val(dataArr[7]);
            $('#status').val(dataArr[8]);
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

    $('#assign_request').click(function(e) {
        e.preventDefault();
        data = $('#farmer-form').serializeFormJSON();

        if (dataArr[9] != "completed" && dataArr[9] === "unassigned") {
            SDK.updateData("ttlussd", "tractor_requests", "id", data.id, { "status": data.status}, function(res) {
                if (res.status_code === 619) {
                    SDK.updateData("ttlussd", "tractors", "name", data.status, { "status": data.name}, function(resp) {
console.log('tractors', data.status);
                        if (resp.status_code === 619) {
                            phone_no = data.phone.replace('0', '+233');
                            SDK.call('TTLUSSD', 'sendRequestSMS', [phone_no, data.name], "");
                            $('#tractorRequest').modal('hide');
                            location.reload();
                        } else {
                            alert("Error updating tractors. Call Adam.");
                        }
                    });
                } else {
                    alert("Error assigning tractor to request. Try again!");
                }
            });
        } else {
            alert("Error! Request is already completed or assigned. Contact Admin");
        }
        
    });

    $('#complete_request').click(function(e) {
        e.preventDefault();
        data = $('#farmer-form').serializeFormJSON();

        if (dataArr[9] != "unassigned") {
            SDK.updateData("ttlussd", "tractor_requests", "id", data.id, { "status": "completed"}, function(res) {
                if (res.status_code === 619) {
                    SDK.updateData("ttlussd", "tractors", "name", dataArr[9], { "status": "unassigned"}, function(resp) {
                        console.log(resp);
                        if (resp.status_code === 619) {
                            $('#tractorRequest').modal('hide');
                            location.reload();
                        } else {
                            alert("Error updating tractors. Call Adam.");
                        }
                    });
                } else {
                    alert("Error updating request. Try again!");
                }
            });
        } else {
            alert("Assign request first");
        }
        $('#tractorRequest').modal('hide');

    });

});
