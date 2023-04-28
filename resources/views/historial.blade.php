@extends('adminlte::page')

@section('title', 'Historial')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>SIOM - Historial</h1>
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
                        title: 'Listado Historial '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoHistorial'+moment().local().format('DD/MM/YYYY')
                    }, 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado Historial '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoHistorial'+moment().local().format('DD/MM/YYYY')            
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoHistorial'+moment().local().format('DD/MM/YYYY'),
                        customize: function ( doc ) {
                            doc.styles.title.alignment = 'right';
                            doc.styles.title.fontSize = 12;
                        }
                    },
                    {
                        extend: 'print', 
                        text: 'Imprimir',
                        title: 'Listado Historial '+moment().local().format('DD/MM/YYYY'),
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('historial')}}",
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
                    {data:'fecha', name:'fecha',
                        render: function(data){
                            if(data != null)
                            {
                                return moment.utc(data).local().format("DD/MM/Y HH:mm");
                            }
                            else
                            {
                                return data;
                            }
                        }
                    },
                    {data:'variable', name:'variable',
                        render: function(data,type,row){
                            switch (true)
                            {
                                case row['subcategoria_id'] == 10005:
                                    return 'MatMin_'+row['nombre'];
                                break;
                                case row['subcategoria_id'] == 10006:
                                    return 'Rem_'+row['nombre'];
                                break;
                                case row['subcategoria_id'] == 10007:
                                    return 'MovTot_'+row['nombre'];
                                break;
                                case row['subcategoria_id'] == 10008:
                                    return 'DispMec_'+row['nombre'];
                                break;
                                default:
                                    return data.substring(5);
                                break;
                            }                                
                        }
                    },
                    {data:'fecha_data', name:'fecha_data',
                        render: function(data){
                            if(data != null)
                            {
                                return moment(data).format("DD/MM/Y");
                            }
                            else
                            {
                                return data;
                            }
                        }
                    },
                    //{data:'transaccion', name:'transaccion'},
                    {data:'valorviejo', name:'valorviejo'},
                    {data:'valornuevo', name:'valornuevo'},
                    {data:'usuario', name:'usuario'}
                ],
                order: [[0, 'desc']]            
            });
        });
        /* DATATABLES */
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
                                <th>Fecha Mod.</th>
                                <th>Variable</th>
                                <th>Fecha Data</th>
                                <!-- <th>Transaccion</th> -->
                                <th>Valor V.</th>
                                <th>Valor N.</th>
                                <th>Usuario</th>            
                            </tr>
                        </thead>      
                    </table>                
                </div>
            </div>
        </div> 
        @csrf
    </div> 
@stop
