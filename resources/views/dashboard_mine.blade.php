@extends('adminlte::page')

@section('title', 'Dashboard')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1><b style="color:#495057;">SIOM</b> Tablero Mina</h1>            
            <div class="row">
                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4"/>
                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>                    
        </div> 
    </div>  
@stop

@section('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/sl-1.3.4/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset("assets/DataTables/Select-1.3.4/css/select.dataTables.min.css")}}"/>
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
            max-width: 86rem;
        }
        .section-header > div > div{
            margin-top:1rem;
        }
        @media(min-width:300px) {
            .section-header > div{
                flex: 0 0 98%;
            }
        }
        .buttons-copy, .buttons-csv{
            display: none!important;
        }
        @media(min-width:530px) {
            .section-header > div{
                flex-direction: row;
                justify-content: space-between;
                flex: 0 0 98%;
            }
            .section-header > div > div{
                margin-top:0rem;
            }
            .buttons-copy, .buttons-csv{
            display: inline-block!important;
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
            max-width: 86rem;
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
        .datatables-s .datatables-length, .datatables-s .datatables-title {
            display: flex;
            justify-content: center;
            margin-top: .5rem;
        }
        @media(min-width:750px) {
            .datatables-s .datatables-length, .datatables-s .datatables-title {
                margin-left: 2rem;
            }
        }
        .datatables-s .datatables-filter, .datatables-s .datatables-btn-cargar {
            display: flex;
            justify-content: center;
            margin-top: .5rem;
        }
        @media(min-width:750px) {
            .datatables-s .datatables-filter, .datatables-s .datatables-btn-cargar {
                margin-right: 2rem;
            }
        }
        .datatables-t {
            padding: .5rem 0 0 .3rem;
            margin: auto;
            max-width: 84rem;
        }
        @media(min-width:750px) {
            .datatables-t {
                width: 100%;
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

        .dtrg-level-0 > td{
            background-color: #525C66;     
            color: white;
            font-size: 1.1rem;
        }
        .dtrg-level-1 > td{
            background-color: #D6D6D6;
        }
        .datetimepicker-input{
            font-size: 1.8rem;
        }

        .thcenter{
            text-align:center;
            vertical-align: inherit!important;
        }
        .buttonclass{
            background-color:#525C66
        }

        .table-bord th {
            border: 1px solid #dee2e6;
        }

        .table-bord td {
            border: 1.3px solid #dee2e6;
        }

        .table-sm td, .table-sm th {
            padding: 0.25rem;
        }

        /*.red_percentage {
            background-color:red;
            color: white;
            font-weight: 600!important;
        }
        .yellow_percentage{
            font-weight: 700!important;
            background-color:#ffbb10;
        }
        .green_percentage{
            background-color:green;
            color: white;
            font-weight: 600!important;
        }*/
        .red_percentage{
            color: red;
            font-weight: bold;
        }
        .yellow_percentage{
            color: darkorange;
            font-weight: bold;
        }
        .green_percentage{
            color: green;
            font-weight: bold;
        }

        /* SCROLLBALL HIDDEN
        .dataTables_scrollBody {
            -ms-overflow-style: none;  
            scrollbar-width: none; 
        }
        .dataTables_scrollBody::-webkit-scrollbar { 
            display: none;  
        } */

        /* PROJECTION */
        .budgetAndForecastVisibility {
            min-width:20vw!important;
            max-width:20vw!important;
        }
        .budgetOrForecastVisibility {
            min-width:12vw!important;
            max-width:12vw!important;
        }
        .budgetAndForecastNoVisibility {
            min-width:4vw!important;
            max-width:4vw!important;
        }

    </style> 
@stop

@section('js')
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script> 
    <script src="{{asset("vendor/jquery-ui/jquery-ui.js")}}"></script> 
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
    <script>
        let columnsVisibility = [];
        var idx = -1;     
        let focastVisibility = 1;
        let budgetVisibility = 1;
        let timeFrames = document.querySelectorAll('.timeFrame');   
        const valor = document.querySelector('#valor'); 

        /*PRESS NAV-LINK BUTTON*/
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#mina-table').dataTable();
                    oTable.fnAdjustColumnSizing();
                    var oTable2 = $('#comentarios-table').dataTable();
                    oTable2.fnAdjustColumnSizing();
                }, 
            350);
        });
        /*PRESS NAV-LINK BUTTON*/

        var date_selected = moment().subtract(1, "days");
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'DD/MM/YYYY',
                maxDate: moment().subtract(1, "days"),
                defaultDate: date_selected
            });
            $("#datetimepicker4").on("change.datetimepicker", function (e) {
                idx = -1;
                date_selected = e.date;
                test = moment(date_selected).format('YYYY-MM-DD');
                $('#mina-table').DataTable().ajax.reload(null, false);
                $('#comentarios-table').DataTable().ajax.reload(null, false);
                if(moment(date_selected).format('YYYY-MM-DD') == moment().subtract(1, 'days').format('YYYY-MM-DD') && moment().utc().format('HH') < 19)
                {
                    $(".alert-light").css('display','flex');
                }
                else
                {
                    $(".alert-light").css('display','none');
                }
            });
        })
        
        $(document).on('click','.expPdf', function(event) {
            columnsVisibility = [];
            formato = $(this).attr('id');
            tabledata = $("#mina-table").DataTable();
            for (i=7; i<23; i=i+5)
            {
                columnsVisibility.push(tabledata.column(i).visible());
            }
            event.preventDefault();
            $.ajax({
                url: "{{route('dashboard.getpdfcompleto') }}",
                type: 'POST',
                data:{
                    formato:formato,
                    date: moment(date_selected).format('YYYY-MM-DD'),
                    columnsVisibility: columnsVisibility,
                    focastVisibility: focastVisibility,
                    budgetVisibility: budgetVisibility,
                    _token: $('input[name="_token"]').val()
                },
                beforeSend: function(){
                    $('#modal-overlay').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard:false,
                    });
                }
            }).done(function(res){

                const link = document.createElement('a');
                link.href = res.ruta;
                if(formato == "full")
                {
                    nom = 'SIOM_DailyReportCompleto_' + moment(date_selected).format('YYYY-MM-DD') + '.pdf';
                }
                else
                {
                    nom = 'SIOM_DailyReportMina_' + moment(date_selected).format('YYYY-MM-DD') + '.pdf';
                }
                link.setAttribute('download',nom);
                document.body.appendChild(link);
                link.click();
                $('#modal-overlay').modal('hide')
            })
                
        });

        /* DATATABLES */
        $(document).ready(function(){            
            assignWidthColumn('budgetAndForecastVisibility');
            var collapsedGroups = {};
            if(moment(date_selected).format('YYYY-MM-DD') == moment().subtract(1, 'days').format('YYYY-MM-DD') && moment().utc().format('HH') < 14)
            {
               $(".alert-light").css('display','flex');
            }
            else
            {
                $(".alert-light").css('display','none');
            }
            function getExportTitle()
            {
                return 'Daily Report: '+moment(date_selected).format('YYYY-MM-DD');
            }
            function getDateSelected()
            {
                return moment(date_selected).format('YYYY-MM-DD');
            }
            var tabledata = $("#mina-table").DataTable({
                dom:    "<'datatables-p'<'datatables-button'B>>" + 
                        "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Proyección',
                        buttons: [
                            {
                                text: 'BUDGET',
                                action: function ( e, dt, node, config ) {
                                    columnVisibility('BUDGET', dt);                                            
                                }
                            },
                            {
                                text: 'FORECAST',
                                action: function ( e, dt, node, config ) {  
                                    columnVisibility('FORECAST', dt);                                      
                                }
                            }
                        ]
                    },
                    {
                        extend: 'collection',
                        text: 'Periodo',
                        buttons: [
                            {
                                text: 'DÍA',
                                action: function ( e, dt, node, config ) {
                                    columnVisibility('DIA', dt);
                                }
                            },
                            {
                                text: 'MES',
                                action: function ( e, dt, node, config ) {
                                    columnVisibility('MES', dt);
                                }
                            },
                            {
                                text: 'TRIM.',
                                action: function ( e, dt, node, config ) {
                                    columnVisibility('TRIM', dt);
                                }
                            },
                            {
                                text: 'AÑO',
                                action: function ( e, dt, node, config ) {
                                    columnVisibility('ANIO', dt);
                                }
                            },
                        ]
                    },
                    {
                        extend: 'copyHtml5', 
                        text: 'Copiar',
                        className: 'buttonclass',
                        exportOptions: {
                            columns: [4,':visible.exportable']
                        }
                    }, 
                    {
                        extend: 'csvHtml5',
                        className: 'buttonclass',
                        title: function () { return getExportTitle();},
                        filename: 'DailyReport'+getDateSelected(),
                        exportOptions: {
                            columns: [4,':visible.exportable']
                        }
                    }, 
                    {
                        extend: 'excelHtml5',
                        className: 'buttonclass',
                        title: function () { return getExportTitle();},
                        filename: 'DailyReport'+getDateSelected(),
                        exportOptions: {
                            columns: [4,':visible.exportable']
                        }
                    }, 
                    {
                        //extend: 'pdfHtml5',
                        extend: 'collection',
                        text: 'PDF',
                        buttons:[
                            {   text: 'Daily Mina',
                                className: 'expPdf',
                                attr:{
                                    id:"mine"
                                }
                            },
                            {
                                text:'Daily Full Mina y Planta',
                                className: 'expPdf',
                            },
                        ],
                          
                    },
                    {
                        extend: 'print',
                        className: 'buttonclass',
                        text: 'Imprimir',
                        title: function () { return getExportTitle();},
                        exportOptions: {
                            columns: [4,':visible.exportable']
                        }
                    },
                ],
                lengthChange: false,
                paging: false,
                processing: true,
                bInfo: false,
                serverSide: true,
                responsive: true,
                scrollX : true,
                select: true,
                "preDrawCallback": function (settings) {
                    pageScrollPos = $('div.dataTables_scrollBody').scrollTop();
                },
                "drawCallback": function (settings) {
                    if (idx >= 0)
                    {
                        let row = $("#mina-table").DataTable().row(idx);
                        row.select();                        
                        $('div.dataTables_scrollBody').scrollTop(pageScrollPos); 
                    }
                },
                ajax:{                
                    url: "{{route('dashboard.minatable')}}",
                    type: 'GET',
                    data: function(d){
                        d.fecha = moment(date_selected).format('YYYY-MM-DD');
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
                               
                    {data:'categoria', name:'categoria', visible:false},
                    {data:'subcategoria', name:'subcategoria', visible:false},  
                    {data:'var_orden', name:'var_orden', visible:false},                      
                    {data:'action', name:'action', orderable: false,searchable: false, width:'25px'},   
                    {data:'var_export', name:'var_export', visible:false}, 
                    {data:'variable', name:'variable', orderable: false}, 
                    {data:'unidad', name:'unidad', orderable: false, searchable: false, width:'25px'}, 
                    {data:'dia_real', name:'dia_real', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if (data != '-')
                            {
                                return '<span class="complete_value" data-id="'+row['id']+'">'+data+'</span>';
                            }
                            return data;
                        }
                    },
                    {data:'dia_forecast', name:'dia_forecast', orderable: false,searchable: false},
                    {data:'dia_budget', name:'dia_budget', orderable: false,searchable: false},
                    {data: null, name:'per_forecast', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['dia_forecast'] != '-' && row['dia_real'] != '-')
                            {
                                $d_budget = parseFloat(row['dia_forecast'].replaceAll(',',''));
                                $d_real = parseFloat(row['dia_real'].replaceAll(',',''));
                                if($d_budget != 0.00 )
                                {
                                    $dia_porcentaje = Math.round(($d_real / $d_budget)*100);
                                    switch(true)
                                    {
                                        case $dia_porcentaje < 90:
                                            //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                            return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        case $dia_porcentaje >= 90 && $dia_porcentaje < 95 :
                                            //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                            return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        case  $dia_porcentaje >= 95 :
                                            //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                            return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $dia_porcentaje+'%';
                                        break;
                                            
                                    }    
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            else
                            {
                                return '-';
                            }
                                         
                        }
                    },
                    {data: null, name:'per_budget', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['dia_budget'] != '-' && row['dia_real'] != '-')
                            {
                                $d_budget = parseFloat(row['dia_budget'].replaceAll(',',''));
                                $d_real = parseFloat(row['dia_real'].replaceAll(',',''));
                                if($d_budget != 0.00 )
                                {
                                    $dia_porcentaje = Math.round(($d_real / $d_budget)*100);
                                    switch(true)
                                    {
                                        case $dia_porcentaje < 90:
                                            //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                            return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        case $dia_porcentaje >= 90 && $dia_porcentaje < 95 :
                                            //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                            return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        case  $dia_porcentaje >= 95 :
                                            //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                            return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $dia_porcentaje+'%';
                                        break;
                                            
                                    }    
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            else
                            {
                                return '-';
                            }
                                         
                        }
                    },
                    {data:'mes_real', name:'mes_real', orderable: false,searchable: false},
                    {data:'mes_forecast', name:'mes_forecast', orderable: false,searchable: false},
                    {data:'mes_budget', name:'mes_budget', orderable: false,searchable: false},
                    {data: null, name:'per_forecast', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['mes_forecast'] != '-' && row['mes_real'] != '-')
                            {
                                $m_budget = parseFloat(row['mes_forecast'].replaceAll(',',''));
                                $m_real = parseFloat(row['mes_real'].replaceAll(',',''));
                                if($m_budget != 0.00)
                                {
                                    $mes_porcentaje = Math.round(($m_real / $m_budget)*100);
                                    switch(true)
                                    {
                                        case $mes_porcentaje < 90:
                                            return '<div class="red_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        case $mes_porcentaje >= 90 && $mes_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        case  $mes_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $mes_porcentaje+'%';
                                        break;
                                            
                                    }    
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }                                         
                        }
                    },
                    {data: null, name:'per_budget', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['mes_budget'] != '-' && row['mes_real'] != '-')
                            {
                                $m_budget = parseFloat(row['mes_budget'].replaceAll(',',''));
                                $m_real = parseFloat(row['mes_real'].replaceAll(',',''));
                                if($m_budget != 0.00)
                                {
                                    $mes_porcentaje = Math.round(($m_real / $m_budget)*100);
                                    switch(true)
                                    {
                                        case $mes_porcentaje < 90:
                                            return '<div class="red_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        case $mes_porcentaje >= 90 && $mes_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        case  $mes_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$mes_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $mes_porcentaje+'%';
                                        break;
                                            
                                    }    
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }                                         
                        }
                    },
                    {data:'trimestre_real', name:'trimestre_real', orderable: false,searchable: false},
                    {data:'trimestre_forecast', name:'trimestre_forecast', orderable: false,searchable: false},
                    {data:'trimestre_budget', name:'trimestre_budget', orderable: false,searchable: false},
                    {data:null, name:'per_forecast', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['trimestre_forecast'] != '-' && row['trimestre_real'] != '-')
                            {                                
                                $t_budget = parseFloat(row['trimestre_forecast'].replaceAll(',',''));
                                $t_real = parseFloat(row['trimestre_real'].replaceAll(',',''));
                                if($t_budget != 0.00)
                                {
                                    $trimestre_porcentaje = Math.round(($t_real/ $t_budget)*100);
                                    switch(true)
                                    {
                                        case $trimestre_porcentaje < 90:
                                            return '<div class="red_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        case $trimestre_porcentaje >= 90 && $trimestre_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        case  $trimestre_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $trimestre_porcentaje+'%';
                                        break;                                            
                                    }   
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }     
                        }
                    },
                    {data:null, name:'per_budget', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['trimestre_budget'] != '-' && row['trimestre_real'] != '-')
                            {                                
                                $t_budget = parseFloat(row['trimestre_budget'].replaceAll(',',''));
                                $t_real = parseFloat(row['trimestre_real'].replaceAll(',',''));
                                if($t_budget != 0.00)
                                {
                                    $trimestre_porcentaje = Math.round(($t_real/ $t_budget)*100);
                                    switch(true)
                                    {
                                        case $trimestre_porcentaje < 90:
                                            return '<div class="red_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        case $trimestre_porcentaje >= 90 && $trimestre_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        case  $trimestre_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$trimestre_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $trimestre_porcentaje+'%';
                                        break;                                            
                                    }   
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }     
                        }
                    },
                    {data:'anio_real', name:'anio_real', orderable: false,searchable: false},
                    {data:'anio_forecast', name:'anio_forecast', orderable: false,searchable: false},
                    {data:'anio_budget', name:'anio_budget', orderable: false,searchable: false},
                    {data:null, name:'per_forecast', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['anio_forecast'] != '-' && row['anio_real'] != '-')
                            {                               
                                $a_budget = parseFloat(row['anio_forecast'].replaceAll(',',''));
                                $a_real = parseFloat(row['anio_real'].replaceAll(',',''));
                                if($a_budget != 0.00)
                                {
                                    $anio_porcentaje=Math.round(($a_real / $a_budget)*100);
                                    switch(true)
                                    {
                                        case $anio_porcentaje < 90:
                                            return '<div class="red_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        case $anio_porcentaje >= 90 && $anio_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        case  $anio_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $anio_porcentaje+'%';
                                        break;
                                            
                                    }   
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }     
                        }
                    },
                    {data:null, name:'per_budget', orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['anio_budget'] != '-' && row['anio_real'] != '-')
                            {                               
                                $a_budget = parseFloat(row['anio_budget'].replaceAll(',',''));
                                $a_real = parseFloat(row['anio_real'].replaceAll(',',''));
                                if($a_budget != 0.00)
                                {
                                    $anio_porcentaje=Math.round(($a_real / $a_budget)*100);
                                    switch(true)
                                    {
                                        case $anio_porcentaje < 90:
                                            return '<div class="red_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        case $anio_porcentaje >= 90 && $anio_porcentaje < 95 :
                                            return '<div class="yellow_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        case  $anio_porcentaje >= 95 :
                                            return '<div class="green_percentage">'+$anio_porcentaje+'%</div>';
                                        break;
                                        default:
                                            return $anio_porcentaje+'%';
                                        break;
                                            
                                    }   
                                }
                                else
                                {
                                    return '-';
                                }

                            }
                            else
                            {
                                return '-';
                            }     
                        }
                    },
                ],
                rowGroup: {
                    dataSrc: ['categoria', 'subcategoria'],   
                    /** */
                    startRender: function (rows, group) {
                        var collapsed = !!collapsedGroups[group];

                        rows.nodes().each(function (r) {
                            r.style.display = collapsed ? 'none' : '';
                        });    

                        // Add category name to the <tr>. NOTE: Hardcoded colspan
                        return $('<tr/>')
                            .append('<td colspan="23">' + group + '</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                    }                 
                    /** */
                },
                columnDefs: [
                    {
                        targets: [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26],
                        className: "dt-center"
                    }
                ],
                orderFixed: [
                    [0, 'asc'],
                    [1, 'asc'],
                    [2, 'asc']
                ]
            });     
         
            /** */
            $('#mina-table tbody').on('click', 'tr.dtrg-level-1', function () {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
                tabledata.draw();
            });
            
            
            var tablecomentario = $("#comentarios-table").DataTable({
                dom:     "<'datatables-s'<'datatables-title'l><'datatables-btn-cargar'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                lengthChange: false,
                paging: false,
                processing: true,
                bInfo: false,
                serverSide: true,
                responsive: true,                
                scrollX : true,
                select: true,
                scrollY: '65vh',                
                scrollCollapse: true,
                paging: false,
                ajax:{                
                    url: "{{route('comentario.comentariostable')}}",
                    type: 'GET',
                    data: function(d){
                        d.fecha = moment(date_selected).format('YYYY-MM-DD');
                        d.area_id = 2;
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
                               
                    {data:'id', name:'id', orderable: false, searchable: false, visible: false},
                    {data:'area', name:'area', orderable: false, searchable: false},
                    {data:'usuario', name:'usuario', orderable: false,searchable: false},        
                    {data:'comentario', name:'comentario', orderable: false,searchable: false},               
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ], 
                columnDefs: [
                    {
                        targets: [0,1],
                        className: "dt-center"
                    }
                ],
                orderFixed: [
                    [0, 'asc']
                ]
            });   

            $('.datatables-title').html('<div style="font-size:1.5rem; font-weight:500;">Comentarios</div>')            
            @if (Auth::user()->hasAnyRole(['Reportes_E', 'Admin']))
                $('.datatables-btn-cargar').html('<a href="javascript:void(0)" name="edit"  class="btn btn-success add" title="Editar registro">Cargar</a>');
            @else
                $('.datatables-btn-cargar').html('');
            @endif

        });
        /* DATATABLES */

        /* ADD BUTTON*/
        $(document).on('click', '.add', function(){ 
                $('#form-button-comentario').val(1);     
                $('#modal-title-comentario').html('Cargar Comentario'); 
                $('#modal-form-comentario').trigger("reset"); 
                $('#modal-comentario').modal('show');  
                $('#id_comentario').val('');   
            });
        /* ADD BUTTON*/

        /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            idx = $("#mina-table").DataTable().row($(this).parent()).index();
            $.ajax({
                url:"{{route('dashboard.edit') }}",
                method:"POST",
                data:{
                    area_id: 2,
                    id: $(this).data('id'),
                    variable_id:$(this).data('vbleid'),
                    selecteddate: moment(date_selected).utc().format('YYYY-MM-DD'),
                    _token: $('input[name="_token"]').val()
                },
                success:function(data)
                {  
                    if (data['val'] == 1)
                    {                        
                        $('#form-button').val(0); 
                        $('#modal-title').html('Editar Registro'); 
                        $('#modal-form').trigger("reset"); 
                        $('#modal').modal('show');
                        $('#id').val(data['generic'].id);
                        $('#area').val(data['generic'].area);
                        $('#categoria').val(data['generic'].categoria);
                        $('#subcategoria').val(data['generic'].subcategoria);
                        $('#variable').val(data['generic'].variable);
                        $('#fecha').val(data['generic'].fecha);
                        valor.value = data['generic'].valor;
                        valor.setAttribute("max", data['generic'].max);
                        valor.setAttribute("min", data['generic'].min); 
                    }
                    else
                    {
                        if (data['val'] == -2)
                        {
                            Swal.fire({
                                html: data['html'],
                                icon: 'warning',
                            })
                        }
                        else
                        {    
                            Swal.fire({
                                title: data['msg'],
                                icon: 'warning',
                            })                        
                        }
                    }
                }
            });
        });      
        /* EDIT BUTTON */

        /* COMPLETE VALUE */
        $(document).on('click', '.complete_value', function(){ 
            var id=$(this).data('id');
            $.get('dashboard/'+id+'/complete_value', function(data){  
                console.log(data);    
                $('#modal-form-complete_value').trigger("reset"); 
                $('#modal-complete_value').modal('show');                        
                $('#complete_value').val(data);                                   
            })
        });      
        /* COMPLETE VALUE */
        
        /* EDIT BUTTON COMENTARIO*/
        $(document).on('click', '.edit-comentario', function(){ 
            var id=$(this).data('id');
            $.get('comentario/'+id+'/edit', function(data){
                if (data['val'] == 1)
                {
                    $('#form-button-comentario').val(0); 
                    $('#modal-title-comentario').html('Editar Comentario'); 
                    $('#modal-form-comentario').trigger("reset"); 
                    $('#modal-comentario').modal('show');
                    $('#id_comentario').val(data['generic'].id);                          
                    $('#comentario').val(data['generic'].comentario);                           
                    $("#area_id_comentario").val(data['generic'].area_id).attr("selected", "selected");
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
        /* EDIT BUTTON COMENTARIO*/

        /* EDIT2 BUTTON */  
        $(document).on('click', '.edit2', function() {
            Swal.fire({
                title: 'No tienes los permisos necesarios',
                icon: 'warning',
            })
        });
        /* EDIT2 BUTTON */


        /* DELETE BUTTON */  
        $(document).on('click', '.delete-comentario', function() {
            let id = $(this).data('id');
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
                    url: "comentario/" + id,
                    type: 'delete',
                    data:{_token: $('input[name="_token"]').val()},
                    success: function (data) { 
                        if (data['val'] == 1)
                        {
                            var oTable = $('#comentarios-table').dataTable();
                            oTable.fnDraw(false); 
                            swalWithBootstrapButtons.fire(
                                'Borrado!',
                                'El registro fue eliminado.',
                                'success'
                            )
                        }
                        else
                        {
                            Swal.fire({
                                title: data['msg'],
                                icon: 'warning',
                            })                            
                        }
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
                    url:"{{route('dashboard.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        valor:valor.value,
                        selecteddate: moment(date_selected).format('YYYY-MM-DD'),
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
                            var oTable = $('#mina-table').dataTable();
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

        /* FORM BUTTON DASHBOARD*/        
        $("#modal-form-comentario").validate({
            rules: {
                area_id_comentario: {
                    required: true
                },
                comentario: {
                    required: true,
                    minlength: 2,
                    maxlength: 1000
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

        $("#form-button-comentario").click(function(){
            if($("#modal-form-comentario").valid()){
                $('#form-button').html('Guardando..');
                $.ajax({
                    url:"{{route('comentario.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id_comentario").val(),
                        area_id_comentario:$('#area_id_comentario').val(),
                        selecteddate: moment(date_selected).format('YYYY-MM-DD'),
                        comentario:$('#comentario').val(),
                        _token: $('input[name="_token"]').val()
                    },
                    success:function(data)
                    {  
                        $('#modal').scrollTop(0);
                        if($.isEmptyObject(data.error)){
                            $('#modal-comentario').modal('hide');
                            $(".alert-error").css('display','none');
                            $('#modal-form-comentario').trigger("reset");
                            $('#form-button-comentario').html('Cargar');
                            var oTable = $('#comentarios-table').dataTable();
                            oTable.fnDraw(false);
                            if($('#form-button-comentario').val() == 1){
                                MansfieldRep.notification('Comentario cargado con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('Comentario actualizado con exito', 'MansfieldRep', 'success');
                            } 
                        }else{
                            $('#form-button-comentario').html('Guardar Cambios');
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

        /* ACTION TO CLOSE MODAL COMENTARIO */
        $('#modal-comentario').on('hidden.bs.modal', function () {
            $("#modal-form-comentario").validate().resetForm();
            $(".alert-error").css('display','none');
        });
        /* ACTION TO CLOSE MODAL COMENTARIO*/

        /* MODAL DRAGGABLE */
        $('.modal.draggable>.modal-dialog').draggable({
            cursor: 'move',
            handle: '.modal-head'
        });
        $('.modal.draggable>.modal-dialog>.modal-content>.modal-head').css('cursor', 'move');
        /* MODAL DRAGGABLE */

                /* FUNCIONES */
                function assignWidthColumn(classN) {
            for (const frame of timeFrames) {
                frame.classList.remove('budgetAndForecastVisibility', 'budgetOrForecastVisibility', 'budgetAndForecastNoVisibility');
                frame.classList.add(classN);
            }
        }

        function columnVisibility(projection, dt) {
            switch (projection) {
                case 'BUDGET': 
                    if (budgetVisibility === 1) {
                        if (dt.column( -20 ).visible() === true) {
                            dt.columns( [-16,-18] ).visible( false );
                        }
                        if (dt.column( -15 ).visible() === true) {
                            dt.columns( [-11,-13] ).visible( false );
                        }
                        if (dt.column( -10 ).visible() === true) {
                            dt.columns( [-6,-8] ).visible( false );
                        }
                        if (dt.column( -5 ).visible() === true) {
                            dt.columns( [-1,-3] ).visible( false );
                        }
                        budgetVisibility = 0;
                    }
                    else {
                        if (dt.column( -20 ).visible() === true) {
                            dt.columns( [-16,-18] ).visible( true );
                        }
                        if (dt.column( -15 ).visible() === true) {
                            dt.columns( [-11,-13] ).visible( true );
                        }
                        if (dt.column( -10 ).visible() === true) {
                            dt.columns( [-6,-8] ).visible( true );
                        }
                        if (dt.column( -5 ).visible() === true) {
                            dt.columns( [-1,-3] ).visible( true );
                        }
                        budgetVisibility = 1;
                    } 
                break;
                case 'FORECAST': 
                    if (focastVisibility === 1) {
                        if (dt.column( -20 ).visible() === true) {
                            dt.columns( [-17,-19] ).visible( false );
                        }
                        if (dt.column( -15 ).visible() === true) {
                            dt.columns( [-12,-14] ).visible( false );
                        }
                        if (dt.column( -10 ).visible() === true) {
                            dt.columns( [-7,-9] ).visible( false );
                        }
                        if (dt.column( -5 ).visible() === true) {
                            dt.columns( [-2,-4] ).visible( false );
                        }
                        focastVisibility = 0;
                    }
                    else {
                        if (dt.column( -20 ).visible() === true) {
                            dt.columns( [-17,-19] ).visible( true );
                        }
                        if (dt.column( -15 ).visible() === true) {
                            dt.columns( [-12,-14] ).visible( true );
                        }
                        if (dt.column( -10 ).visible() === true) {
                            dt.columns( [-7,-9] ).visible( true );
                        }
                        if (dt.column( -5 ).visible() === true) {
                            dt.columns( [-2,-4] ).visible( true );
                        }
                        focastVisibility = 1;
                    }
                break;
                case 'DIA':
                    if (dt.column( -20 ).visible() === false) {
                        dt.columns( [-20] ).visible(true);
                        if (focastVisibility === 1) {
                            dt.columns( [-17,-19] ).visible(true);
                        }
                        if (budgetVisibility === 1) {
                            dt.columns( [-16,-18] ).visible(true);
                        }
                    }
                    else {
                        dt.columns( [-16,-17,-18,-19,-20] ).visible( false );
                    }
                break;
                case 'MES':
                    if (dt.column( -15 ).visible() === false) {
                        dt.columns( [-15] ).visible(true);
                        if (focastVisibility === 1) {
                            dt.columns( [-12,-14] ).visible(true);
                        }
                        if (budgetVisibility === 1) {
                            dt.columns( [-11,-13] ).visible(true);
                        }
                    }
                    else {
                        dt.columns( [-11,-12,-13,-14,-15] ).visible( false );
                    }
                break;
                case 'TRIM':
                    if (dt.column( -10 ).visible() === false) {
                        dt.columns( [-10] ).visible(true);
                        if (focastVisibility === 1) {
                            dt.columns( [-7,-9] ).visible(true);
                        }
                        if (budgetVisibility === 1) {
                            dt.columns( [-6,-8] ).visible(true);
                        }
                    }
                    else {
                        dt.columns( [-6,-7,-8,-9,-10] ).visible( false );
                    }
                break;
                case 'ANIO':
                    if (dt.column( -5 ).visible() === false) {
                        dt.columns( [-5] ).visible(true);
                        if (focastVisibility === 1) {
                            dt.columns( [-2,-4] ).visible(true);
                        }
                        if (budgetVisibility === 1) {
                            dt.columns( [-2,-3] ).visible(true);
                        }
                    }
                    else {
                        dt.columns( [-1,-2,-3,-4,-5] ).visible( false );
                    }
                break;
            }

            /*var oTable = $('#procesos-table').dataTable();
            oTable.fnAdjustColumnSizing();*/

            if (budgetVisibility === 1 && focastVisibility === 1) {
                assignWidthColumn('budgetAndForecastVisibility');
            }
            else {
                if (budgetVisibility === 0 && focastVisibility === 0) {
                    assignWidthColumn('budgetAndForecastNoVisibility');
                }
                else {
                    assignWidthColumn('budgetOrForecastVisibility');
                }
            }
        }


    </script>
@stop

@section('content') 
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body">   
                    <div class="alert alert-light alert-dismissible fade show" style="display:none;" role="alert">
                        <p><strong>Advertencia!</strong>&nbsp;Es posible que algunos datos aún no se encuentren cargados.</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- <table style="width:100%;" class="table table-striped table-sm table-bordered table-hover datatable" id="mina-table"> -->
                    <table style="width:100%; border-collapse: collapse !important;" class="table-sm table-bord table-hover nowrap" id="mina-table">
                        <thead style=" border-collapse: collapse !important;">
                            <tr>
                                <th rowspan="2">CATEGORIA</th>
                                <th rowspan="2">SUBCATEGORIA</th>
                                <th rowspan="2">orden</th>
                                <th rowspan="2" style="min-width:25px!important;" class="thcenter"></th> 
                                <th rowspan="2">ÁREA</th>
                                <th rowspan="2" class="thcenter exportable">NOMBRE</th>
                                <th rowspan="2" class="thcenter exportable" style="min-width:25px!important;">U.</th>
                                <th colspan="5" class="thcenter timeFrame">DIA</th>
                                <th colspan="5" class="thcenter timeFrame">MES</th>
                                <th colspan="5" class="thcenter timeFrame">TRIMESTRE</th>
                                <th colspan="5" class="thcenter timeFrame">AÑO</th>
                            </tr>
                            <tr>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Forecast</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">% F</th>
                                <th class="thcenter exportable">% B</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Forecast</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">% F</th>
                                <th class="thcenter exportable">% B</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Forecast</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">% F</th>
                                <th class="thcenter exportable">% B</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Forecast</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">% F</th>
                                <th class="thcenter exportable">% B</th>
                            </tr>
                        </thead>  
                    </table>                
                </div>
            </div> 
        </div> 
        @csrf
    </div>
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body">   
                    <table style="width:100%; border-collapse: collapse !important;" class="table-sm table-bord table-hover" id="comentarios-table">
                        <thead style=" border-collapse: collapse !important;">
                            <tr>
                                <th></th>
                                <th class="thcenter" style="min-width:6vw!important;">Area</th>
                                <th class="thcenter" style="min-width:6vw!important;">Usuario</th>
                                <th class="thcenter" style="min-width:25vw!important;">Procesos</th>
                                <th class="thcenter" style="min-width:25px!important;"></th>
                            </tr>
                        </thead>  
                    </table>                
                </div>
            </div>
        </div> 
        @csrf
    </div> 

    {{-- MODAL --}}
    <div class="modal draggable fade" id="modal" aria-hidden="true">
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
                            <label for="area" class="col-sm-2 col-form-label">Area</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="area" name="area" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="categoria" name="categoria" disabled>
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label for="subcategoria" class="col-sm-2 col-form-label">Subcategoria</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="subcategoria" name="subcategoria" disabled>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label for="variable" class="col-sm-2 col-form-label">Variable</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="variable" name="variable" disabled>
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="fecha" name="fecha" disabled>
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label for="valor" class="col-sm-2 col-form-label">Valor</label>
                            <div class="col-sm-10">
                              <input type="number" class="form-control" id="valor" name="valor">
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

    <div class="modal fade" id="modal-comentario" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-head">
                    <h5 id="modal-title-comentario"></h5>
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
                    <form action="post" id="modal-form-comentario" name="modal-form-comentario" autocomplete="off">
                        <input type="hidden" name="id_comentario" id="id_comentario">                       
                        <div class="form-group row">
                            <label for="area_id_comentario" class="col-sm-2 col-form-label">Area</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="area_id_comentario" id="area_id_comentario">
                                    <option value="" selected disabled>Seleccione Area</option>
                                    @foreach($areas as $id => $nombre)
                                            <option value="{{$id}}">{{$nombre}}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comentario" class="col-sm-2 col-form-label">Comentario</label>
                            <div class="col-sm-10">
                              <textarea type="text" class="form-control" id="comentario" name="comentario" placeholder="Comentario" rows="6"></textarea>
                            </div>
                        </div>              
                        @csrf
                    </form>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-primary" id="form-button-comentario">Cargar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-complete_value" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-head">
                    <h5 id="modal-title-complete_value">Valor Real</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-bod">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="complete_value" name="complete_value" disabled>
                        </div>
                    </div>       
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}

    {{-- Modal para descargar de PDF --}}
    <div class="modal fade" id="modal-overlay" data-target="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Descargando...</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Generando Daily Report...</p>
                </div>
            </div>
        </div>
    </div>

@stop