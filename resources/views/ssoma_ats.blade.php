@extends('adminlte::page')

@section('title', 'ATS')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>ATS</h1>
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
                maxDate: moment().subtract(0, "days"),
                minDate: moment("2023-01-01").format('YYYY-MM-DD'),
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
                        title: 'Listado ATS '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoATS'+moment().local().format('DD/MM/YYYY')
                    }, 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado ATS '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoATS'+moment().local().format('DD/MM/YYYY')            
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoATS'+moment().local().format('DD/MM/YYYY'),
                        customize: function ( doc ) {
                            doc.styles.title.alignment = 'right';
                            doc.styles.title.fontSize = 12;
                        }
                    },
                    {
                        extend: 'print', 
                        text: 'Imprimir',
                        title: 'Listado ATS '+moment().local().format('DD/MM/YYYY'),
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('ats')}}",
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
                    {data:'hora', name:'hora'},
                    {data:'nombre', name:'nombre'},
                    {data:'apellido', name:'apellido'},
                    {data:'area', name:'area'},
                    {data:'tarea', name:'tarea'},
                    {data:'lugar_frente', name:'lugar_frente'},
                    {data:'cantidad_personas', name:'cantidad_personas'},
                    {data:'comentarios', name:'comentarios', orderable: false,searchable: 'false'},
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ],
                order: [[0, 'desc']]            
            });
        });
        /* DATATABLES */

        /* SEARCH EMPLOYEE*/
        $('#get-empleados').click(function(){
            var dni = $('#dni').val();

            const swalWithBootstrapButtons = Swal.mixin({
                    customClass:{
                        confirmButton:'btn btn-succes',
                        cancelButton:'btn btn-danger'
                    },
                    buttonsStyling:true
                });
            if (dni === '' || dni === null){
                swalWithBootstrapButtons.fire({
                    title:'DNI NO INGRESADO',
                    text:"Ingrese un DNI para buscar a una persona",
                    icon:'warning',
                    showCancelButton:false
                })
            }
            else{
                $.get('ats/'+dni+'/GetEmpleado',function(data){
                    if (data['val'] == 1) {
                        $('#nombre').val(data['generic'].Nombre);
                        $('#apellido').val(data['generic'].Apellido);
                        $('#puesto').val(data['generic'].Puesto);
                        $('#area').val(data['generic'].nombre);
                    }
                    else{
                        $('#nombre').val("");
                        $('#apellido').val("");
                        $('#puesto').val("");
                        $('#area').val("");
                        Swal.fire({
                            title: data['msg'],
                            icon: 'error',
                        })
                       
                    }

            });
            }
        })
        /* SEARCH EMPLOYEE*/

        /* ADD BUTTON*/
        $('#add').click(function () { 
            $('#form-button').val(1);     
            $('#modal-title').html('Cargar ATS'); 
            $('#modal-form').trigger("reset"); 
            $('#modal').modal('show');  
            $('#id').val('');   
        });
        /* ADD BUTTON*/

         /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('ats/'+id+'/edit', function(data){
                $('#form-button').val(0); 
                $('#modal-title').html('Editar ATS'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);
                $('#fecha').val(data.fecha);
                $('#hora').val(data.hora);
                $('#dni').val(data.dni);
                $('#nombre').val(data.nombre);
                $('#apellido').val(data.apellido);
                $('#area').val(data.area);
                $('#tarea').val(data.tarea);
                $('#lugar').val(data.lugar_frente);
                $('#cantidad').val(data.cantidad_personas);
                $('#comentario').val(data.comentarios);

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
                    url: "ats/" + id,
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
                fecha:{
                    required:true
                },
                hora:{
                    required:true
                },
                tarea: {
                    required: true,
                    minlength: 5,
                    maxlength: 250
                },
                lugar:{
                    required:true,
                    minlength: 5,
                    maxlength: 250
                },
                cantidad: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 100
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
                    url:"{{route('ats.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        fecha:$('#fecha').val(),
                        hora:$('#hora').val(),
                        dni:$('#dni').val(),
                        tarea:$('#tarea').val(),
                        lugar_frente:$('#lugar').val(),
                        cantidad_personas:$('#cantidad').val(),
                        comentarios:$('#comentario').val(),
                        active:active,
                        _token: $('input[name="_token"]').val()
                    },
                    success:function(data)
                    {  
                        $('#modal').scrollTop(0);
                        if($.isEmptyObject(data.error)){
                            $('#modal').modal('hide');
                            $(".alert-error").css('display','none');
                            $('#modal-form').trigger("reset");
                            $('#form-button').html('Guardar Cambios');
                            var oTable = $('#data-table').dataTable();
                            oTable.fnDraw(false);
                            if($('#form-button').val() == 1){
                                MansfieldRep.notification('ATS cargada con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('ATS actualizada con exito', 'MansfieldRep', 'success');
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
                    <table style="width:100%" class="table table-striped table-bordered table-hover datatable" id="data-table">
                        <thead>
                            <tr>    
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Area</th>
                                <th>Tarea</th>
                                <th>Lugar/Frente</th>
                                <th>Cantidad Personas</th>
                                <th>Comentarios</th>
                                <th style="min-width:50px!important;"></th>            
                            </tr>
                        </thead>      
                    </table>                
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

                        {{-- FECHA --}}
                        <div class="form-group">
                            <label for="nombre" class="col-form-label">Fecha:</label> 
                            <div class="col-sm">
                                <div class="input-group-append" data-target="#fecha" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha" id="fecha" name="fecha"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        {{-- FECHA --}}

                        {{-- HORA --}}
                        <div class="form-group">
                            <label for="nombre" class="col-form-label">Hora</label>
                            <div class="col-sm">
                                <input type="time" class="timepicker form-control" id="hora" name="hora" placeholder="Hora">
                            </div>
                        </div>
                        {{-- HORA --}}

                         {{--DNI--}}
                         <div class="form-group">
                            <label for="dni" class=" col-form-label">DNI:</label>
                                <div class="col-sm">
                                    <div class="input-group-append">
                                        <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI">
                                        <button type="button" class="btn btn-primary" id="get-empleados"><i class="fas fa-search"></i></button>
                                    </div>
                                  </div>
                        </div>
                        {{--DNI--}}

                          {{--Nombre--}}
                          <div class="form-group">
                            <label for="nombre" class=" col-form-label">Nombre:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" disabled>
                            </div>
                        </div>
                        {{--Nombre--}}

                        {{--Apellido--}}
                        <div class="form-group">
                            <label for="apellido" class=" col-form-label">Apellido:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" disabled>
                            </div>
                        </div>
                        {{--Apellido--}}

                        {{--Area--}}
                        <div class="form-group">
                            <label for="area" class=" col-form-label">Area:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="area" name="area" placeholder="Area" disabled>
                            </div>
                        </div>
                        {{--Area--}}

                        {{-- TAREA --}}
                        <div class="form-group">
                            <label for="tarea" class="col-form-label">Tarea</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="tarea" name="tarea" placeholder="Tarea">
                            </div>
                        </div>  
                        {{-- TAREA --}}

                        {{-- LUGAR/FRENTE --}}
                        <div class="form-group">
                            <label for="lugar" class="col-form-label">Lugar/Frente</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="lugar" name="lugar" placeholder="Lugar">
                            </div>
                        </div>  
                        {{-- LUGAR/FRENTE --}}

                        {{-- CANTIDAD DE PERSONAS --}}
                        <div class="form-group">
                            <label for="cantidad" class="col-form-label">Cantidad de Personas</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad de Personas">
                            </div>
                        </div>
                        {{-- CANTIDAD DE PERSONAS --}}

                        {{-- COMENTARIO --}}
                        <div class="form-group">
                            <label for="comentario" class="col-form-label">Comentarios</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Comentarios">
                            </div>
                        </div>
                        {{-- COMENTARIO --}}

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
