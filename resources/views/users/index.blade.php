@extends('layout')

@section('header')

<script src="{{ Request::secure(Request::root())."/js/jquery-1.10.2.min.js" }}"></script>

<!-- page head start-->
<div class="page-head">
    <h3>Users</h3>
    <span class="sub-title">Users/</span>
</div>
<!-- page head end-->

@endsection

@section('content')
<br>
<div class="col-lg-12">
    <section class="panel">

        <table class="table table-striped table-bordered table-hover" id="users-table" width="100%"></table>
    </section>
</div>


<script>
    $(document).ready(function() {
        var dataSet = [];
        $.get('/retrieve_users', function(res) {
            console.log(res);
            for (i= 0; i < res.length; i++) {
                arr = [
                    res[i].id,
                    res[i].username,
                    res[i].first_name,
                    res[i].last_name,
                    res[i].phone_number,
                    res[i].email,
                    !res[i].status
                ];
                dataSet.push(arr);
            }

            $('#users-table').DataTable({
                data: dataSet,
                columns:[
                    {"title": "Id"},
                    {"title": "Username"},
                    {"title": "First Name"},
                    {"title": "Last Name"},
                    {"title": "Phone Number"},
                    {"title": "Email"},
                    {"title": "Active"}
                ]
            });
        })
    });
</script>
@endsection
