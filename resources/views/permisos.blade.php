@extends('adminlte::page')

@section('title', 'Permisos')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Asignación Variables</h1>
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

        /* PROPIOS VISTA */
        #vble-table_wrapper
        {
            width: 95%;
        }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination
        {
            justify-content: center!important;
            margin-top: 1.5rem!important;
        }
        .checkvble
        {
            width: 1rem;
            height: 1rem;
        }

        @media(min-width:400px) {
            .user-select{
                display: flex;
                flex-direction: column!important;
                width: 90%;
                margin: 0 auto;
                margin-top: 1rem;
            }

            .user-select > div{
                display: grid;
            }

            .toolbar{
                display: none!important;
            }

            #fieldset-permisos
            {
                width: 90%;
                margin: 2rem auto;
                max-width: 968px!important;
            }

            #fieldset-permisos > legend
            {
                font-size: 1rem!important;
            }

            .fieldset-div
            {
                display: flex;
                flex-direction: column!important;
                width: 90%;
                margin: 0 auto;
                margin-top: 1rem;
            }

            .fieldset-div > div
            {
                display: grid;
                margin: .5rem 0 1rem 0;
            }
        }

        @media(min-width:768px) {
            .user-select{
                justify-content: space-around!important;
                flex-direction: row!important;
                margin-top: 2rem;
            }
            .user-select > div{
                display: flow-root!important;
            }    
            
            .user-select > div:first-child{
                flex: 0 1 50%
            }

            .toolbar{
                display: flex!important;
            }

            #fieldset-permisos
            {
                width: 80%;
            }

            #fieldset-permisos > legend
            {
                font-size: 1rem!important;
            }

            .fieldset-div
            {
                justify-content: space-around!important;
                flex-direction: row!important;
                padding-bottom: .75rem;
            }

            .fieldset-div > div{
                display: flow-root!important;
            }

            .fieldset-div > div:first-child{
                flex:0 1 80%;
            }

            .user-select{
                margin-top: 2.5rem!important;
                margin-bottom: 2rem!important;
            }
        }

        @media(min-width:1100px) {
            #fieldset-permisos > legend
            {
                font-size: 1.1rem!important;
            }

            .toolbar
            {
                font-size: 1.1rem!important;
            }
        }

        @media(min-width:960px) {
            .user-select{
                justify-content: flex-start!important;
                margin-bottom: 1rem;
                width: 98%;
            }
            .user-select  div{
                margin: 0 .5rem 0 .5rem;
            }
        }


    </style> 
@stop

