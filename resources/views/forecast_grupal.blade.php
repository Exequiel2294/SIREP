@extends('adminlte::page')

@section('title', 'Historial Variables')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1><b style="color:#495057;">SIOM</b> Forecast por Grupo Variables</h1>
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
            max-width: 80rem;
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

        .btn-select {
            color: #fff;
            background-color: #28a745!important;
            border-color: #28a745;
            box-shadow: none;
        }
        .thcenter{
            text-align:center;
            vertical-align: inherit!important;
        }

        .table-bord td, .table-bord th {
            border: 1.2px solid #dee2e6;
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

        var columnas = [];
        var vs_id = '(';
        var vsc_id = '(';
        var headerp = '<tr>';
        var headers = '<tr>';
        var date_fd = moment().startOf('month').format('YYYY-MM-DD');
        var date_fh = moment().subtract(1, "days");
        var table;
        $(function () {
            /* Calendario Fecha desde*/
            $('#fechadesde').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: date_fd,
                minDate: moment("2022-01-01").format('YYYY-MM-DD'),
                useCurrent: false,
            });
            $("#fechadesde").on("change.datetimepicker", function (e) {
                date_fd = e.date;
            });

            /* Calendario Fecha hasta*/
            $('#fechahasta').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: date_fh,
                minDate: moment("2022-01-01").format('YYYY-MM-DD'),
                orientation: "bottom"
            });
            $("#fechahasta").on("change.datetimepicker", function (e) {
                date_fh = e.date;
            });
        })


        /* SELECT BUTTON */        
        $("#select-form").validate({
            rules: {    
                area_id:{
                    required: true
                },
                'variable_id[]':{
                    required: true
                },
                fechadesde:{
                    required: true
                },
                fechahasta:{
                    required: true
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

        $("#select-button").click(function(){
            if($("#select-form").valid()){   
                if ( $.fn.dataTable.isDataTable( '#data-table' ) ) {
                    table.destroy();
                }
                $( ".div_table" ).empty();                
                columnas.length = 0; 
                vs_id = '(';
                vsc_id = '(';
                headerp = '<tr>';
                headers = '<tr>';
                $.ajax({
                    type: "POST",
                    data: {
                        vs_id: $('#variable_id').val(),
                        _token: $('input[name="_token"]').val()
                    },
                    url: "{{route('forecast_group.getcolumnas')}}",
                    success: function(data){
                        
                        //Bucle para crear encabezado primario de tabla
                        headerp += '<th rowspan="2" style="width:120px!important;">Fecha</th>';
                        for (var i=0; i < data.columnasp.length; i++)
                        {   
                            headerp += '<th colspan="'+data.columnasp[i]['cantidad']+'" class="thcenter">'+data.columnasp[i]['subcategoria']+'</th>';
                        }
                        headerp += '</tr>';

                        //Bucle para crear columnas datatables, y encabezado secundario de Tabla en Html
                        columnas.push({data: 'fecha', name: 'fecha'});
                        for (var i=0; i < data.columnass.length; i++)
                        {   
                            columnas.push({data: data.columnass[i]['variable_id'], name: data.columnass[i]['variable'], searchable: false, orderable:false,
                            render:function(data, type, row){
                                if (data != null)
                                {
                                    let val = parseFloat(data);
                                    return val.toLocaleString('en-US', { minimumFractionDigits: 3 });
                                }
                                else{
                                    return '-';
                                }
                                return '<input type="number" class="form-control" id="r'+row['variable_id']+'" value="" readonly hidden>'+data;
                            }
                            });
                            vs_id += data.columnass[i]['variable_id'];
                            vsc_id += '['+data.columnass[i]['variable_id']+']';
                            headers += '<th class="thcenter" style="width:120px!important;">'+data.columnass[i]['variable']+'</th>';
                            if (i < data.columnass.length -1)
                            {
                                vs_id += ',';                        
                                vsc_id += ',';
                            }
                        }
                        vs_id += ')';
                        vsc_id += ')';
                        headers += '</tr>';
                        //console.log(vs_id, vsc_id, columnas, headerp, headers);
                        
                        $( ".div_table" ).append( '<table class="table table-striped table-bordered table-hover datatable table-sm" id="data-table"><thead style="border-collapse: collapse !important;">'+headerp+headers+'</thead></table>' );
                        table = $('#data-table').DataTable({             
                            dom:    "<'datatables-p'<'datatables-button'B>>" + 
                                    "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                                    "<'datatables-t'<'datatables-table'tr>>" + 
                                    "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                            buttons: [    
                                {
                                    extend: 'copyHtml5', 
                                    text: 'Copiar'
                                }, 
                                {
                                    extend: 'excelHtml5',
                                    title: 'Listado Variables '+moment().local().format('DD/MM/YYYY'),
                                    filename: 'ListadoVariables'+moment().local().format('DD/MM/YYYY')            
                                }
                            ],
                            pageLength: 10,
                            lengthMenu: [[10, 20, 25], [10, 20, 25]],
                            processing: true,
                            serverSide: false,
                            bInfo: true,
                            scrollX : true,
                            scrollY:true,
                            destroy: true,
                            fixedColumns:   {
                                left: 1
                            },
                            ajax:{                
                                url: "{{route('forecast_group.getvalores')}}",
                                type: 'POST',
                                data: function(d){
                                    d.area_id = $('#area_id').val();
                                    d.vs_id = vs_id;
                                    d.vsc_id = vsc_id;
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
                            columns: columnas,
                            columnDefs: [
                                {
                                    targets: '_all',
                                    className: "dt-center"
                                }
                            ],
                            
                        } );
                    }
                }); 
            } 
        });
        /* SELECT BUTTON */


        /* CHANGE AREA */
        $(document).on('change','.area',function(){
            var _token = $('input[name="_token"]').val();
            var area_id=$(this).val();
            $.ajax({
                url:"{{route('forecast_group.getvariables') }}",
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
            <div class="card" style="min-height: 35rem;">
                <div class="generic-body">  
                    <div class="card-body" style="margin:auto; width:95%"> 
                        <form action="post" id="select-form" name="select-form" autocomplete="off">                            
                                <!-- <legend class="w-auto">Seleccionar Área y Mes</legend> -->
                                <div class="row" style="justify-content: space-around;">
                                    <div class="form-group col-md-2">
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
                                    <div class="form-group col-md-5">
                                        <label for="variable_id" class="col-form-label">Variable</label>
                                        <div>
                                            <select class="form-control" name="variable_id[]" id="variable_id" size="5" multiple>
                                                <option value="" selected disabled>Seleccione Variable</option>                                       
                                            </select> 
                                        </div>
                                    </div>              
                                    <div class="form-group date col-md-4" style="display: flex;flex-direction: column; justify-content: space-between;">    
                                        <div style="display: flex;flex-direction: row; justify-content: space-between;">             
                                            <div class="form-group date col-md-6"  data-target-input="nearest">
                                                <label for="fechadesde" class="col-form-label">Desde</label>
                                                <div class="input-group-append " data-target="#fechadesde" data-toggle="datetimepicker">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechadesde" id="fechadesde" name="fechadesde"/>
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            <div class="form-group date col-md-6"  data-target-input="nearest">
                                                <label for="fechahasta" class="col-form-label">Hasta</label>
                                                <div class="input-group-append" data-target="#fechahasta" data-toggle="datetimepicker">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#fechahasta" id="fechahasta" name="fechahasta"/>
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>                                
                                            </div>  
                                        </div>                                                             
                                        <div style="display: flex;flex-direction: row;justify-content: center;">  
                                            <button type="button" class="btn btn-primary" id="select-button">Cargar Valores</button>                                      
                                        </div>    
                                    </div>                     
                                    <!--<div class="form-group date col-md-2" style="display: flex;flex-direction: column; justify-content: space-between;">
                                        <div data-target-input="nearest">
                                            <label for="fechadesde" class="col-form-label">Desde</label>
                                            <div class="input-group-append dateinput" data-target="#fechadesde" data-toggle="datetimepicker">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#fechadesde" id="fechadesde" name="fechadesde"/>
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div data-target-input="nearest">
                                            <label for="fechahasta" class="col-form-label">Hasta</label>
                                            <div class="input-group-append dateinput" data-target="#fechahasta" data-toggle="datetimepicker" id="">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#fechahasta" id="fechahasta" name="fechahasta"/>
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div> 
                                        </div>
                                    </div>  -->                                      
                                </div>                                       
                            @csrf
                        </form> 
                    </div>      
                    <div class="card-body div_table" style="margin:auto; width:95%"></div>
                </div>
            </div>
        </div> 
        @csrf
    </div> 

    {{-- MODAL --}}
    <div class="modal fade" id="modal-import" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-head">
                    <h5 id="modal-title-import"></h5>
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
                    <form action="post" id="modal-form-import" name="modal-form-import" autocomplete="off">
                        <div class="form-group">
                            <label for="decimalseparator" class="col-sm-12 col-form-label">Seleccione Separador Decimal</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="decimalseparator" id="decimalseparator">
                                    <option value=0 selected="selected">Punto</option> 
                                    <option value=1>Coma</option>                                    
                                </select> 
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="separadordecimal" class="col-sm-12 col-form-label">Pegue Columna o Fila de Datos</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="import" name="import" rows="10"></textarea>
                            </div>
                        </div>              
                        @csrf
                    </form>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-primary" id="form-button-import">Cargar Datos</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
@stop