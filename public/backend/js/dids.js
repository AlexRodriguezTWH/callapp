

$(document).on('click','.btnDeleteDID', function(){
  var frm = $(this).closest("form");

  swal = new Swal({
              title: '¿Deseas eliminar este DID?',
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Continuar`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                  frm[0].submit();

                } else if (result.isDenied) {
                  Swal.fire('Changes are not saved', '', 'info')
                }
              })


});
