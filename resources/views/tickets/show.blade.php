@extends('layouts.backend.ticket')
@section('title')
  Ticket - {{ $ticket->ID }}
@endsection 
@section('section-title')
  <h3>Ticket <span class="badge badge-success">{{ $ticket->ID }}</span></h3>
@endsection
@section('content')
      <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body" >
                  @include('tickets.fragmentos.form-window')
                </div>
              </div>
          </div>
      </div>
@endsection

@section('js_after')
  <script src="{{ asset('backend/js/ticket.js') }}"></script>
@endsection
