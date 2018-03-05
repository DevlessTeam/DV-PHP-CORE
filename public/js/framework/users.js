$(document).ready(function () {
    var dataSet = [];
    var Datatable;
    var user_id;
    var rowData;
    var element_id;
    var status;

    //Init Datatable for usesr records
    Datatable = $("#users-table").DataTable({
        columnDefs: [{
            orderable: false,
            className: "select-checkbox",
            targets: 0
        }],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [
            [1, "asc"]
        ]
    });

    $(document).on("click", "#dtRow", function() {
        // grab row id
        element_id = $(this).find("tr").context._DT_RowIndex;

        // hide modal
        $('.alert').hide();

        $("#udModal").modal({
            "backdrop": "static"
          });

        rowData = Datatable.row(this).data();
        user_id = rowData[1];
        status = rowData[7];
        $("#udModal #username").val(rowData[2]);
        $("#udModal #phone_number").val(rowData[5]);
        $("#udModal #email").val(rowData[6]);
        $("#udModal #first_name").val(rowData[3]);
        $("#udModal #last_name").val(rowData[4]);
        rowData[7] === "true" ? $("#udModal #active").prop("checked", true) : null;
    });

    // Add new user
    $("#save").click(function () {
        var id = Datatable.data()[0] !== undefined ?
            parseInt(Datatable.data()[0][1]) + 1 :
            1;
        var username = $("#add_username").val();
        var phone_number = $("#add_phone_number").val();
        var email = $("#add_email").val();
        var fname = $("#add_first_name").val();
        var lname = $("#add_last_name").val();
        var password = $("#add_password").val();
        var con_password = $("#add_confirm_password").val();

        if (password === con_password) {
            SDK.call(
                "devless",
                "signUp", [email, password, username, phone_number, fname, lname],
                function (res) {
                    if (res.payload.result) {
                        Datatable.row
                            .add([
                                "",
                                res.payload.result.profile.id,
                                username,
                                fname,
                                lname,
                                phone_number,
                                email,
                                res.payload.result.profile.status
                            ])
                            .draw();
                        $(".alert").show();
                    } else {
                        alert("User creation failed");
                    }
                }
            );
        } else {
            alert("Passwords doesn't match");
        }
    });

    // Code snippet for converting form data into an object (key & value)
    function jQExtn() {
        $.fn.serializeObject = function () {
            var obj = {};
            var arr = this.serializeArray();
            $.each(arr, function () {
                if (obj[this.name] !== undefined) {
                    if (!obj[this.name].push) {
                        obj[this.name] = [obj[this.name]];
                    }
                    obj[this.name].push(this.value || "");
                } else {
                    obj[this.name] = this.value || "";
                }
            });
            return obj;
        };
    }

    jQExtn();

    $("#select-all").change(function () {
        if ($(this).is(":checked")) {
            Datatable.rows().select();
        } else {
            Datatable.rows().deselect();
        }
    });

    // Handles deleting of users from the db
    $("#delete-users").click(function () {
        array_id = [];
        var selectedIds = Datatable.rows({
            selected: true
        }).data();
        selectedIds.map((v, i) => {
            array_id.push(v[1]);
        });

        if (array_id.length !== 0) {
            var action = confirm("Are sure you want this action");
            if (action) {
                $.ajax({
                    url: "remove_user",
                    type: "DELETE",
                    data: {
                        data: array_id
                    }
                }).done(function (res) {
                    if (res) {
                        Datatable.rows({
                            selected: true
                        }).remove().draw(false);
                    } else {
                        alert("Error occurred when deleting user(s)");
                    }
                });
            }
        }
    });

    $("#updateForm").submit(function (e) {
        e.preventDefault();

        var payload = $(this).serializeObject();

        if (payload.password === payload.confirm_password) {
            $.ajax({
                url: "update_user",
                type: "PATCH",
                data: {
                    id: user_id,
                    username: payload.username,
                    first_name: payload.first_name,
                    last_name: payload.last_name,
                    phone_number: payload.phone_number,
                    email: payload.email,
                    password: payload.password,
                    active: payload.active
                }
            }).done(function (res) {
                if (res === 'true') {
                    active = (payload.active === "on") ? true : false;

                    Datatable.row(element_id).data([
                        "",
                        user_id,
                        payload.username,
                        payload.first_name,
                        payload.last_name,
                        payload.phone_number,
                        payload.email,
                        active
                    ]);
                    $(".alert").show();
                } else {
                    alert("Error updating user info");
                }
            });
        } else {
            alert("Password doesn't match");
        }
    });
});

var authSettings = {};
authSettings.get = function (callback) {
    $.getJSON("/open-api/authSettings/getAuthSettings/[]", function (resp) {
        callback(resp);
    });
};

authSettings.update = function (settings, callback) {
    $.getJSON(
        "/open-api/authSettings/updateAuthSettings/" + JSON.stringify(settings),
        function (resp) {
            callback(resp);
        }
    );
};

var authForm = {};

authForm.populate = function () {
    authSettings.get(function (resp) {
        console.log(resp);
        $("#verify_email_true")[0].checked = resp["verify_email"] != 0 || false;
        $("#self_signup_true")[0].checked = resp["self_signup"] != 0 || false;
        $("#expire_session")[0].checked = resp["expire_session"] != 0 || false;
        $("#session_time")[0].value = resp["session_time"];
    });
};

authForm.save = function () {
    var session_time = $("#session_time")[0].value;
    var self_signup = $("#self_signup_true")[0].checked ? 1 : 0;
    var verify_email = $("#verify_email_true")[0].checked ? 1 : 0;
    var expire_session = $("#expire_session")[0].checked ? 1 : 0;
    newSettings = [session_time, self_signup, verify_email, expire_session];
    console.log(newSettings);
    authSettings.update(newSettings, function (resp) {
        $("#settings-notif")[0].textContent = resp["ok"] ?
            "Settings updated successfully" :
            "Settings could not be updated";
        $("#settings-notif")[0].style.display = "block";
        setTimeout(function () {
            $("#settings-notif")[0].style.display = "none";
        }, 2000);
    });
};
authForm.populate();

//hide modal
function hideModal () {
    $('#udModal').modal('hide');
    $('tr').removeClass('selected');
}