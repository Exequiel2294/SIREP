@extends('adminlte::page')

@section('title', 'Historial')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Historial por Variable</h1>
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

        $(function () {
            /* Calendario Fecha desde*/
            $('#fechadesde').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: moment().startOf('year'),
                maxDate: moment().subtract(2, "days"),
                minDate: moment().startOf('year'),
                useCurrent: false
            });

            /* Calendario Fecha hastq*/
            $('#fechahasta').datetimepicker({
                format: 'DD/MM/YYYY',
                maxDate: moment().subtract(1, "days")
            });

            /*DATATABLE*/
            let table = $('#data-table').DataTable( {
                        destroy:true,
                        dom: "<'datatables-p'<'datatables-button'B>>" +
                                "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                                "<'datatables-t'<'datatables-table'tr>>" + 
                                "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                        
                        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                        processing: true,
                        //bInfo: true,
                        //serverSide: true,
                        responsive: true,
                        scrollX : true,
                        
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
                            {title:'id' , orderable: false, searchable: false},
                            {title:'area' , orderable: false, searchable: false},
                            {title:'nombre' , orderable: false, searchable: false},
                            {title:'unidad' , orderable: false, searchable: false},
                            {title:'fecha' , orderable: false, searchable: false},
                            {title:'valor', orderable: false,searchable: false},
                            {data:'action', title:'acciones',name:'action'},
                        ],
                        order: [[0, 'asc']],
                        
                    } );
        })
        

        
 

        /* FORM BUTTON */        
        $("#form-his").validate({
            rules: {
                variables: {
                    required: true,
                },
                fechadesde: {
                    required: false,
                },
                fechahasta: {
                    required: true,
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

        $("#button-his").click(function(e){
            e.preventDefault();
            if( $("#form-his").valid() ){
                
                    
                    let id = $("#variables").val();
                    let fd = $('#fechadesde').val();
                    let fh = $('#fechahasta').val();
                    
                        
                    // $('#data-table').DataTable().destroy();
                    let table = $('#data-table').DataTable( {
                        destroy:true,
                        dom: "<'datatables-p'<'datatables-button'B>>" +
                                "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                                "<'datatables-t'<'datatables-table'tr>>" + 
                                "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                        
                        lengthMenu: [[25, 50, 100], [25, 50, 100]],
                        processing: true,
                        //bInfo: true,
                        serverSide: true,
                        responsive: true,
                        scrollX : true,
                        ajax:{                
                            url: "{{route('valores.variables')}}",
                            type: 'POST',
                            data: function(d){
                                d.id = id;
                                d.fh = fh;
                                d.fd = fd;
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
                            {data:'id',title:'id' ,name:'id', orderable: false, searchable: false},
                            {data:'area',title:'area' ,name:'area', orderable: false, searchable: false},
                            {data:'nombre',title:'nombre' ,name:'nombre', orderable: false, searchable: false},
                            {data:'unidad',title:'unidad' ,name:'unidad', orderable: false, searchable: false},
                            {data:'fecha',title:'fecha' ,name:'fecha', orderable: false, searchable: false},
                            {data:'valor', title:'valor',name:'valor', orderable: false,searchable: false},
                            {data:'action', title:'action',name:'action'},
                        ],
                        order: [[0, 'asc']],
                        
                    } );
                    // $('#modal').scrollTop(0);
                    // if($.isEmptyObject(data.error)){
                    //     $('#modal').modal('hide');
                    //     $(".alert-error").css('display','none');
                    //     $('#modal-form').trigger("reset");
                    //     $('#form-button').html('Guardar Cambios');
                    //     var oTable = $('#data-table').dataTable();
                    //     oTable.fnDraw(false);
                    //     if($('#form-button').val() == 1){
                    //         MansfieldRep.notification('Area cargada con exito', 'MansfieldRep', 'success');
                    //     }   
                    //     else{
                    //         MansfieldRep.notification('Area actualizada con exito', 'MansfieldRep', 'success');
                    //     } 
                    // }else{
                    //     $('#form-button').html('Guardar Cambios');
                    //     printErrorMsg(data.error);
                    // } 
                    
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
        // $('#modal').on('hidden.bs.modal', function () {
        //     $("#modal-form").validate().resetForm();
        //     $(".alert-error").css('display','none');
        // });
        /* ACTION TO CLOSE MODAL */

         /* EDIT BUTTON */
         $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('historial/'+id+'/edit', function(data){
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Campo'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);                          
                $('#fecha').val(data.fecha);                   
                $('#valor').val(data.valor);                   
                $("#nuevovalor").val(data.nvalor).attr("selected", "selected");
            })
        });      
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
                            if($('#form-button').val() == 1){
                                MansfieldRep.notification('Registro cargado con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('Registro actualizado con exito', 'MansfieldRep', 'success');
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
        /* FORM BUTTON DASHCOARD*/


    </script>
@stop

@section('content')
    <div class="row " style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="modal-body" >
                    <form  id="form-his" name="form-his" autocomplete="off">
                                              
                        <div class="form-row">
                            <div class="form-group col-sm-4">
                                <label>Variables:</label>
                                <select class="form-control" name="variables" id="variables">
                                    <option></option>
                                    @foreach ($variables as $variable)
                                        <option value="{{$variable->id}}">{{$variable->area . " - " .$variable->nombre}}</option>
                                    @endforeach
                                </select> 
                            </div>

                            <div class="form-group date col-sm-"  data-target-input="nearest">
                                <label>Fecha desde:</label>
                                <div class="input-group-append " data-target="#fechadesde" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechadesde" id="fechadesde" id="fechadesde"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>

                            <div class="form-group date col-sm-4"  data-target-input="nearest">
                                <label>Fecha hasta:</label>
                                <div class="input-group-append" data-target="#fechahasta" data-toggle="datetimepicker">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechahasta" id="fechahasta" name="fechahasta"/>
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    <button  class="btn btn-success ml-2" id="button-his">Buscar</button>
                                </div>
                                
                            </div>
                            
                        </div>              
                        @csrf
                    </form>
                </div>
                <div class="generic-body">        
                    <table style="width:100%" class="table table-striped table-bordered table-hover datatable table-sm" id="data-table">
                        
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
                                <input type="text" class="form-control" id="nvalor" name="nvalor" placeholder="Nuevo Valor">
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