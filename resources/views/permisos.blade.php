@extends('adminlte::page')

@section('title', 'Permisos')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Permisos</h1>
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

        /*DATATABLES LARGE TABLES*/
        td.details-control {
            background: url('../assets/DataTables/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('../assets/DataTables/details_close.png') no-repeat center center;
        }
         /*DATATABLES LARGE TABLES*/
    </style> 
@stop

@section('js')
{{-- <script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2() 
    })
</script> --}}
<script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
<script>
    $(function () {
        $("#select2").on("change", function (e) {
            $('#permisos-table').DataTable().ajax.reload(null, false);
            $('#vble-table').DataTable().ajax.reload(null, false);
        });

        $('#btn-asignar').click(function(e){
            e.preventDefault();
            //alert($( "#select2" ).val());
        });
        
        $("#close-modal").click( function (e) {
            $('#vble-table').DataTable().ajax.reload(null, false);
        });
    });

    /* DATATABLES PERMISOS DEL USUARIO*/
    $(document).ready(
        function (){     
            
            var dt =  $("#permisos-table").DataTable({
                
                lengthMenu: [[10, 25], [10, 25]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{    
                    //url: "permisos/"+id,
                    url: "{{route('permisos.load') }}",
                    type: 'POST',
                    data: function(d){
                        d.id = $( "#select2" ).val(),
                        d._token = $('input[name="_token"]').val();
                    }
                    // data:{
                    //     id: $("#select2").val(),
                    //     _token: $('input[name="_token"]').val()},
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
                    {data:'nombre', name:'nombre'},
                    {data:'descripcion', name:'descripcion'},
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ],
                order: [[0, 'asc'],[1, 'asc'],[2, 'asc']]            
            });
        }
    );
    /* DATATABLES disponibles para el usuario*/
    $(document).ready(
        function (){     
            //$("#vble-table").DataTable();
            
            var t = $("#vble-table").DataTable({
                
                select: {
                    style:'multi',
                },
                lengthMenu: [[10, 25], [10, 25]],
                //processing: true,
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
                    {data:'id', name:'id' , width:'10px'},
                    {data:'nombre', name:'nombre',width:'10px'},
                    {data:'descripcion', name:'descripcion',width:'10px'},
                ],
                // order: [[0, 'asc'],[1, 'asc'],[2, 'asc']]            
            });

            $('#vble-table tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
                $(this).toggleClass('bg-primary');
            });
            $('#button-table').click( function (e) {
                //alert( t.rows('.selected').data().length +' row(s) selected' );
                //console.log(t.rows('.selected').data());
                e.preventDefault();
                let v = [];
                for (let i = 0; i < t.rows('.selected').data().length; i++) {
                    v.push(t.rows('.selected').data()[i].id);
                }
                if (v.length !== 0) {
                    console.log(v);
                    $.ajax({
                        url:"{{route('permisos.insertar') }}",
                        method:"POST",
                        data:{
                            id: $( "#select2" ).val(),
                            valor: v,
                            tam: v.length,
                            _token: $('input[name="_token"]').val()
                        },
                        success:function(data)
                        {  
                            $('#modal-vbles').scrollTop(0);
                            $('#modal-vbles').modal('hide');
                            MansfieldRep.notification('Registro cargado con exito', 'MansfieldRep', 'success');
                            $('#permisos-table').DataTable().ajax.reload(null, false);
                        }
                    });
                }else{
                    console.log('no hay datos');
                }
            });
            
        }
    );
</script>

@stop

@section('content')
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body">  
                    <div class="row m-3">
                        <select name="select2" id="select2" class="form-control select2 mr-2" style="width: 50%;">
                            {{-- <option value="0" selected="selected">Seleccione un usuario</option> --}}
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                        <button 
                            type="button"
                            class="btn btn-success" 
                            id="btn-asignar"
                            data-toggle="modal" 
                            data-target="#modal-vbles"
                        >Asignar Variables
                        </button>
                        <button 
                            type="button"
                            class="btn btn-primary ml-2" 
                            id="btn-asignar"
                            data-toggle="modal" 
                            data-target="#modal-copiar"
                        >Copiar Permisos
                        </button>
                    </div>

                    <h6 class="m-3 mt-4">Variables con permisos de edición para el usuario:</h6>

                    <div class="card-body">
                        <table style="width:100%" class="table table-striped table-bordered table-hover datatable" id="permisos-table">
                            <thead>
                                <tr>    
                                    <th>Id</th>
                                    <th>Variable</th>
                                    <th>Descripción</th>
                                    <th style="min-width:50px!important;"></th>            
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
                    <h5 id="modal-title">Variables</h5>
                    <button type="button" class="close" id="close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row" style="justify-content: center; overflow:auto;">
                        <table style="width:100%" class="table table-sm table-striped table-bordered table-hover datatable m-4" id="vble-table">
                            <thead>
                                <tr>  
                                    <th>Id</th>
                                    <th>Variable</th>
                                    <th>Descripción</th>            
                                </tr>
                            </thead>      
                        </table>             
                        @csrf
                    </div> 
                </div>
                
                <div class="modal-foot">
                    <button type="button" class="btn btn-primary" id="button-table" name="button-table">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    

@stop