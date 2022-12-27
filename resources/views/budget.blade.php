@extends('adminlte::page')

@section('title', 'Budget')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Budget</h1>
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


        $(document).ready(function(){ 
            var table = $('#data-table').DataTable({             
                dom:    "<'row'<'col-sm-12'tr>>",     
                lengthMenu: [[365], [365]],
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('budget.getvalores')}}",
                    type: 'POST',
                    data: function(d){
                        d.variable_id = $("#variable_id").val();
                        d.anio = $("#anio").val();
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
                    {data:'nombre', title:'Nombre', name:'nombre', orderable: false, searchable: false, width:'auto'},
                    {data:'fecha', title:'Año', name:'fecha', orderable: false, searchable: false, width:'20%',
                        render: function(data){
                            return moment(data, 'YYYY-MM-DD').locale('es').format('MMMM').toUpperCase();
                        }
                    },
                    {data:'valor', title:'Valor', name:'valor', orderable: false, searchable: false, width:'20%',
                        render: function(data){
                            if (data == null){
                                return '-';
                            }
                            else{
                                return data;
                            }

                        }
                    },
                    {data:'action', title:'', name:'action', orderable: false,searchable: false, width:'25px'},
                ],
                columnDefs: [
                    {
                        targets: [3],
                        className: "dt-center"
                    }
                ],
                
            } );
        }); 
        
        /* SELECT BUTTON */        
        $("#select-form").validate({
            rules: {                
                area_id: {
                    required: true
                }, 
                variable_id: {
                    required: true
                },                
                anio: {
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

        $("#select-button").click(function(){
            if($("#select-form").valid()){  
                $("div.datatables-length").html('<b>' + $('#variable_id option:selected').text() + '</b>&nbspcorrespondientes al año &nbsp<b>' + $('#anio option:selected').text()+'</b>.');  
                $('#data-table').DataTable().ajax.reload(null, false); 
            } 
        });
        /* SELECT BUTTON */



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
            $.get('budget/'+id+'/edit', function(data){
                $('#modal-title').html('Editar Budget'); 
                $('#modal').modal('show');
                $('#id').val(data.id);                        
                $('#valor').val(data.valor);                              
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
                valor: {
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
                    url:"{{route('budget.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        valor:$('#valor').val(),
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

        /* CHANGE AREA */
        $(document).on('change','.area',function(){
            var _token = $('input[name="_token"]').val();
            var area_id=$(this).val();
            $.ajax({
                url:"{{route('budget.getvariables') }}",
                type:"POST",
                dataType: "json",
                data:{_token:_token,area_id:area_id},
                success:function(data){
                    $("#variable_id").prop( "disabled", false );  
                    $('#variable_id').html('');
                    $('#variable_id').append(data.result);
                },
                error:function(){

                }
            });
        });
        /* END CHANGE AREA */


    </script>
@stop

@section('content')
    <div class="row " style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body">  
                    <div class="card-body" style="margin:auto; width:95%"> 
                        <form action="post" id="select-form" name="select-form" autocomplete="off">                            
                                <!-- <legend class="w-auto">Seleccionar Área y Mes</legend> -->
                                <div class="row" style="justify-content: space-around;">
                                    <div class="form-group col-md-3">
                                        <label for="area_id" class="col-form-label">Área</label>
                                        <div>
                                            <select class="form-control area" name="area_id" id="area_id">
                                                <option value="" selected disabled>Seleccione Área</option>
                                                @foreach($areas as $id => $nombre)
                                                        <option value="{{$id}}">{{$nombre}}</option>
                                                @endforeach                                                                  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="variable_id" class="col-form-label">Variable</label>
                                        <div>
                                            <select class="form-control" name="variable_id" id="variable_id">
                                                <option value="" selected disabled>Seleccione Variable</option>                                       
                                            </select> 
                                        </div>
                                    </div>       
                                    <div class="form-group col-md-3">
                                        <label for="anio" class="col-form-label">Año</label>
                                        <div>                                            
                                            <select class="form-control" name="anio" id="anio">
                                                <option value="" selected disabled>Seleccione Año</option>
                                                <option value=2022>2022</option>
                                                <option value=2023>2023</option>                                                                                    
                                            </select>  
                                        </div>
                                    </div>                              
                                    <div class="form-group col-md-2" style="align-self:self-end">                                    
                                        <button type="button" class="btn btn-primary" id="select-button">Cargar</button>
                                    </div> 
                                </div>                                        
                            @csrf
                        </form> 
                    </div>      
                    <div class="card-body" style="margin:auto; width:95%">
                        <div class="datatables-s">
                            <div class="datatables-length">
                            </div> 
                            <div class="datatables-filter"></div>
                        </div>
                        <table style="width:100%" class="table table-striped table-bordered table-hover datatable table-sm" id="data-table"></table>  
                    </div>           
              
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
                            <label for="valor" class="col-sm-2 col-form-label">Valor</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="valor" name="valor">
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