@section('js')
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
    <script>
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#permisos-table').dataTable();
                    oTable.fnAdjustColumnSizing();
                }, 
            350);
        });

        $(function () {
            $("#select2").on("change", function (e) {            
                $('#permisos-table').DataTable().ajax.reload(null, false);
                $('#vble-table').DataTable().ajax.reload(null, false);            
            });

            $('#btn-asignar').click(function(e){
                e.preventDefault();
            });        

            /* ACTION TO CLOSE MODAL */
            $('#modal-vbles').on('hidden.bs.modal', function () {
                $('#permisos-table').DataTable().ajax.reload(null, false);
                $('#vble-table').DataTable().ajax.reload(null, false);
            });
            /* ACTION TO CLOSE MODAL */
        });

        
        $(document).ready(function (){     
            $('#fieldset-permisos').hide(); 
            /* DATATABLES PERMISOS DEL USUARIO*/          
            var dt =  $("#permisos-table").DataTable({              
                dom:    "<'row data-search'<'col-md-6 toolbar'><'col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row data-buttons'<'col-sm-12'p>>",            
                lengthMenu: [[10, 25], [10, 25]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{    
                    url: "{{route('permisos.getuservbles') }}",
                    type: 'POST',
                    data: function(d){
                        d.id = $( "#select2" ).val(),
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
                    {data:'id', name:'id'},
                    {data:'area', name:'area'},
                    {data:'nombre', name:'nombre'},
                    {data:'descripcion', name:'descripcion'}
                ],
                order: [[0, 'asc'],[1, 'asc'],[2, 'asc']]            
            });
            $("div.toolbar").html('Variables con permisos de edición para el usuario:');

            /* DATATABLES disponibles para el usuario*/
            var t = $("#vble-table").DataTable({    
                dom:    "<'row data-search'<'col-sm-12'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row data-buttons'<'col-sm-12'p>>",  
                lengthMenu: [[10, 25], [10, 25]],
                processing: true,
                bInfo: true,
                //serverSide: true,
                responsive: true,
                // scrollX : true,
                ajax:{    
                    url: "permisos/getvariables",
                    type: 'POST',
                    data: function(d){
                        d.id = $( "#select2" ).val(),
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
                    {data:'id', name:'id' , visible: false, orderable: false,searchable: false},
                    {data:'area', name:'area'},
                    {data:'descripcion', name:'descripcion', 
                        render: function(data,type,row){
                            if (row['area_id'] == 2)
                            {
                                return row['nombre'];
                            }
                            return data;
                        }
                    },
                    {data:'action', name: 'action', orderable: false,searchable: false, width:'50px'}
                ],
                columnDefs: [
                    {
                        targets:3,
                        className: 'dt-body-center',
                        width: 50
                    }
                ]          
            }); 
        });

        $('#btn-copiar').click(function () { 
            console.log('click');
            if ($('#fieldset-permisos').is(":visible"))
            {
                $('#fieldset-permisos').hide(); 
                $("#modal-form").validate().resetForm();
            }
            else{
                $('#fieldset-permisos').show();  
            }
        });

        /*INICIO SELECCIONAR CHECKBOX*/
        $(document).on('click', '.checkvble', function(){ 
            if ($("#select2" ).val() !='')
            { 
                var data = {
                    variable_id: $(this).data('vbleid'),
                    user_id: $( "#select2" ).val(),
                    _token: $('input[name=_token]').val()
                };
                if ($(this).is(':checked')) {
                    data.estado = 1
                } else {
                    data.estado = 0
                }
                ajaxRequest('permisos/check', data);             
            }     
        });    
        function ajaxRequest (url, data) {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (respuesta) {
                    MansfieldRep.notification(respuesta.respuesta, 'MansfieldRep', 'success');
                }
            });
        }
        /*FIN SELECCIONAR CHECKBOX*/   

        /* FORM BUTTON */        
        $("#modal-form").validate({
            rules: {
                select2: {
                    required: true
                },
                usercopy_id: {
                    required: true,
                    notEqualTo : "#select2"
                }            
            },
            messages: {  
                usercopy_id: {
                    notEqualTo: "Los usuarios no pueden ser iguales"
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

        $("#form-button").click(function(){
            if($("#modal-form").valid()){
                $('#form-button').html('Guardando..');
                $.ajax({
                    url:"{{route('permisos.load') }}",
                    method:"POST",
                    data:{
                        user_id: $("#select2").val(),
                        usercopy_id:$('#usercopy_id').val(),
                        _token: $('input[name="_token"]').val()
                    },
                    success:function(data)
                    {  
                        $("#modal-form").validate().resetForm();
                        $('#form-button').html('Guardar');
                        $('#fieldset-permisos').hide();
                        $('#permisos-table').DataTable().ajax.reload(null, false);
                        $('#vble-table').DataTable().ajax.reload(null, false);
                        $('#usercopy_id').val('');
                        MansfieldRep.notification('Permisos copiados con exito', 'MansfieldRep', 'success');
                    }
                });
            }            
        });
        /* FORM BUTTON */
    </script>
@stop

@section('content')
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body"> 
                    <form action="post" id="modal-form" name="modal-form" autocomplete="off">  
                        <div class="user-select">
                            <div class="form-group">
                                <select name="select2" id="select2" class="form-control select2">
                                    <option value="" selected="selected">Seleccione un usuario</option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="btn-asignar" data-toggle="modal" data-target="#modal-vbles">Asignar Variables</button>
                            </div>
                            <div class="form-group">
                                <a href="javascript:void(0)" name="btn-asignar" id="btn-copiar" title="Copiar permisos" class="btn btn-primary">Copiar Permisos</a>
                            </div>
                        </div>
                        <fieldset id="fieldset-permisos" class="form-group border">
                            <legend class="w-auto px-2">Seleccionar Usuario desde el cual se van a transferir los permisos</legend>                         
                            <div class="form-group row fieldset-div">
                                <div class="form-group">
                                    <select class="form-control" name="usercopy_id" id="usercopy_id">
                                        <option value="" selected disabled>Seleccione Usuario</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" id="form-button">Aceptar</button>
                                </div>
                            </div>
                        </fieldset>               
                        @csrf
                    </form>
                    <div class="card-body">
                        <table style="width:100%" class="table table-striped table-bordered table-hover datatable" id="permisos-table">
                            <thead>
                                <tr>    
                                    <th>Id</th>
                                    <th>Área</th>
                                    <th>Variable</th>
                                    <th>Descripción</th>           
                                </tr>
                            </thead>      
                        </table>  
                    </div>
                    
                </div>
            </div>
        </div> 
        @csrf
    </div> 


    {{-- MODAL --}}
    <div class="modal fade" id="modal-vbles" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-head">
                    <h5 id="modal-title">Asignación de Variable</h5>
                    <button type="button" class="close" id="close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row" style="justify-content: center; overflow:auto;">
                        <table style="width:100%" class="table table-sm table-striped table-bordered table-hover datatable" id="vble-table">
                            <thead>
                                <tr>  
                                    <th>Id</th>
                                    <th>Área</th>
                                    <th>Descripción</th>   
                                    <th style="width:50px!important;"></th>          
                                </tr>
                            </thead>      
                        </table>             
                        @csrf
                    </div> 
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    

@stop