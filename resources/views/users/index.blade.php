@extends('layout')

@section('header')
<!-- page head start-->
<div class="page-head">
    <h3>Users</h3>
    <span class="sub-title">Users/</span>
    <form method="post" action="#" class="search-content">
        <input type="text" placeholder="Search users..." name="keyword" class="form-control">
    </form>
</div>
<!-- page head end-->

@endsection

@section('content')
<br>
<div class="col-lg-12">
    <section class="panel">

        <table class="table">
            <thead>
            <tr>
                 <th>ID</th>
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>

             @if($users->count()> 1)
                @foreach($users as $user)
                    @if($user->id != 1)
                        <tr>  
                         <td>{{$user->id}}</td>
                         <td>{{substr($user->username,0,10)}}</td>
                         <td>{{substr($user->first_name,0,10)}}</td>
                         <td>{{substr($user->last_name,0,10)}}</td>
                         <td>{{substr($user->phone_number,0,10)}}</td>
                         <td>{{substr($user->email,0,10)}}</td>
                        </tr> 
                    @endif
                @endforeach
             @endif       

            </tbody>
        </table>
    </section>
</div>

@endsection