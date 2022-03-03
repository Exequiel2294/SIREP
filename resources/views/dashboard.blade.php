@extends('adminlte::page')

@section('title', 'Dashboard')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Tablero</h1>            
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

        /*.bluewhite{            
           color: white;
            background-color: #4682B4;
        }*/
        .buttonclass{
            background-color:#525C66
        }

        .table-bord td, .table-bord th {
            border: 0.5px solid #dee2e6;
        }

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

    </style> 
@stop

@section('js')
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script>
    <script>
        

        /*PRESS NAV-LINK BUTTON*/
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#procesos-table').dataTable();
                    oTable.fnAdjustColumnSizing();
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
                date_selected = e.date;
                $('#procesos-table').DataTable().ajax.reload(null, false);
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
        

        /* DATATABLES */
        $(document).ready(function(){    
            
            if(moment(date_selected).format('YYYY-MM-DD') == moment().subtract(1, 'days').format('YYYY-MM-DD') && moment().utc().format('HH') < 19)
            {
               $(".alert-light").css('display','flex');
            }
            else
            {
                $(".alert-light").css('display','none');
            }
            function getExportFilename()
            {
                return 'Reporte'+moment(date_selected).format('YYYY-MM-DD');
            }
            function getExportTitle()
            {
                return 'Reporte '+moment(date_selected).format('YYYY-MM-DD');
            }

            $("#procesos-table").DataTable({
                dom:    "<'datatables-p'<'datatables-button'B>>" + 
                        "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                buttons: [
                    {
                        extend: 'copyHtml5', 
                        text: 'Copiar',
                        className: 'buttonclass'
                    }, 
                    {
                        extend: 'csvHtml5',
                        className: 'buttonclass',
                        title: function () { return getExportTitle();},
                        filename: function () { return getExportFilename();} 
                    }, 
                    {
                        extend: 'excelHtml5',
                        className: 'buttonclass',
                        title: function () { return getExportTitle();},
                        filename: function () { return getExportFilename();}          
                    }, 
                    {
                        extend: 'pdfHtml5',
                        className: 'buttonclass',
                        title: function () { return getExportTitle();},
                        filename: function () { return getExportFilename();},
                        customize: function ( doc ) {
                            doc.styles.title.fontSize = 10;
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend: 'print',
                        className: 'buttonclass',
                        text: 'Imprimir',
                        title: function () { return getExportTitle();}
                    }
                ],
                lengthChange: false,
                paging: false,
                processing: true,
                bInfo: true,
                bInfo: false,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('dashboard.procesostable')}}",
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
                    {data:'action', name:'action', orderable: false,searchable: false, width:'25px'} ,   
                    //{data:'fecha', name:'fecha'},         
                    {data:'variable', name:'variable', orderable: false}, 
                    {data:'unidad', name:'unidad', orderable: false, searchable: false},
                    {data:'dia_real', name:'dia_real', orderable: false,searchable: false},
                    {data:'dia_budget', name:'dia_budget', orderable: false,searchable: false},
                    {data: null, orderable: false,searchable: false,
                        render: function (data,type,row){
                            $dia_porcentaje = (Math.floor(row['dia_real'] / row['dia_budget']))*100;
                            switch(true)
                            {
                                case $dia_porcentaje < 90:
                                    return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                break;
                                case $dia_porcentaje > 89 && $dia_porcentaje <100 :
                                    return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                break;
                                case  $dia_porcentaje > 99 :
                                    return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                break;
                                default:
                                    return $dia_porcentaje+'%';
                                break;
                                    
                            }                     
                        }
                    },
                    {data:'mes_real', name:'mes_real', orderable: false,searchable: false},
                    {data:'mes_budget', name:'mes_budget', orderable: false,searchable: false},
                    {data:'mes_porcentaje', name:'mes_porcentaje', orderable: false,searchable: false,
                        render: function (data,type,row){
                            $mes_porcentaje = (Math.floor(row['mes_real'] / row['mes_budget']))*100;
                            switch(true)
                            {
                                case $mes_porcentaje < 90:
                                    return '<div class="red_percentage">'+$mes_porcentaje+'%</div>';
                                break;
                                case $mes_porcentaje > 89 && $mes_porcentaje <100 :
                                    return '<div class="yellow_percentage">'+$mes_porcentaje+'%</div>';
                                break;
                                case  $mes_porcentaje > 99 :
                                    return '<div class="green_percentage">'+$mes_porcentaje+'%</div>';
                                break;
                                default:
                                    return $mes_porcentaje+'%';
                                break;
                                    
                            }  
                        }
                    },
                    {data:'trimestre_real', name:'trimestre_real', orderable: false,searchable: false},
                    {data:'trimestre_budget', name:'trimestre_budget', orderable: false,searchable: false},
                    {data:'trimestre_porcentaje', name:'trimestre_porcentaje', orderable: false,searchable: false,
                        render: function (data,type,row){
                            $trimestre_porcentaje=(Math.floor(row['trimestre_real'] / row['trimestre_budget']))*100;
                            switch(true)
                            {
                                case $trimestre_porcentaje < 90:
                                    return '<div class="red_percentage">'+$trimestre_porcentaje+'%</div>';
                                break;
                                case $trimestre_porcentaje > 89 && $trimestre_porcentaje <100 :
                                    return '<div class="yellow_percentage">'+$trimestre_porcentaje+'%</div>';
                                break;
                                case  $trimestre_porcentaje > 99 :
                                    return '<div class="green_percentage">'+$trimestre_porcentaje+'%</div>';
                                break;
                                default:
                                    return $trimestre_porcentaje+'%';
                                break;
                                    
                            }  
                        }
                    },
                    {data:'anio_real', name:'anio_real', orderable: false,searchable: false},
                    {data:'anio_budget', name:'anio_budget', orderable: false,searchable: false},
                    {data:'anio_porcentaje', name:'anio_porcentaje', orderable: false,searchable: false,
                        render: function (data,type,row){
                            $anio_porcentaje = (Math.floor(row['anio_real'] / row['anio_budget']))*100;
                            switch(true)
                            {
                                case $anio_porcentaje < 90:
                                    return '<div class="red_percentage">'+$anio_porcentaje+'%</div>';
                                break;
                                case $anio_porcentaje > 89 && $anio_porcentaje <100 :
                                    return '<div class="yellow_percentage">'+$anio_porcentaje+'%</div>';
                                break;
                                case  $anio_porcentaje > 99 :
                                    return '<div class="green_percentage">'+$anio_porcentaje+'%</div>';
                                break;
                                default:
                                    return $anio_porcentaje+'%';
                                break;
                                    
                            }  
                        }
                    }
                ],  
                rowGroup: {
                    dataSrc: ['categoria','subcategoria'],
                    /*startRender: function ( rows, group, level ) {
                        
                        if(level == 0)
                        {
                            return group;
                        }
                        if(level == 1)
                        {
                            console.log($('#procesos-table').DataTable().rows().data());
                            return group +' '+ '<a href="javascript:void(0)" name="edit" class="btn-action-table edit" title="Editar registros"><i class="fa fa-edit"></i></a>';
                        }
                    }
                    */
                },
                orderFixed: [
                    [0, 'asc'],
                    [1, 'asc']
                ]
            });
        });
        /* DATATABLES */


        /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('dashboard/'+id+'/edit', function(data){
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Registro'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);
                $('#area').val(data.area);
                $('#categoria').val(data.categoria);
                $('#subcategoria').val(data.subcategoria);
                $('#variable').val(data.variable);
                $('#fecha').val(data.fecha);
                $('#valor').val(data.valor);
            })
        });      
        /* EDIT BUTTON */

        /* FORM BUTTON */        
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
                            var oTable = $('#procesos-table').dataTable();
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
                    <div class="alert alert-light alert-dismissible fade show" style="display:none;" role="alert">
                        <strong>Advertencia!</strong>&nbsp;Es posible que algunos datos aún no se encuentren cargados.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- <table style="width:100%;" class="table table-striped table-sm table-bordered table-hover datatable" id="procesos-table"> -->
                    <table style="width:100%; border-collapse: collapse !important;" class="table-sm table-bord table-hover" id="procesos-table">
                        <thead style=" border-collapse: collapse !important;">
                            <tr>
                                <th rowspan="2">CATEGORIA</th>
                                <th rowspan="2">SUBCATEGORIA</th>
                                <th rowspan="2" style="min-width:25px!important;" class="thcenter bluewhite"></th> 
                                <!-- <th rowspan="2">FECHA</th> -->
                                <th rowspan="2" class="thcenter bluewhite">NOMBRE</th>
                                <th rowspan="2" class="thcenter bluewhite">U.</th>
                                <th colspan="3" class="thcenter bluewhite">DIA</th>
                                <th colspan="3" class="thcenter bluewhite">MES</th>
                                <th colspan="3" class="thcenter bluewhite">TRIMESTRE</th>
                                <th colspan="3" class="thcenter bluewhite">AÑO</th>
                            </tr>
                            <tr>
                                <th class="thcenter">Real</th>
                                <th class="thcenter">Budget</th>
                                <th class="thcenter">%</th>
                                <th class="thcenter">Real</th>
                                <th class="thcenter">Budget</th>
                                <th class="thcenter">%</th>
                                <th class="thcenter">Real</th>
                                <th class="thcenter">Budget</th>
                                <th class="thcenter">%</th>
                                <th class="thcenter">Real</th>
                                <th class="thcenter">Budget</th>
                                <th class="thcenter">%</th>
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
