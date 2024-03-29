@extends('adminlte::page')

@section('title', 'Empleados')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Empleados</h1>
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
                        title: 'Listado Emepleados '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoEmepleados'+moment().local().format('DD/MM/YYYY')
                    }, 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado Emepleados '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoEmepleados'+moment().local().format('DD/MM/YYYY')            
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoEmepleados'+moment().local().format('DD/MM/YYYY'),
                        customize: function ( doc ) {
                            doc.styles.title.alignment = 'right';
                            doc.styles.title.fontSize = 12;
                        }
                    },
                    {
                        extend: 'print', 
                        text: 'Imprimir',
                        title: 'Listado Emepleados '+moment().local().format('DD/MM/YYYY'),
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('empleados')}}",
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
                    {data:'dni', name:'dni'},
                    {data:'nombre', name:'nombre'},
                    {data:'apellido', name:'apellido'},
                    {data:'puesto', name:'puesto'},
                    {data:'sector', name:'sector'},
                    {data:'correo', name:'correo'},
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ],
                order: [[0, 'asc']]            
            });

            // AJAX REQUEST traer los sectores de la tabla
            $.ajax({
                url:"{{route('empleados.getsectores')}}",
                type:'GET',
                dataType:'json',
                success:function(response)
                {
                    var len = 0;
                    if(response['data'] != null){
                        len=response['data'].length;
                    }
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id=response['data'][i].id;
                            var nombre=response['data'][i].sector;
                            var area = response['data'][i].area;
                            var optionSector = "<option value='"+id+"'>"+area+" - "+nombre+"</option></div>";
                            $("#sector").append(optionSector);
                        }
                    }

                }
                
            })

            // AJAX REQUEST traer los cargos de la tabla
            $.ajax({
                url:"{{route('empleados.getcargos')}}",
                type:'GET',
                dataType:'json',
                success:function(response)
                {
                    var len = 0;
                    if(response['result'] != null){
                        len=response['result'].length;
                    }
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id=response['result'][i].id;
                            var cargo = response['result'][i].cargo;
                            var optionCargo = "<option value='"+id+"'>"+cargo+"</option></div>";
                            $("#cargo").append(optionCargo);
                        }
                    }

                }
                
            })

            // AJAX REQUEST traer los correos de la tabla
            $.ajax({
                url:"{{route('empleados.getcorreos')}}",
                type:'GET',
                dataType:'json',
                success:function(response)
                {
                    var len = 0;
                    if(response['data'] != null){
                        len=response['data'].length;
                    }
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id=response['data'][i].id;
                            var email = response['data'][i].email;
                            var optionCorreo = "<option value='"+id+"'>"+email+"</option></div>";
                            $("#correo").append(optionCorreo);
                        }
                    }

                }
                
            })
        });
        /* DATATABLES */

        /* ADD BUTTON*/
        $('#add').click(function () { 
            $('#form-button').val(1);     
            $('#modal-title').html('Cargar Empleado'); 
            $('#modal-form').trigger("reset"); 
            $('#modal').modal('show');  
            $('#id').val('');   
        });
        /* ADD BUTTON*/

        /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('empleados/'+id+'/edit', function(data){
                var select = data.id;
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Empleado'); 
                $('#modal-form').trigger("reset");
                $('#modal').modal('show');
                $('#id').val(data.id);
                $('#dni').val(data.dni);
                $('#nombre').val(data.nombre);
                $('#apellido').val(data.apellido);
                $('#cargo').val(data.cargo);
                $('#puesto').val(data.puesto);
                $('#correo').val(data.correo);
                $('#sector').val(data.sector);
                // $('input[name="_token"]').val()                   
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
                    url: "empleados/" + id,
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
            //MIRAR SI SE PUEDE DAR LA VALIDACION PARA QUE DE POR DNI
            rules: {
                nombre: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
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
                    url:"{{route('empleados.load') }}",
                    method:"POST",
                    data:{
                        id:$('#id').val(),
                        dni:$('#dni').val(),
                        nombre:$('#nombre').val(),
                        apellido:$('#apellido').val(),
                        id_cargo:$('#cargo').val(),
                        puesto:$('#puesto').val(),
                        id_usuario:$('#correo').val(),
                        id_sector:$('#sector').val(),
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
                                MansfieldRep.notification('Emepleado cargada con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('Inspeccion actualizada con exito', 'MansfieldRep', 'success');
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
                    {{-- DATATABLE - INSPECCION --}}        
                    <table style="width:100%" class="table table-striped table-bordered table-hover datatable" id="data-table">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Puesto</th>
                                <th>Sector</th>
                                <th>Correo</th>
                                <th style="min-width:50px!important;"></th>    
                            </tr>
                        </thead>      
                    </table>                
                    {{-- END - DATATABLE - INSPECCION --}}
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

                         {{--DNI--}}
                         <div class="form-group">
                            <label for="dni" class="col-form-label">DNI:</label>
                            <div class="col-sm">
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI">
                            </div>
                        </div>
                        {{--DNI--}}

                        {{--Nombre--}}
                        <div class="form-group">
                            <label for="nombre" class="col-form-label">Nombre:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" >
                            </div>
                        </div>
                        {{--Nombre--}}

                        {{--Apellido--}}
                        <div class="form-group">
                            <label for="apellido" class="col-form-label">Apellido:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" >
                            </div>
                        </div>
                        {{--Apellido--}}

                         {{--Dropdown cargos--}}
                         <div class="form-group">
                            <label for="cargo" class="col-form-label">Cargos:</label>
                            <div class="col-sm">
                                <select class='form-control' id='cargo' name='sel_cargo'>
                                <option value='0'>Seleccione un cargo</option>
                                </select>
                            </div>
                        </div>
                        {{--Dropdown cargos--}}

                        {{--Puesto--}}
                        <div class="form-group">
                            <label for="puesto" class="col-form-label">Puesto:</label>
                            <div class="col-sm">
                              <input type="text" class="form-control" id="puesto" name="puesto" placeholder="Puesto">
                            </div>
                        </div>
                        {{--Puesto--}}

                        {{--Dropdown correos--}}
                        <div class="form-group">
                            <label for="correo" class="col-form-label">Correo:</label>
                            <div class="col-sm">
                                <select class='form-control' id='correo' name='sel_correo'>
                                <option value='0'>Seleccione un correo</option>
                                </select>
                            </div>
                        </div>
                        {{--Dropdown correos--}}

                        {{--Dropdown sector--}}
                        <div class="form-group">
                            <label for="sector" class="col-form-label">Sector:</label>
                            <div class="col-sm">
                                <select class='form-control' id='sector' name='sel_sector'>
                                <option value='0'>Seleccione un sector</option>
                                </select>
                            </div>
                        </div>
                        {{--Dropdown sector--}}

                       
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
