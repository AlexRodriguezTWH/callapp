@extends('layouts.backend.app')
@section('section-title')
  <h3>Nuevo DID</h3>
@endsection

@section('content')
      <div class="row">
          <div class="col-md-9 offset-md-1">
              <div class="card">
                <div class="card-body">
                  @include('dids.form')
                </div>
              </div>
          </div>
      </div>

@endsection
