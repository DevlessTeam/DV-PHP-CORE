$(function() {
    var dataSet = [];

    SDK.queryData("ttlussd", "owners", {}, function(res){

        response = res.payload.results;

        for (i=0; i < response.length; i++) {
            arr = [
                response[i].name,
                response[i].type,
                response[i].phone,
                response[i].location,
                response[i].no_of_tractors
            ];

            dataSet.push(arr);
        }
        
        table = $('#owners-table').DataTable({
            data: dataSet,
            columns: [
                {"title": "Name"},
                {"title": "Type"},
                {"title": "Phone"},
                {"title": "Location"},
                {"title": "No. of Tractors"}
            ]
        });
        /*
        $('#owners-table tbody').on( 'click', 'tr', function () {
            dataArr = table.row( this ).data();
            $('#addOwner').modal('show');
            $('#name').val(dataArr[0]);
            $('#type').val(dataArr[1]);
            $('#phone').val(dataArr[2]);
            $('#location').val(dataArr[3]);
            $('#no_of_tractors').val(dataArr[4]);
        }); */
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

            SDK.addData("ttlussd", "owners", data, function(res) {
                if (res.status_code === 609) {
                    $('#addOwner').modal('hide');
                    rowNode = table
                    .row.add( [ data.name, data.type, data.phone, data.location, data.no_of_tractors ])
                    .draw()
                    .node();
                
                $( rowNode )
                    .css( 'color', 'red' )
                    .animate( { color: 'black' });
                } else {
                    alert("Error adding new owner. Try again!");
                }
            });

        });
});