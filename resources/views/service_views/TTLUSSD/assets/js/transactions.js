$(function() {

    var dataSet = [];

    SDK.queryData("ttlussd", "transactions", {}, function(res) {
        results = res.payload.results;

        for( i=0; i < results.length; i++) {
            jsonData = JSON.parse(results[i].log);
            wallet = jsonData.description.substr(54,10);
            dataArr = [
                results[i].id,
                results[i].date,
                jsonData.transaction_id,
                wallet,
                jsonData.mobile_invoice_no,
                jsonData.token,
                results[i].status
            ]
            dataSet.push(dataArr);
        }

        table = $('#transactions-table').DataTable({
            data: dataSet,
            columns: [
                {"title": "Id"},
                {"title": "Date"},
                {"title": "Transaction ID"},
                {"title": "Wallet Number"},
                {"title": "Invoice No."},
                {"title": "Token"},
                {"title": "Status"}
            ]
        });

         $('#transactions-table tbody').on( 'click', 'tr', function () {
            dataArr = table.row( this ).data();
            $('#transaction-form').modal('show');
            $('#date').val(dataArr[1]);
            $('#transaction_id').val(dataArr[2]);
            $('#wallet_number').val(dataArr[3]);
            $('#invoice_no').val(dataArr[4]);
            $('#token').val(dataArr[5]);
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
        data = $(this).serializeFormJSON();
        SDK.call('TTLUSSD', 'checkstatus', [ data.token ], function(res) {
                console.log(res);
            if (res.payload.result) {
                JSONData = JSON.parse(res.payload.result);
                
                if(JSONData.tx_status != dataArr[6]) {

                    SDK.updateData("ttlussd", "transactions", "id", dataArr[0], {"status": JSONData.tx_status}, function(resp) {
                        console.log(resp);
                        if (resp.status_code === 619) {
                            $('#transaction-form').modal('hide');
                            location.reload();
                        } else {
                            alert("Error checking transaction status! Call Adam :D");
                        }
                    });
                } else {
                    alert("The state of the transaction is still the same. Try again after 30 minutes.");
                    $('#transaction-form').modal('hide');
                }
            } else {
                alert('Error from Mpower! Try again');
            }
        });

    
    });


});