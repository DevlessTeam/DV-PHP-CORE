@extends('layout')

@section('header')
  <div class="page-head">
    <h3>
      Data Table
    </h3>
  </div>
@endsection

@section('content')
  <div class="wrapper">
    <div class="row">
      <div class="col-sm-12">
        <section class="panel">
          <table class="schema-table table" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="details-control"></td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </div>
  </div>
@endsection
