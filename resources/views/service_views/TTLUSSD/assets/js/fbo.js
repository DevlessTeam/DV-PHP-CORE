$(function() {
    var dataSet = [];

    SDK.queryData("ttlussd", "fbo", {}, function(res){

        response = res.payload.results;

        for (i=0; i < response.length; i++) {
            arr = [
                response[i].name,
                response[i].phone,
                response[i].location,
                response[i].no_of_farmers
            ];

            dataSet.push(arr);
        }
        
        table = $('#fbos-table').DataTable({
            data: dataSet,
            columns: [
                {"title": "Name"},
                {"title": "Phone"},
                {"title": "Location"},
                {"title": "No. of Farmers"}
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

            SDK.addData("ttlussd", "fbo", data, function(res) {
                if (res.status_code === 609) {
                    $('#addFbo').modal('hide');
                    rowNode = table
                    .row.add( [ data.name, data.phone, data.location, data.no_of_farmers ])
                    .draw()
                    .node();
                
                $( rowNode )
                    .css( 'color', 'red' )
                    .animate( { color: 'black' });
                } else {
                    alert("Error adding new FBO. Try again!");
                }
            });

        });
});