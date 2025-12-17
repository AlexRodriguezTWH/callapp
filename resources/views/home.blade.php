@extends('layouts.backend.app')
@section('section-title')
  <h3>En vivo</h3>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                @if (Session::get('opcion_ticket_store'))
                <a href="#" data-toggle = "modal" data-target = "#modalTicketNuevo">
                  <img src= "{{ asset('img/btnNuevo.png') }}" />
                </a>
                @endif
                <span class="text-muted text-sm" id="dvTime"></span>
              </div>
                <div class="card-body" id="calls_online">
                  @include('tickets.tabla')
                </div>
            </div>
        </div>
    </div>

    @include('modals.ticket-full')
    @include('modals.ticket')
@endsection



@section('js_after')
  <script src="{{ asset('backend/js/ticket.js') }}"></script>
  <script>  $(document).ready(function(e){  clickMonitoreo();   }); </script>
@endsection
