@extends('adminlte::page')

@section('title', 'OST')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>OST</h1>
            <a href="javascript:void(0)" class="btn btn-success" id="add">Añadir</a> 
        </div> 
    </div>  
@stop

@section('css')
    <style>


        /* Section content-header */
        .section-header {
            display: flex; 
            justify-content: center; 
            margin-top: .5rem;
        }
        .section-header > div{
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 0 0 100%;
            max-width: 83rem;
        }
        @media(min-width:300px) {
            .section-header > div{
                flex-direction: row;
                justify-content: space-between;
                flex: 0 0 98%;
            }
        }
        @media(min-width:450px) {
            .section-header > div{
                flex: 0 0 95%;
            }
        }

        .modal-head {
            display: flex;
            padding: 1rem;
            background-color: #0F62AC;
            /* border-bottom: 1px solid #e9ecef; */
            border-top-left-radius: calc(.3rem - 1px);
            border-top-right-radius: calc(.3rem - 1px);
        }
        .modal-head > h5 {
            margin: 0 auto;
            color: white;
            font-size: 1.5rem;
            line-height: 1.5;
        }
        .modal-head > button {
            margin: -1rem -1rem -1rem -1rem;
            color: white;
            padding: 1rem;
        }
        .modal-bod {
            flex: 1 1 auto;
            padding: 1.8rem 1.5rem 0 1.8rem;
        }
        .modal-foot {
            display: flex;
            justify-content: center;
            padding: 1.4rem 0 1.7rem 0;
        }
        .alert-error {
            color: #fff;
            background: #dc3545;
            border-color: #d32535;
            padding: 1rem 1rem .5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            display: none;
        }
        @media(min-width:760px) {
            .alert-error {
                padding: 2rem 1rem .5rem 3rem;
            }
        }
        .alert-error .print-error-msg h4 {
            font-size: 1.25rem;
        }
        @media(min-width:760px) {
        .alert-error .print-error-msg h4 {
                font-size: 1.5rem;
            }
        }
        .alert-error .print-error-msg ul {
            margin-left: .6rem;
        }
        @media(min-width:760px) {
            .alert-error .print-error-msg ul {
                font-size: 1.1rem;
            }
        }
        .alert-error > button {
            align-self: end;
            margin-top: .5rem;
        }
        @media(min-width:760px) {
        .alert-error > button {
                margin: -.5rem .3rem 0 0;
            }
        }

        .container-fluid {
            padding:0 .2rem;
        }
        .generic-card {
            width: 100%;
            min-width: 21rem;
            max-width: 83rem;
        }
        @media(min-width:450px) {
            .generic-card {
                width: 95%;
                margin-top: 1rem;
            }
        }
        .generic-body {
            flex: 1 1 auto;
        }


        /* DATATABLES */
        .table thead th{
            vertical-align: middle !important;
        }
        .datatables-p {
            display: flex;
            justify-content: center;
            padding: 0 .5rem;
            margin-top: 1rem;
        }
        @media(min-width:750px) {
            .datatables-p {
                margin-top: 2rem;
            }
        }
        .dt-buttons .btn-secondary {
            background-color: #0f62ac;
            border-color: #0f62ac;
        }
        @media(min-width:750px) {
            .dt-buttons .btn-secondary {
                font-size: 1.15rem;
            }
        }
        .datatables-s {
            display: flex;
            flex-direction: column;
        }
        @media(min-width:750px) {
            .datatables-s {        
                margin-top: 1rem;
                flex-direction: row;
                justify-content: space-between;
            }
        }
        .datatables-s .datatables-length {
            display: flex;
            justify-content: center;
            margin-top: .5rem;
        }
        @media(min-width:750px) {
            .datatables-s .datatables-length {
                margin-left: 2rem;
            }
        }
        .datatables-s .datatables-filter {
            display: flex;
            justify-content: center;
            margin-top: .5rem;
        }
        @media(min-width:750px) {
            .datatables-s .datatables-filter {
                margin-right: 2rem;
            }
        }
        .datatables-t {
            padding: .5rem 0 0 .3rem;
            margin: auto;
            max-width: 80rem;
        }
        @media(min-width:750px) {
            .datatables-t {
                width: 95%;
            }
        }

        .datatables-c {        
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            justify-content: center;
        }
        @media(min-width:750px) {
            .datatables-c {        
                margin-top: 1rem;
                justify-content: space-between;
                flex-direction: row; 
            }
        }
        .datatables-c .datatables-information {
            justify-content: center;
            margin-top: .5rem;
            display: none;
        }
        @media(min-width:450px) {
            .datatables-c .datatables-information {
                display: flex;
            }
        }
        @media(min-width:750px) {
            .datatables-c .datatables-information {
                margin-left: 2rem;
            }
        }
        .datatables-c .datatables-processing {
            margin-top: .5rem;
            display: flex;
            justify-content: center;
        }
        @media(min-width:450px) {
            .datatables-c .datatables-processing {
                margin: 1rem 0 0 0;
            }
        }
        @media(min-width:750px) {
            .datatables-c .datatables-processing {
                margin: .5rem 2rem 0 0;
            }
        }
    </style> 
