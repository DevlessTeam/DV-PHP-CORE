$(function() {
    SDK.queryData("ttlussd", "tractor_requests", {}, function(res) {
        $('#requests').text(res.payload.results.length.toLocaleString('en-US'));
    });

    SDK.queryData("ttlussd", "tractor_requests", {}, function(res) {
        counter = 0;
        for(i=0; i < res.payload.results.length; i++) {
            if (res.payload.results[i].status === "unassigned") {
                counter += 1;
            }
        }
        $('#pending_requests').text(counter.toLocaleString('en-US'));
    });

    SDK.queryData("ttlussd", "tractors", {}, function(res) {
        $('#trucks').text(res.payload.results.length.toLocaleString('en-US'));
    });
    
    SDK.queryData("ttlussd", "tractors", {}, function(res) {
        counter = 0;
        for(i=0; i < res.payload.results.length; i++) {
            if (res.payload.results[i].status === "unassigned") {
                counter += 1;
            }
        }
        $('#available_trucks').text(counter.toLocaleString('en-US'));
    });

    SDK.queryData("ttlussd", "farmers", {}, function(res) {
        $('#farmers').text(res.payload.results.length.toLocaleString('en-US'));
    });

    SDK.queryData("ttlussd", "owners", {}, function(res) {
        $('#owners').text(res.payload.results.length.toLocaleString('en-US'));
    });

    SDK.queryData("ttlussd", "fbo", {}, function(res) {
        $('#fbo').text(res.payload.results.length.toLocaleString('en-US'));
    });

    function logout() {
        SDK.call("TTLUSSD", "logout", [], function(res) {
            console.log(res);
        });
    }
});