<!-- Modal -->
<div class="modal fade" id="modalTicketNuevo"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true">

  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="staticBackdropLabel">Nuevo ticket - Manual</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id='modalBodyTicketFull'>
        @include('tickets.fragmentos.form-full')
      </div>

    </div>
  </div>
</div>
