@extends('adminlte::page')

@section('title', 'Conciliación Data')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Conciliación Data</h1>
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
                flex: 0 0 90%;
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
            padding: 1rem 1rem 0 1rem;
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
                width: 90%;
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
                margin-top: .5rem;
                margin-bottom: .5rem;
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .datatables-s .datatables-length{
            padding-top: 0.4rem;
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

            .datatables-length{
                display: none!important;
            }

            #fieldset-permisos
            {
                width: 85%;
                margin: 2rem auto;
                max-width: 968px!important;
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

            .datatables-length{
                display: flex!important;
            }

            #fieldset-permisos
            {
                width: 90%;
            }  

            .user-select{
                margin-top: 2.5rem!important;
                margin-bottom: 2rem!important;
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

        /* PROPIOS DE LA VISTA*/
        .dtrg-level-0 > td{
            background-color: #525C66;     
            color: white;
            font-size: 1.1rem;
        }
        .dtrg-level-1 > td{
            background-color: #D6D6D6;
        }

        .thcenter{
            text-align:center;
            vertical-align: inherit!important;
        }

        .table-bord td, .table-bord th {
            border: 1.2px solid #dee2e6;
        }

        /*SCROLLBALL HIDDEN */
        .dataTables_scrollBody {
            -ms-overflow-style: none;  
            scrollbar-width: none; 
        }
        .dataTables_scrollBody::-webkit-scrollbar { 
            display: none;  
        }

    </style> 
@stop

@section('js')
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
    <script>

        const conciliado_load = [];
        const var_calc = [10072, 10075, 10078, 10081, 10084, 10087, 10090, 10095, 10099, 10102, 10105, 10108, 10002, 10006, 10008, 10013, 10015, 10020, 10027, 10028, 10032, 10037, 10038, 10040, 10046, 10049, 10053];
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#conciliado-table').dataTable();
                    oTable.fnAdjustColumnSizing();
                }, 
            350);
        });
        
        $(document).ready(function (){ 
            $("#btn-select").val(0);
            var dt =  $("#conciliado-table").DataTable({              
                dom:    "<'row'<'col-sm-12'tr>>",            
                lengthMenu: [[10, 25], [10, 25]],
                lengthChange: false,
                paging: false,
                processing: true,
                bInfo: false,
                serverSide: true,
                responsive: true,
                scrollX : true,
                select: true,        
                ajax:{    
                    url: "{{route('conciliado.getvariables') }}",
                    type: 'POST',
                    data: function(d){
                        d.area_id = $( "#area_id" ).val(),
                        d.month = $( "#month" ).val(),
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
                    {data:'subcategoria', title:'subcategoria', name:'subcategoria', visible:false},  
                    {data:'var_orden', title:'var_orden', name:'var_orden', visible:false},  
                    {data:'variable', title:'Variable', name:'variable', orderable: false, width:'40%'}, 
                    {data:'unidad', title:'U.', name:'unidad', orderable: false, searchable: false, width:'4%'}, 
                    {data:'mes_real', title:'Mensual Real', name:'mes_real', orderable: false,searchable: false, width:'28%',
                        render:function(data, type, row){
                            if (data != '-')
                            {
                                let val = parseFloat(data);
                                return '<input type="number" class="form-control" id="r'+row['variable_id']+'" value="'+data+'" readonly hidden>'+val.toLocaleString('en-US', { minimumFractionDigits: 12 });
                            }
                            return '<input type="number" class="form-control" id="r'+row['variable_id']+'" value="" readonly hidden>'+data;
                        }
                    },  
                    {data:'conciliado_data', title:'Conciliado', name:'conciliado_data', orderable: false, searchable: false, width:'28%',
                        render: function (data,type,row){
                            if(var_calc.indexOf(parseInt(row['variable_id'])) != -1)
                            {                                  
                                return '<input type="text" class="form-control" id="'+row['variable_id']+'" value="" style="width: 80%!important; margin: auto; height: calc(1.8rem + 2px)!important; text-align:center;" readonly>';
                            }
                            else
                            {
                                let val = (data == null) ? '' : parseFloat(data);
                                return '<input type="text" class="form-control" id="'+row['variable_id']+'" value="'+val.toLocaleString('en-US', { minimumFractionDigits: 12 })+'" style="width: 80%!important; margin: auto; height: calc(1.8rem + 2px)!important; text-align:center;">';
                            }
                        }
                    },
                    {data:'variable_id', title:'variable_id', name:'variable_id', visible: false}    
                ],  
                rowGroup: {
                    dataSrc: ['subcategoria']
                },
                columnDefs: [
                    {
                        targets: [3, 4, 5, 6],
                        className: "dt-center"
                    }
                ],
                orderFixed: [
                    [0, 'asc'],
                    [1, 'asc'],
                ]  
            });
            $("div.datatables-length").html('');    
            
        });

        $("#modal-form-loadvbles").validate({
            rules: {
                area_id: {
                    required: true
                },                
                month: {
                    required: true,
                    min: 1,
                    max:12
                },
                dias_conciliacion: {
                    required: true
                }
            },
            messages: {  
                month: {
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

        $("#btn-select").click(function(){
            if($("#modal-form-loadvbles").valid()){   
                conciliado_load.length = 0;
                $("#btn-select").val(1);             
                $("div.datatables-length").html('Datos para el Área de&nbsp<b>' + $('#area_id option:selected').text() + '</b>&nbspcorrespondientes al mes de&nbsp<b>' + $('#month option:selected').text()+'</b>.');  
                $('#conciliado-table').DataTable().ajax.reload(null, false); 
            } 
        });

        /* INICIO LOAD DATA */
            function isNumeric(str) {
                if (typeof str != "string") return false // we only process strings!  
                return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
            }

            $("#form-button").click(function(){ 
                if($("#modal-form-loadvbles").valid())
                {     
                    if ($("#btn-select").val() == 1)
                    {
                        const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: true
                        })

                        swalWithBootstrapButtons.fire({
                        title: 'Estas seguro?',
                        text: "De existir datos conciliados para el mes seleccionado, los mismos se suplantaran por los registros actuales.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                        }).then((result) => {
                            if (result.value) {       
                                $('#form-button').html('Guardando..');    
                                conciliado_load.length = 0;                
                                var table = $('#conciliado-table').DataTable();
                                var arrayid = table.column(6).data().toArray();  
                                for (var i=0; i < arrayid.length; i++)
                                {
                                    index = conciliado_load.findIndex(x => x.variable_id === parseInt(arrayid[i]));
                                    if (index == -1) {
                                        let val = $("#"+arrayid[i]).val();
                                        let val2 = $("#r"+arrayid[i]).val();
                                        val3 =  (val2 == '') ? null : parseFloat(val2);
                                        val = val.replaceAll(',','');
                                        if ((!isNumeric(val) && val !='') || parseFloat(val) < 0)
                                        {
                                            var row_data = table.row(i).data();
                                            conciliado_load.length = 0;
                                            MansfieldRep.notification('Error en variable:<br>' + row_data['subcat'] + ' - ' + row_data['variable'] + '<br>Los Datos No Fueron Cargados','MansfieldRep', 'error');
                                            $('#form-button').html('Guardar');
                                            break;
                                        }
                                        else
                                        {
                                            val = (val != '') ? parseFloat(val) : null;
                                            var variable =
                                            {
                                                variable_id:parseInt(arrayid[i]),
                                                value:val,
                                                valuereal:val3
                                            }
                                            conciliado_load.push(variable);                                
                                        }
                                    } 
                                }  
                                if (!(i < arrayid.length)) 
                                {
                                    console.log(conciliado_load);
                                    $.ajax({
                                        url:"{{route('conciliado.load') }}",
                                        method:"POST",
                                        data:{
                                            area_id: $( "#area_id" ).val(),
                                            month: $( "#month" ).val(),
                                            conciliado_load:conciliado_load,
                                            dias_conciliacion: $("#dias_conciliacion" ).val(),
                                            _token: $('input[name="_token"]').val()
                                        },
                                        success:function(data)
                                        {  
                                            if($.isEmptyObject(data.error)){
                                                $('#conciliado-table').DataTable().ajax.reload(null, false); 
                                                $('#form-button').html('Guardar');
                                                if($('#form-button').val() == 1){
                                                    MansfieldRep.notification('Registros cargados con exito', 'MansfieldRep', 'success');
                                                }   
                                                else{
                                                    MansfieldRep.notification('Registro actualizado con exito', 'MansfieldRep', 'success');
                                                }                          
                                            }else{
                                                $('#form-button').html('Guardar');
                                                console.log(data.error);
                                            } 
                                        }
                                    });
                                }                         
                            
                            } 
                        }) 
                    }
                    else
                    {
                        $("div.datatables-length").html('<div style="color:red;">Primero debe Cargar las <b>Variables</b></div>');
                    }
                }                             
            });
        /* FIN LOAD DATA*/

        /* INICIO MODAL IMPORT DATA */
            $("#btn-import").click(function(){ 
                if($("#modal-form-loadvbles").valid())
                {
                    if ($("#btn-select").val() == 1)
                    {
                        $('#form-button-import').val(1);     
                        $('#modal-title-import').html('Importar Datos'); 
                        $('#modal-form-import').trigger("reset"); 
                        $('#modal-import').modal('show');  
                    }
                    else
                    {
                        $("div.datatables-length").html('<div style="color:red;">Primero debe Cargar las <b>Variables</b></div>');
                    }
                }
            });

            $("#modal-form-import").validate({
                rules: {
                    import: {
                        required: true
                    },
                    decimalseparator: {
                        required: true,
                        number: true,
                        range: [0,1]
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

            $("#form-button-import").click(function(){
                if($("#modal-form-import").valid()){  
                    var import_data = $("#import").val();
                    import_data = import_data.split(/\r\n|\r|\n|\t/);
                    if (import_data[import_data.length-1] == '')
                    {
                        import_data.pop();
                    }
                    console.log(import_data);
                    var table = $('#conciliado-table').DataTable();
                    var arrayid = table.column(6).data().toArray();  
                    var z;
                    if ($("#decimalseparator").val() == 0)
                    {
                        let j = 0;
                        for (let i=0; i < import_data.length; i++)
                        {
                            let val = import_data[i].replaceAll(' ','').replaceAll(',','');
                            let val2 = (isNumeric(val)) ? parseFloat(val).toLocaleString('en-US') : val.replaceAll('-','');
                            if (var_calc.indexOf(parseInt(arrayid[i+j])) != -1)
                            {
                                j=j+1;
                            }
                            $("#"+arrayid[i+j]).val(val2);
                            z = i+j;
                            console.log(i, i+j, val2, $("#"+arrayid[i+j]).val());
                        }  
                    }
                    else
                    {
                        let j = 0;
                        for (let i=0; i < import_data.length; i++)
                        {
                            let val = import_data[i].replaceAll(' ','').replaceAll('.','').replaceAll(',','.');
                            let val2 = (isNumeric(val)) ? parseFloat(val).toLocaleString('en-US') : val.replaceAll('-','');
                            if (var_calc.indexOf(parseInt(arrayid[i+j])) != -1)
                            {
                                j=j+1;
                            }
                            $("#"+arrayid[i+j]).val(val2);
                            z = i+j;
                        } 
                    }     
                    console.log('z',z);
                    for (let i = z+1; i < arrayid.length; i++)
                    {
                        $("#"+arrayid[i]).val(null);
                    }
                    $('#modal-import').modal('hide');
                    $('#modal-form-import').trigger("reset");
                    
                    
                }
            });

            $('#modal-import').on('hidden.bs.modal', function () {
                $("#modal-form-import").validate().resetForm();
            });
        /* FIN MODAL IMPORT DATA */
    </script>
@stop

@section('content')
    <div class="row" style="justify-content: center; overflow:auto;">
        <div class="generic-card">
            <div class="card">
                <div class="generic-body"> 
                    <fieldset id="fieldset-permisos" class="form-group border" style="padding: 1rem 2rem;"> 
                        <form action="post" id="modal-form-loadvbles" name="modal-form-loadvbles" autocomplete="off">                            
                                <!-- <legend class="w-auto">Seleccionar Área y Mes</legend> -->
                                <div class="row" style="justify-content: space-around;">
                                    <div class="form-group col-md-3">
                                        <label for="area" class="col-form-label">Área</label>
                                        <div>
                                            <select class="form-control" name="area_id" id="area_id">
                                                <option value="" selected disabled>Seleccione Área</option>
                                                <option value=2>Mina</option>
                                                <option value=1>Procesos</option>                                                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="categoria" class="col-form-label">Mes</label>
                                        <div>
                                            <select class="form-control" name="month" id="month">
                                                <option value=0 selected="selected">Seleccione Mes</option>
                                                @foreach ($months as $num => $name)
                                                    <option value="{{ $num }}">{{ $name }}</option>
                                                @endforeach                                       
                                            </select> 
                                        </div>
                                    </div>       
                                    <div class="form-group col-md-2">
                                        <label for="dias" class="col-form-label">Días a Conciliar</label>
                                        <div>                                            
                                            <select class="form-control" name="dias_conciliacion" id="dias_conciliacion">
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5 selected="selected">5</option>
                                                <option value=6>6</option>
                                                <option value=7>7</option>
                                                <option value=8>8</option>
                                                <option value=9>9</option> 
                                                <option value=10>10</option>                                                                                     
                                            </select>  
                                        </div>
                                    </div>                              
                                    <div class="form-group col-md-3" style="align-self:self-end">                                    
                                        <button type="button" class="btn btn-primary" id="btn-select">Cargar Variables</button>
                                    </div> 
                                </div>                                        
                            @csrf
                        </form> 
                    </fieldset>              

                    <div class="card-body">
                        <div class="datatables-s">
                            <div class="datatables-length">
                            </div> 
                            <div class="datatables-filter"> 
                                <button type="button" class="btn btn-primary" id="btn-import">Importar</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-success" id="form-button">Guardar</button>   
                            </div>
                        </div>
                        <table style="width:100%" class="table table-striped table-bordered table-hover datatable table-sm" id="conciliado-table"></table>  
                    </div>                    
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
                            <label for="separadordecimal" class="col-sm-12 col-form-label">Pegue Columna de Datos</label>
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