@stop

@section('js')
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script>
    <script>

        /*PRESS NAV-LINK BUTTON*/
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#data-table').dataTable();
                    oTable.fnAdjustColumnSizing();
                }, 
            350);
        });
        /*PRESS NAV-LINK BUTTON*/
        /* FECHA PARA EL DATETIMEPICKER */
        if ( moment().startOf('month').format('YYYY-MM-DD') === moment().format('YYYY-MM-DD') ) {
            var date_fd = moment().subtract(1, "month").startOf('month').format('YYYY-MM-DD');
        }
        else {
            var date_fd = moment().startOf('month').format('YYYY-MM-DD');
        }
        var date_fh = moment().subtract(1, "days");
        var table;
        $(function(){
            /* Calendario Fecha desde*/
            $('.datetimepicker-input').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: date_fd,
                maxDate: moment().subtract(2, "days"),
                minDate: moment("2022-01-01").format('YYYY-MM-DD'),
                useCurrent: false,
            });
            $(".fecha-picker").on("change.datetimepicker", function (e) {
                date_fd = e.date;
            });
            $('.timepicker').datetimepicker({
                format:'HH:mm:ss'
            });
        })
        /*FIN Fecha*/

        /* DATATABLES */
        $(document).ready(function(){     
            $("#data-table").DataTable({
                dom: "<'datatables-p'<'datatables-button'B>>" +
                         "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5', 
                        text: 'Copiar'
                    }, 
                    {
                        extend: 'csvHtml5',
                        title: 'Listado OST '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoOST'+moment().local().format('DD/MM/YYYY')
                    }, 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado OST '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoOST'+moment().local().format('DD/MM/YYYY')            
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoOST'+moment().local().format('DD/MM/YYYY'),
                        customize: function ( doc ) {
                            doc.styles.title.alignment = 'right';
                            doc.styles.title.fontSize = 12;
                        }
                    },
                    {
                        extend: 'print', 
                        text: 'Imprimir',
                        title: 'Listado OST '+moment().local().format('DD/MM/YYYY'),
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('ost')}}",
                    type: 'GET',
                },
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast":"Último",
                        "sNext":"Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "sProcessing":"Procesando...",
                },
                columns: [
                    
                    {data:'fecha', name:'fecha'},
                    {data:'tipologia', name:'tipologia'},
                    {data:'nombre_y_apellido', name:'nombre_y_apellido'},
                    {data:'area_reportante', name:'area_reportante'},
                    {data:'lugar_frente', name:'lugar_frente'},
                    {data:'tarea_observada', name:'tarea_observada'},
                    {data:'acto_inseguro', name:'acto_inseguro'},
                    {data:'condicion_inseguro', name:'condicion_inseguro'},
                    {data:'desc_condicion_insegura', name:'desc_condicion_insegura'},
                    {data:'solu_condicion_insegura', name:'solu_condicion_insegura'},
                    {data:'elimi_condicion_insegura',name:'elimi_condicion_insegura'},
                    {data:'prevenir_repeticion', name:'prevenir_repeticion'},
                    {data:'reforzar_acto_seguro', name:'reforzar_acto_seguro'},
                    {data:'responsable_ejecucion', name:'responsable_ejecucion'},
                    {data:'fecha_vecimiento', name:'fecha_vecimiento'},
                    {data:'nivel_criticidad', name:'nivel_criticidad'},
                    {data:'estado', name:'estado'},
                    {data:'comentario', name:'comentario'},
                    {data:'fecha_cierre', name:'fecha_cierre'},
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ],
                order: [[0, 'asc']]            
            });
        });
        /* DATATABLES */

        /* ADD BUTTON*/
        $('#add').click(function () { 
            $('#form-button').val(1);     
            $('#modal-title').html('Cargar OST'); 
            $('#modal-form').trigger("reset"); 
            $('#modal').modal('show');  
            $('#id').val('');   
        });
        /* ADD BUTTON*/

         /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('ost/'+id+'/edit', function(data){
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Area'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);
                $('#fecha').val(data.fecha);
                $('#tipologia').val(data.tipologia);
                $('#nomyape').val(data.nombre_y_apellido);
                $('#area').val(data.area_reportante);
                $('#lugar').val(data.lugar_frente);
                $('#tarea').val(data.tarea_observada);
                $('#acto').val(data.acto_inseguro);
                $('#condicion').val(data.condicion_inseguro);
                $('#descripcion').val(data.desc_condicion_insegura);
                $('#solucion').val(data.solu_condicion_insegura);
                $('#elimin').val(data.elimi_condicion_insegura);
                $('#accion').val(data.prevenir_repeticion);
                $('#reconocio').val(data.reforzar_acto_seguro);
                $('#responsable').val(data.responsable_ejecucion);
                $('#fecha_vencimiento').val(data.fecha_vecimiento);
                $('#nivel').val(data.nivel_criticidad);
                $('#estado').val(data.estado);
                $('#comentario').val(data.comentario);
                $('#fecha_cierre').val(data.fecha_cierre);
                $('input[name="_token"]').val()                          
            })
        });      
        /* EDIT BUTTON */

        /* DELETE BUTTON */  
        $(document).on('click', '.delete', function() {
            let id = $(this).attr('id');
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
            title: 'Estas seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "ost/" + id,
                    type: 'delete',
                    data:{_token: $('input[name="_token"]').val()},
                    success: function (data) { 
                        var oTable = $('#data-table').dataTable();
                        oTable.fnDraw(false); 
                        swalWithBootstrapButtons.fire(
                            'Borrado!',
                            'El registro fue eliminado.',
                            'success'
                        )
                    },
                    error: function (data){
                        swalWithBootstrapButtons.fire(
                            'Error!',
                            'El registro no puede ser borrado! Hay recursos usandolo.',
                            'warning'
                        )
                    }
                });
               
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Tu registro esta a salvo :)',
                    'error'
                )
            }
            })


        });
        /* DELETE BUTTON */
        

        /* FORM BUTTON */        
        $("#modal-form").validate({
            rules: {
                nombre: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                descripcion: {
                    required: false,
                    minlength: 2,
                    maxlength: 250
                },              
            },
            messages: {  
         
            },
            errorElement: 'span', 
            errorClass: 'help-block help-block-error', 
            focusInvalid: false, 
            ignore: "", 
            highlight: function (element, errorClass, validClass) { 
                $(element).closest('.form-group').addClass('text-danger'); 
                $(element).closest('.form-control').addClass('is-invalid');
            },
            unhighlight: function (element) { 
                $(element).closest('.form-group').removeClass('text-danger'); 
                $(element).closest('.form-control').removeClass('is-invalid');
            },
            success: function (label) {
                label.closest('.form-group').removeClass('text-danger');
            },
            errorPlacement: function (error, element) {
                if ($(element).is('select') && element.hasClass('bs-select')) {
                    error.insertAfter(element);
                } else if ($(element).is('select') && element.hasClass('select2-hidden-accessible')) {
                    element.next().after(error);
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element); 
                }
            },
            invalidHandler: function (event, validator) {                 
            },
            submitHandler: function (form) {
                return true; 
            }
        });

        $("#form-button").click(function(){
            let active=1;
            if($("#modal-form").valid()){
                $('#form-button').html('Guardando..');
                $.ajax({
                    url:"{{route('ost.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        fecha:$('#fecha').val(),
                        tipologia: $('#tipologia').val(),
                        nombre_y_apellido:$('#nomyape').val(),
                        area_reportante:$('#area').val(),
                        lugar_frente:$('#lugar').val(),
                        tarea_observada:$('#tarea').val(),
                        acto_inseguro:$('#acto').val(),
                        condicion_inseguro:$('#condicion').val(),
                        desc_condicion_insegura:$('#descripcion').val(),
                        solu_condicion_insegura:$('#solucion').val(),
                        elimi_condicion_insegura:$('#elimin').val(),
                        prevenir_repeticion:$('#accion').val(),
                        reforzar_acto_seguro:$('#reconocio').val(),
                        responsable_ejecucion:$('#responsable').val(),
                        fecha_vecimiento:$('#fecha_vencimiento').val(),
                        nivel_criticidad:$('#nivel').val(),
                        estado:$('#estado').val(),
                        comentario:$('#comentario').val(),
                        fecha_cierre:$('#fecha_cierre').val(),
                        active:active,
                        _token: $('input[name="_token"]').val()
                    },
                    success:function(data)
                    {  
                        console.log(data);
                        $('#modal').scrollTop(0);
                        if($.isEmptyObject(data.error)){
                            $('#modal').modal('hide');
                            $(".alert-error").css('display','none');
                            $('#modal-form').trigger("reset");
                            $('#form-button').html('Guardar Cambios');
                            var oTable = $('#data-table').dataTable();
                            oTable.fnDraw(false);
                            if($('#form-button').val() == 1){
                                MansfieldRep.notification('OST cargada con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('OST actualizada con exito', 'MansfieldRep', 'success');
                            } 
                        }else{
                            $('#form-button').html('Guardar Cambios');
                            printErrorMsg(data.error);
                        } 
                    }
                });
            }            
        });

        function printErrorMsg(msg) {
            $(".alert-error").css('display','flex');
            $(".print-error-msg").find("ul").html('');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
        /* FORM BUTTON */

        /* CLOSE ALERT */
        $(document).on('click', '#close-alert', function(){       
            $(".alert-error").css('display','none');
        });        
        /* CLOSE ALERT */

        /* ACTION TO CLOSE MODAL */
        $('#modal').on('hidden.bs.modal', function () {
            $("#modal-form").validate().resetForm();
            $(".alert-error").css('display','none');
        });
        /* ACTION TO CLOSE MODAL */


    </script>
@stop

@section('content')
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body">
                    {{-- DATATABLE - OST --}}        
                    <table style="width:100%" class="table table-striped table-bordered table-hover datatable" id="data-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipologia</th>
                                <th>Nombre y Apellido</th>
                                <th>Area reportante</th>
                                <th>Lugar/Frente</th>
                                <th>Tarea observada</th>
                                <th>Acto inseguro</th>
                                <th>Condicion insegura</th>
                                <th>Descripcion del acto o condicion insegura</th>
                                <th>¿Se pudo solucionar en el momento?</th>
                                <th>¿Como se elimino el acto o condincion insegura?</th>
                                <th>Accion para prevenir la repiticion</th>
                                <th>¿Como se reconocio/reforzo el acto seguro?</th>
                                <th>Responsable de ejecutar la tarea</th>
                                <th>Fecha de vencimiento</th>
                                <th>Nivel de criticidad</th>
                                <th>Estado</th>
                                <th>Comentario</th>
                                <th>Fecha de cierre</th>
                                <th style="min-width:50px!important;"></th>    
                            </tr>
                        </thead>      
                    </table>                
                    {{-- END - DATATABLE - OST --}}
                </div>
            </div>
        </div> 
        @csrf
    </div> 

    {{-- MODAL --}}
    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-head">
                    <h5 id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert-error">
                    <div class="print-error-msg">
                        <h4><i class="icon fa fa-ban"></i> El formulario contiene errores</h4>
                        <ul></ul>
                    </div>
                    <button type="button" id="close-alert" class="close" aria-hidden="true">×</button>          
                </div>
                <div class="modal-bod">
                    
                    <form action="post" id="modal-form" name="modal-form" autocomplete="off">
                        <input type="hidden" name="id" id="id">
                        {{-- fecha - datetimpicker --}}
                        <div class="form-group">
                            <label for="nombre" class="col-form-label">Fecha:</label> 
                            <div class="col-sm">
                                <div class="input-group-append" data-target="#fecha" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha" id="fecha" name="fecha"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        {{-- fin - fecha - datetimpicker --}}

                        {{--TIPOLOGIA--}}
                        <div class="form-group">
                            <label for="tipologia" class="col-form-label">Tipologia:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="tipologia" id="tipologia">
                                    <option value="ACTO INSEGURO" selected>ACTO INSEGURO</option>
                                    <option value="CONDICION INSEGURA">CONDICION INSEGURA</option>
                                    <option value="RECONOCIMIENTO">RECONOCIMIENTO</option>
                                </select> 
                            </div>
                        </div>
                        {{--TIPOLOGIA--}}

                        {{--Nombre y Apellido--}}
                        <div class="form-group">
                            <label for="nomyape" class=" col-form-label">Nombre y Apellido:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="nomyape" name="nomyape" placeholder="Nombre y Apellido">
                            </div>
                        </div>
                        {{--Nombre y Apellido--}}

                         {{--Area reportante--}}
                         <div class="form-group">
                            <label for="area" class="col-form-label">Area Reportante:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="area" id="area">
                                    <option value="OPERACIONES" selected>OPERACIONES</option>
                                    <option value="PROCESOS">PROCESOS</option>
                                    <option value="MINA">MINA</option>
                                    <option value="LABORATORIO QUIMICO">LABORATORIO QUIMICO</option>
                                    <option value="SST">SST</option>
                                    <option value="PROYECTO">PROYECTO</option>
                                    <option value="SSOMA">SSOMA</option>
                                    <option value="CAMPAMENTO">CAMPAMENTO</option>
                                    <option value="IT">IT</option>
                                    <option value="LOGISTICA Y ABASTECIMIENTO">LOGISTICA Y ABASTECIMIENTO</option>
                                </select> 
                            </div>
                        </div>
                        {{--Area reportante--}}

                        {{-- LUGAR/FRENTE --}}
                        <div class="form-group">
                            <label for="lugar" class=" col-form-label">Lugar/Frente:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="lugar" name="lugar" placeholder="Lugar/Frente">
                            </div>
                        </div>
                        {{-- LUGAR/FRENTE --}}

                        {{-- TAREA OBSERVADA --}}
                        <div class="form-group">
                            <label for="tarea" class="col-form-label">Tarea Observada:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="tarea" name="tarea" placeholder="Tarea Observada">
                            </div>
                        </div>
                        {{-- TAREA OBSERVADA --}}
                        
                        {{-- ACTO INSEGURO --}}     
                        <div class="form-group">
                            <label for="acto" class="col-form-label">Acto inseguro:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="acto" id="acto">
                                    <option value="SI" selected>SI</option>
                                    <option value="NO" >NO</option>
                                    <option value="NO APLICA" >NO APLICA</option>
                                </select> 
                            </div>
                        </div>
                        {{--ACTO INSEGURO --}}    
                        
                         {{-- CONDICION INSEGURA --}}     
                         <div class="form-group">
                            <label for="condicion" class="col-form-label">Condicion insegura:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="condicion" id="condicion">
                                    <option value="SI" selected>SI</option>
                                    <option value="NO" >NO</option>
                                    <option value="NO APLICA" >NO APLICA</option>
                                </select> 
                            </div>
                        </div>
                        {{-- CONDICION INSEGURA --}}          
                        
                        {{-- DESCRIPCION DEL ACTO O CONDICION  --}}
                        <div class="form-group">
                            <label for="descripcion" class="col-form-label">Descripcion del acto o condicion insegura:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion del acto o condicion insegura">
                            </div>
                        </div>
                        {{-- DESCRIPCION DEL ACTO O CONDICION --}}
                        
                        {{-- Solucion de la condicion INSEGURA --}}     
                        <div class="form-group">
                            <label for="solucion" class="col-form-label">¿Se pudo solucionar en el momento?</label>
                            <div class="col-sm">
                                <select class="form-control company" name="solucion" id="solucion">
                                    <option value="SI" selected>SI</option>
                                    <option value="NO" >NO</option>
                                    <option value="NO APLICA" >NO APLICA</option>
                                </select> 
                            </div>
                        </div>
                        {{--  Solucion de la condicion INSEGURA --}}

                        {{-- Eliminacion del acto o condicion insegura  --}}
                        <div class="form-group ">
                            <label for="elimin" class="col-form-label">¿Como se elimino el acto o condicion insegura?</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="elimin" name="elimin" placeholder="¿Como se elimino el acto o condicion insegura?">
                            </div>
                        </div>
                        {{-- Eliminacion del acto o condicion insegura --}}

                        {{-- ACCION PARA PREVENIR LA REPETICION  --}}
                        <div class="form-group ">
                            <label for="accion" class="col-form-label">Acciones para prevenir la repeticion:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="accion" name="accion" placeholder="¿Como se elimino el acto o condicion insegura?">
                            </div>
                        </div>
                        {{-- ACCION PARA PREVENIR LA REPETICION --}}

                        {{-- COMO SE RECONOCIO/REFORZO EL ACTO SEGURO --}}
                        <div class="form-group ">
                            <label for="reconocio" class="col-form-label">¿Como se reconocio/reforzo el acto seguro?</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="reconocio" name="reconocio" placeholder="¿Como se reconocio/reforzo el acto seguro?">
                            </div>
                        </div>
                        {{-- COMO SE RECONOCIO/REFORZO EL ACTO SEGURO --}}
                        
                        {{-- RESPONSABLE DE EJECUTAR LA ACCION --}}
                        <div class="form-group ">
                            <label for="responsable" class="col-form-label">Responsable de ejecutar la accion:</label>
                            <div class="col-sm">
                            <input type="text" class="form-control" id="responsable" name="responsable" placeholder="Responsable de ejecutar la accion">
                            </div>
                        </div>
                        {{-- RESPONSABLE DE EJECUTAR LA ACCION --}}

                        {{-- fecha - datetimpicker --}}
                        <div class="form-group ">
                            <label for="fecha_vencimiento" class="col-form-label">Fecha de vencimiento:</label> 
                            <div class="col-sm">
                                <div class="input-group-append fecha-picker" data-target="#fecha_vencimiento" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_vencimiento" id="fecha_vencimiento" name="fecha_vencimiento"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        {{-- fin - fecha - datetimpicker --}}

                        {{-- Nivel de criticidad --}}     
                        <div class="form-group ">
                            <label for="nivel" class="col-form-label">Nivel de criticidad:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="nivel" id="nivel">
                                    <option value="ALTO" selected>ALTO</option>
                                    <option value="MEDIO">MEDIO</option>
                                    <option value="BAJO">BAJO</option>
                                </select> 
                            </div>
                        </div>
                        {{-- Nivel de criticidad --}}

                          {{-- Estado --}}     
                          <div class="form-group ">
                            <label for="estado" class="col-form-label">Estado:</label>
                            <div class="col-sm">
                                <select class="form-control company" name="estado" id="estado">
                                    <option value="ABIERTO VENCIDO" selected>ABIERTO VENCIDO</option>
                                    <option value="ABIERTO EN PLAZO">ABIERTO EN PLAZO</option>
                                    <option value="CERRADO">CERRADO</option>
                                </select> 
                            </div>
                        </div>
                        {{-- Estado --}}

                        {{-- COMENTARIO --}}
                        <div class="form-group ">
                            <label for="comentario" class="col-form-label">Comentario:</label>
                            <div class="col-sm">
                            <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Comentario">
                            </div>
                        </div>
                        {{-- COMENTARIO --}}

                         {{-- fecha - datetimpicker --}}
                         <div class="form-group ">
                            <label for="fecha_cierre" class="col-form-label">Fecha de cierre:</label> 
                            <div class="col-sm">
                                <div class="input-group-append fecha-picker" data-target="#fecha_cierre" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_cierre" id="fecha_cierre" name="fecha_cierre"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        {{-- fin - fecha - datetimpicker --}}

                        @csrf
                    </form>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-primary" id="form-button">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}

@stop
