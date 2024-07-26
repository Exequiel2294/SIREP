@extends('adminlte::page')

@section('title', 'Historial')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1><b style="color:#495057;">SIOM</b> Historial por Variable</h1>
        </div> 
    </div>  
@stop

@section('css')
    <style>
        .alto { z-index: auto; overflow:visible; }

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
        .alert-warning {
            color: #fff;
            border-color: #d32535;
            padding: 1rem 1rem .5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            display: none;
        }
        @media(min-width:760px) {
            .alert-error, .alert-warning {
                padding: 2rem 1rem .5rem 3rem;
            }
        }
        .alert-error .print-error-msg h4, .alert-warning .print-warning-msg h4  {
            font-size: 1.25rem;
        }
        @media(min-width:760px) {
        .alert-error .print-error-msg h4, .alert-warning .print-warning-msg h4 {
                font-size: 1.5rem;
            }
        }
        .alert-error .print-error-msg ul, .alert-warning .print-warning-msg ul {
            margin-left: .6rem;
        }
        @media(min-width:760px) {
            .alert-error .print-error-msg ul, .alert-warning .print-warning-msg ul {
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
    <script src="{{asset("vendor/jquery-ui/jquery-ui.js")}}"></script> 
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
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
        var date_selected = moment().subtract(1, "days");
        if ( moment().startOf('month').format('YYYY-MM-DD') === moment().format('YYYY-MM-DD') ) {
            var date_fd = moment().subtract(1, "month").startOf('month').format('YYYY-MM-DD');
        }
        else {
            var date_fd = moment().startOf('month').format('YYYY-MM-DD');
        }
        var date_fh = moment().subtract(1, "days");
        $(function () {
            /* Calendario Fecha desde*/
            $('#fechadesde').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: moment(date_fd).format('DD-MM-YYYY'),
                maxDate: moment().subtract(2, "days"),
                minDate: moment("2022-01-01").format('YYYY-MM-DD'),
                useCurrent: true,
            });
            $("#fechadesde").on("change.datetimepicker", function (e) {
                date_fd = e.date;
            });

            /* Calendario Fecha hasta*/
            $('#fechahasta').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: moment(date_fh).format('DD-MM-YYYY'),
                maxDate: moment().subtract(1, "days"),
            });
            $("#fechahasta").on("change.datetimepicker", function (e) {
                date_fh = e.date;
            });
        })

        $(document).ready(function(){ 
            function getExportTitle()
            {
                return 'Daily Report: '+moment(date_selected).format('YYYY-MM-DD');
            }
            function getDateSelected()
            {
                return moment(date_selected).format('YYYY-MM-DD');
            }

            var table = $('#data-table').DataTable({
                // dom: "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                //         "<'datatables-t'<'datatables-table'tr>>" + 
                //         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",              
                dom:    "<'datatables-p'<'datatables-button'B>>" + 
                        "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                buttons:[
                    {
                        extend: 'copyHtml5', 
                        text: 'Copiar',
                        className: 'buttonclass',
                        exportOptions: {
                            columns: [1,2,3,4,5],
                            
                        },
                    }, 
                    {
                        extend: 'excelHtml5',
                        className: 'buttonclass',
                        filename: 'Historial de Variable '+getDateSelected(),
                        exportOptions: {
                            columns: [1,2,3,4,5],
                        }
                    }, 
                    {
                        extend: 'print',
                        className: 'buttonclass',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: [1,2,3,4,5]
                        }
                    },
                ],         
                lengthMenu: [[35, 65, 95, 125, 155,365], [35, 65, 95, 125, 155,365]],
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('valores.variables')}}",
                    type: 'POST',
                    data: function(d){
                        d.id = $("#variables").val();
                        d.fh = moment(date_fh).format('YYYY-MM-DD');
                        d.fd = moment(date_fd).format('YYYY-MM-DD');
                        d._token = $('input[name="_token"]').val();
                    }
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
                    {data:'id', title:'id', name:'id', visible: false},
                    {data:'area', title:'Area', name:'area', orderable: false, searchable: false, width:'29%'},
                    {data:'nombre', title:'Nombre', name:'nombre', orderable: false, searchable: false, width:'29%'},
                    {data:'unidad', title:'Unidad', name:'unidad', orderable: false, searchable: false, width:'5%'},
                    {data:'fecha', title:'Fecha', name:'fecha', orderable: false, searchable: false},
                    {data:'valor', title:'Valor', name:'valor', orderable: false,searchable: false},
                    {data:'action', title:'', name:'action', orderable: false,searchable: false, width:'25px'},
                ],
                columnDefs: [
                    {
                        targets: [3,4,5],
                        className: "dt-center"
                    }
                ],
                order: [[4, 'asc']],
                
            } );
        }); 
        
        /* FORM BUTTON */        
        $("#form-his").validate({
            rules: {
                variables: {
                    required: true,
                    min: 0
                },
                fechadesde: {
                    required: false,
                },
                fechahasta: {
                    required: true,
                }
                
            },
            messages: {  
                variables: {
                    min: "Este campo es obligatorio."
                }
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

        $("#button-his").click(function(e){
            e.preventDefault();
            if( $("#form-his").valid() ){   
                $('#data-table').DataTable().ajax.reload(null, false);                 
            }                        
        });
        /* FORM BUTTON */



        /* CLOSE ALERT */
        $(document).on('click', '#close-alert', function(){       
            $(".alert-error").css('display','none');
        });        
        /* CLOSE ALERT */

        /* ACTION TO CLOSE MODAL */
        $('#modal').on('hidden.bs.modal', function () {
            $("#modal-form").validate().resetForm();
            $(".alert-warning").css('display','none');
        });
        /* ACTION TO CLOSE MODAL */

         /* EDIT BUTTON */
         $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('historial/'+id+'/edit', function(data){
                if (data['val'] == 1 || data['val'] == 2)
                {
                    $('#form-button').val(0); 
                    $('#modal-title').html('Editar Campo'); 
                    $('#modal-form').trigger("reset"); 
                    $('#modal').modal('show');
                    $('#id').val(data['generic'].id);                          
                    $('#fecha').val(data['generic'].fecha);                   
                    $('#valor').val(data['generic'].valor);   
                    
                    if (data['val'] == 2)
                    {
                        printWarningMsg(data['msg']);
                    }
                }   
                else
                {    
                    Swal.fire({
                        title: data['msg'],
                        icon: 'warning',
                    })                        
                }                          
            })
        });  
        
        function printWarningMsg(msg) {
            $(".alert-warning").css('display','flex');
            $(".print-warning-msg").find("span").html(msg);
        }
        /* EDIT BUTTON */



        /* FORM BUTTON DASHBOARD*/        
        $("#modal-form").validate({
            rules: {
                nvalor: {
                    required: true
                }
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
            if($("#modal-form").valid()){
                $('#form-button').html('Guardando..');
                console.log($('#nvalor').val());
                $.ajax({
                    url:"{{route('dashboard.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        valor:$('#nvalor').val(),
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
                            MansfieldRep.notification('Registro actualizado con exito', 'MansfieldRep', 'success');
                                                      
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
        /* FORM BUTTON DASHCOARD*/


    </script>
@stop

@section('content')
    <div class="row " style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="modal-body" >
                    <form  id="form-his" name="form-his" autocomplete="off">                                              
                        <div class="row" style="padding: 0.5rem 0 0 0.3rem; margin: auto; max-width: 80rem; justify-content: space-around;">
                            <div class="form-group col-md-4">
                                <label>Variables:</label>
                                <select class="form-control" name="variables" id="variables">
                                <option value=-1 selected disabled>Seleccione Variable</option>
                                    @foreach ($variables as $variable)
                                        <option value="{{$variable->id}}">{{$variable->area . " - " .$variable->nombre}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group date col-md-3"  data-target-input="nearest">
                                <label>Fecha desde:</label>
                                <div class="input-group-append " data-target="#fechadesde" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechadesde" id="fechadesde" id="fechadesde"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <div class="form-group date col-md-3"  data-target-input="nearest">
                                <label>Fecha hasta:</label>
                                <div class="input-group-append" data-target="#fechahasta" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechahasta" id="fechahasta" name="fechahasta"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>                                
                            </div>
                            <div class="form-group col-md-1" style="align-self:self-end">
                                <button  class="btn btn-success" id="button-his">Buscar</button>
                            </div>                            
                        </div>              
                        @csrf
                    </form>
                </div>
                <div class="generic-body">        
                    <table style="width:100%" class="table-striped table-bordered table-hover table-sm nowrap" id="data-table"></table>                
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
                <div class="alert-warning">
                    <div class="print-warning-msg">
                        <h4><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h4>
                        <span>Warning alert preview. This alert is dismissable.</span>
                    </div>         
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
                        <div class="form-group row">
                            <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="fecha" name="fecha" placeholder="Nombre" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor" class="col-sm-2 col-form-label">Valor actual</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="valor" name="valor" placeholder="Valor Actual" readonly>
                            </div>
                        </div>     
                        <div class="form-group row">
                            <label for="nvalor" class="col-sm-2 col-form-label">Nuevo valor</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="nvalor" name="nvalor" placeholder="Nuevo Valor">
                            </div>
                        </div>              
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