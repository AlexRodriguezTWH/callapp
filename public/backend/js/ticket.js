$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
var refreshIntervalId;
var windowOpenEditar;
clearInterval(refreshIntervalId);

$(document).on("click", ".btnAnular", function (e) {
    e.preventDefault();
    var frm = $(this).closest("form");
    frm[0].reset();
});
$(document).on("click", ".btnOpenEditar", function (e) {

    e.preventDefault();
    var url = $(this).data('href');
    windowOpenEditar = window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=200,width=1200,height=700");

});
$('#modalTicketEnvivo').on('show.bs.modal', function (e) {

    $("#modalBodyTicket").html('<div class="d-flex justify-content-center"><strong>Cargando...</strong><div class="spinner-border text-primary" role="status"></div></div>');
    var button = $(e.relatedTarget) // Button that triggered the modal
    var caller = button.data('caller') // Extract info from data-* attributes
    var id = button.data('id') // Extract info from data-* attributes
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/ticket/' + id,
        success: function (json) {
            if (json.status === 'success') {
                $("#modalBodyTicket").html(json.body);
                $("#modalTicketEnvivo").modal('show');
            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('.modal-title').text('ID: ' + id + " Llamada: " + caller)
    modal.find('.modal-body input').val(caller)

});


$(document).on('change', '#modalIdPlaza', function (e) {
    var value = $(this).val();
    $("#IdPlaza").val(value);

    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/puntos/' + value,
        success: function (json) {

            if (json.status === 'success') {
                $("#inpt_codigos").html(json.codigos);
                $("#inpt_puntos").html(json.puntos);
                $("#inpt_rutas").html(json.rutas);
                $("#IdCompania").val(json.idCompania);


            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });

});

$(document).on('change', '#inpt_codigos', function (e) {
    var plaza = $("#modalIdPlaza").val();
    var value = $(this).val();
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/codigo/' + plaza + '/' + value,
        success: function (json) {

            if (json.status === 'success') {
                $("#inpt_puntos").val(json.IdAlmacen);
                $("#inpt_rutas").val(json.ruta);
            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});

$(document).on('change', '#inpt_puntos', function (e) {
    var plaza = $("#modalIdPlaza").val();
    var value = $(this).val();
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/punto/' + plaza + '/' + value,
        success: function (json) {
            if (json.status === 'success') {
                $("#inpt_codigos").val(json.codigo);
                $("#inpt_rutas").val(json.ruta);
            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});


$(document).on('click', '#btnUpdateTicket', function (e) {
    e.preventDefault();
    var ticket = $(this).data('id');
    var frm = $(this).closest("form");
    var datos = frm.serialize();
    $.ajax({
        type: "POST",
        url: __baseUrl + '/ajax/ticket/update/' + ticket,
        data: datos,
        success: function (json) {
            if (json.status == 'success') {
                toastr.success('Ticket actualizado.');
                if (json.close == "ok") {
                    $("#row-" + ticket).remove();
                }
                setTimeout(() => {
                    window.close();
                }, 1000);
                setTimeout(() => {
                    $("#modalTicketEnvivo").modal('hide');
                }, 1000);

            } else {
                toastr.error('Llena todos los campos');
            }
        }
    });
});

$(document).on('change', '#modalIdPlaza_full', function (e) {
    var value = $(this).val();
    $("#IdPlaza").val(value);
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/puntos/' + value,
        success: function (json) {
            if (json.status === 'success') {
                $("#inpt_codigos_full").html(json.codigos);
                $("#inpt_puntos_full").html(json.puntos);
                $("#inpt_rutas_full").html(json.rutas);
                $("#IdCompania").val(json.idCompania);
            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });

});


$(document).on('change', '#inpt_codigos_full', function (e) {
    var plaza = $("#modalIdPlaza_full").val();
    var value = $(this).val();
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/codigo/' + plaza + '/' + value,
        success: function (json) {
            if (json.status === 'success') {
                $("#inpt_puntos_full").val(json.IdAlmacen);
                $("#inpt_rutas_full").val(json.ruta);
            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});



$(document).on('change', '#inpt_puntos_full', function (e) {
    var plaza = $("#modalIdPlaza_full").val();
    var value = $(this).val();
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/punto/' + plaza + '/' + value,
        success: function (json) {

            if (json.status === 'success') {
                $("#inpt_codigos_full").val(json.codigo);
                $("#inpt_rutas_full").val(json.ruta);

            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});


$(document).on('click', '#btnStoreTicket', function (e) {
    e.preventDefault();
    var frm = $(this).closest("form");
    var datos = frm.serialize();
    $.ajax({
        type: "POST",
        url: __baseUrl + '/ajax/ticket/store',
        data: datos,
        success: function (json) {
            if (json.status == 'success') {
                toastr.success('Ticket creado, esta marcado como Pendiente');
                $("#modalTicketNuevo").modal('hide');
            } else {
                toastr.error('Llena todos los campos');
            }
        }
    });
});

$(document).on('click', '#btnPlayMonitor', function (e) {
    clickMonitoreo();
});


//Designa el estado del tickets
$(document).on('change', '#fechacierre', function (e) {
    //Si es pendiente = 0, si cerrado trae fecha y hora
    var value = $(this).val();
    var tipoServicio = document.getElementById('inpt_servicios_edit');
    if (tipoServicio) {
        ValidaPeriodosCierre(false, tipoServicio.value, value, false);
    }
});


//modalTicketEnvivo pendientesd
$(document).ready(function () {

    // Si el input ya está en el DOM (fuera del modal)
    if ($('#inpt_servicios_edit').length) {
        if ($('#inpt_servicios_edit').val() == 5) {
            validaUsuario();
        }
    } 
    // Si está dentro del modal
    else if ($('#modalTicketEnvivo').length) {

        $('#modalTicketEnvivo').on('shown.bs.modal', function () {
            const modal = $(this);

            // Creamos un intervalo que revisa periódicamente si ya existe el input
            const checkInput = setInterval(function () {
                const input = modal.find('#inpt_servicios_edit');
                
                if (input.length) {                    
                    if (input.val() == 5) {
                        validaUsuario();
                    }

                    clearInterval(checkInput); // Detenemos el intervalo
                }
            }, 300); // cada 300ms

            // Si el modal se cierra antes de encontrar el input, limpiamos el intervalo
            modal.on('hidden.bs.modal', function () {
                clearInterval(checkInput);
            });
        });
    }

});





// ----------------------------- Al momento de crear --------------------------------------------
$(document).on('change', '#inpt_servicios', function (e) {
    var value = $(this).val();
    ValidaPeriodosCierre(false, value, 0, true);
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/motivos/' + value,
        success: function (json) {
            if (json.status === 'success') {
                $("#inpt_motivos").html(json.motivos);

                var idServicio = document.getElementById('inpt_servicios').value;
                var idMotivo = document.getElementById('inpt_motivos').value;
                CargaFallasManual(idServicio, idMotivo);

            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});

$(document).on('change', '#inpt_motivos', function (e) {

    var idServicio = document.getElementById('inpt_servicios').value;
    var idMotivo = $(this).val();
    CargaFallasManual(idServicio, idMotivo);
});

//--------------- Edicion ----------------------------------------------
$(document).on('change', '#inpt_servicios_edit', function (e) {
    var estatus = document.getElementById('fechacierre').value;
    var value = $(this).val();

    document.getElementById("inpt_motivos_edit").innerHTML = "";
    ValidaPeriodosCierre(false, value, estatus, true);
    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/motivos/' + value,
        success: function (json) {
            if (json.status === 'success') {

                $("#inpt_motivos_edit").html(json.motivos);
                //Manda llamar las fallas
                var idServicio = document.getElementById('inpt_servicios_edit').value;
                var idMotivo = document.getElementById('inpt_motivos_edit').value;
                cargaFallas(idServicio, idMotivo);

            } else {
                toastr.error('Ocurrio un error al agregarlo a la comanda');
            }
        }
    });
});

$(document).on('change', '#inpt_motivos_edit', function (e) {
    var idServicio = document.getElementById('inpt_servicios_edit').value;
    var idMotivo = $(this).val();
    cargaFallas(idServicio, idMotivo);
});

//------------------ FUNCIONES -----------------------------


function CargaFallasManual(idServicio, idMotivo) {
    document.getElementById("IdFallaxTecnico").innerHTML = "";
    document.getElementById("IdFallaxCliente").innerHTML = "";

    if (idServicio && idMotivo) {
        $.ajax({
            type: "GET",
            url: __baseUrl + '/ajax/data/falla/serviciosFallitas/' + idServicio + '/' + idMotivo + '/' + '1',
            success: function (json) {
                if (json.status === 'success') {
                    $("#IdFallaxCliente").html(json.fallas);
                } else {
                    toastr.error('Ocurrio un error al agregarlo a la comanda');
                }
            }
        });
        $.ajax({
            type: "GET",
            url: __baseUrl + '/ajax/data/falla/serviciosFallitas/' + idServicio + '/' + idMotivo + '/' + '2',
            success: function (json) {
                if (json.status === 'success') {
                    $("#IdFallaxTecnico").html(json.fallas);
                } else {
                    toastr.error('Ocurrio un error al agregarlo a la comanda');
                }
            }
        });
    }
}

/*
Metodo encargado de validar si se muestran o no los periodos de cierre "reales"
*/
function ValidaPeriodosCierre(esNuevo, tipoServicio, estado, limpiaCampo) {
    var trLlegadaReal = document.getElementById("trLlegadaReal");
    var trCerradaReal = document.getElementById("trCerradaReal");

    const inputFechaFinal = document.querySelector('input[name="FechaFinal"]');
    const inputHora = document.getElementById('Hora_Final_Hora');
    const inputMinutos = document.getElementById('Hora_Final_Minutos');

    inputFechaFinal.disabled = false;
    inputHora.disabled = false;
    inputMinutos.disabled = false;
    //Si es TWH
    trCerradaReal.style.display = "none";

    if (tipoServicio == 3 && estado != 0 && !esNuevo) { // 3, !=0, false

        trLlegadaReal.style.display = "none";
        trCerradaReal.style.display = "table-row";
        inputFechaFinal.disabled = false;
        inputHora.disabled = false;
        inputMinutos.disabled = false;

        //Llena con la hora y fecha actual
        var fecha = new Date();
        inputFechaFinal.value = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;
        inputHora.value = String(fecha.getHours()).padStart(2, '0');
        inputMinutos.value = String(fecha.getMinutes()).padStart(2, '0');

    } else if (tipoServicio == 5) { //Si es Purefill
        validaUsuario();
        if (limpiaCampo) {

            const inputFechaInicial = document.querySelector('input[name="FechaInicial"]');
            const inputHoraInicial = document.getElementById('Hora_Inicial_Hora');
            const inputMinutosInicial = document.getElementById('Hora_Inicial_Minuto');
            inputFechaInicial.value = "";
            inputHoraInicial.value = "";
            inputMinutosInicial.value = "";
            inputFechaFinal.value = "";
            inputHora.value = "";
            inputMinutos.value = "";
        }
    } else {//Otro tipo de servicio

        trLlegadaReal.style.display = "none";
        inputFechaFinal.value = "";
        inputHora.value = "";
        inputMinutos.value = "";
    }

}

function validaUsuario() {
console.log("Valida usuario");

    const userId = document.querySelector('input[name="userid"]').value.trim();
    const usuariosEditables = ['ASEMPORINI', 'LSUAREZ', 'FIBARRA', 'RMONDRAGON', 'KAGONZALES', 'MJAGUILAR', 'PCECILIA'];
    document.getElementById("trLlegadaReal").style.display = "table-row";
    document.getElementById("trCerradaReal").style.display = "table-row";
    //Elimina la informacion anterior
    if (!usuariosEditables.includes(userId)) {
        document.querySelector('input[name="FechaFinal"]').disabled = true;
        document.getElementById('Hora_Final_Hora').disabled = true;
        document.getElementById('Hora_Final_Minutos').disabled = true;
    }
}

function cargaFallas(idServicio, idMotivo) {

    //Limpia antes de llenar los filtros
    document.getElementById("IdFallaxTecnico").innerHTML = "";
    document.getElementById("IdFallaxCliente").innerHTML = "";
    if (idServicio && idMotivo) {

        // Llena con la informacion
        $.ajax({
            type: "GET",
            url: __baseUrl + '/ajax/data/falla/serviciosFallitas/' + idServicio + '/' + idMotivo + '/' + '1',
            success: function (json) {
                if (json.status === 'success') {
                    $("#IdFallaxCliente").html(json.fallas);
                } else {
                    toastr.error('Ocurrio un error al agregarlo a la comanda');
                }
            }
        });

        // Manda llamar el tecnico 
        $.ajax({
            type: "GET",
            url: __baseUrl + '/ajax/data/falla/serviciosFallitas/' + idServicio + '/' + idMotivo + '/' + '2',
            success: function (json) {
                if (json.status === 'success') {
                    $("#IdFallaxTecnico").html(json.fallas);
                } else {
                    toastr.error('Ocurrio un error al agregarlo a la comanda');
                }
            }
        });
    }
}


function clickMonitoreo() {
    clearInterval(refreshIntervalId);
    refreshIntervalId = setInterval(function () { refreshMonitorAgua(); }, 10000);
}

function refreshMonitorAgua() {

    $.ajax({
        type: "GET",
        url: __baseUrl + '/ajax/data/envivo',
        success: function (json) {
            if (json.status == 'success') {
                $("#dvTime").html(json.time);
                $("#calls_online").html(json.body);

            } else {
                toastr.error('Llena todos los campos');
            }
        }
    });

}











