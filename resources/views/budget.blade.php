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
                margin-top: .5rem;
                margin-bottom: .5rem;
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .datatables-s .datatables-length{
            padding-top: 0.4rem;
        }

    </style> 
@stop

@section('js')
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script>
    <script src="{{asset("vendor/jquery-ui/jquery-ui.js")}}"></script> 
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
    <script>
        const budget_load = [];
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
            $("#select-button").val(0);
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
                    {data:'unidad', title:'U.', name:'unidad', orderable: false, searchable: false, width:'25px'},
                    {data:'fecha', title:'Mes', name:'fecha', orderable: false, searchable: false, width:'18%',
                        render: function(data){
                            return moment(data, 'YYYY-MM-DD').locale('es').format('MMMM').toUpperCase();
                        }
                    },
                    {data:'fecha', title:'Año', name:'fecha', orderable: false, searchable: false, width:'18%',
                        render: function(data){
                            return moment(data, 'YYYY-MM-DD').locale('es').format('YYYY');
                        }
                    },
                    {data:'valor', title:'Valor', name:'valor', orderable: false, searchable: false, width:'25%',
                        render: function (data,type,row){
                            let val = (data == null) ? '' : parseFloat(data).toFixed(8);
                            return '<input type="number" class="form-control" id="'+row['id']+'" value="'+val+'" style="width: 80%!important; margin: auto; height: calc(1.8rem + 2px)!important; text-align:center;">';
                        }
                    },
                ],
                columnDefs: [
                    {
                        targets: [2,3,4,5],
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
                budget_load.length = 0;
                $("#select-button").val(1);
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



        /* INICIO LOAD DATA */
            function isNumeric(str) {
                if (typeof str != "string") return false // we only process strings!  
                return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
            }

            $("#form-button").click(function(){ 
                if($("#select-form").valid())
                {     
                    if ($("#select-button").val() == 1)
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
                        text: "De existir budget cargado para la variable seleccionada, el mismo se suplantara por los registros actuales.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                        }).then((result) => {
                            if (result.value) {       
                                $('#form-button').html('Guardando..');    
                                budget_load.length = 0;                
                                var table = $('#data-table').DataTable();
                                var arrayid = table.column(0).data().toArray();  
                                for (var i=0; i < arrayid.length; i++)
                                {
                                    index = budget_load.findIndex(x => x.budget_id === parseInt(arrayid[i]));
                                    if (index == -1) {
                                        let val = $("#"+arrayid[i]).val();
                                        if ((!isNumeric(val) && val !='') || parseFloat(val) < 0)
                                        {
                                            var row_data = table.row(i).data();
                                            budget_load.length = 0;
                                            MansfieldRep.notification('Error en variable:<br>' + row_data['variable'] + '<br>Los Datos No Fueron Cargados','MansfieldRep', 'error');
                                            $('#form-button').html('Guardar');
                                            break;
                                        }
                                        else
                                        {
                                            val = (val != '') ? parseFloat(val).toFixed(8) : null;
                                            var budget =
                                            {
                                                budget_id:parseInt(arrayid[i]),
                                                value:val
                                            }
                                            budget_load.push(budget);                                
                                        }
                                    } 
                                }  
                                console.log(budget_load);
                                if (!(i < arrayid.length)) 
                                {
                                    $.ajax({
                                        url:"{{ route('budget.load') }}",
                                        method:"POST",
                                        data:{
                                            budget_load:budget_load,
                                            _token: $('input[name="_token"]').val()
                                        },
                                        success:function(data)
                                        {  
                                            if($.isEmptyObject(data.error)){
                                                $('#data-table').DataTable().ajax.reload(null, false); 
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

        /* INICIO MODAL IMPORT DATA */
            $("#btn-import").click(function(){ 
                console.log(1);
                if($("#select-form").valid())
                {
                    if ($("#select-button").val() == 1)
                    {
                        $('#form-button-import').val(1);     
                        $('#modal-title-import').html('Importar Datos'); 
                        $('#modal-form-import').trigger("reset"); 
                        $('#modal-import').modal('show');  
                    }
                    else
                    {
                        $("div.datatables-length").html('<div style="color:red;">Primero debe Cargar la <b>Variable</b></div>');
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
                    var table = $('#data-table').DataTable();
                    var arrayid = table.column(0).data().toArray(); 
                    console.log(arrayid);
                    if ($("#decimalseparator").val() == 0)
                    {
                        for (var i=0; i < import_data.length; i++)
                        {
                            let val = import_data[i].replaceAll(' ','').replaceAll(',','');
                            let val2 = (isNumeric(val)) ? parseFloat(val).toFixed(8) : val.replaceAll('-','');
                            $("#"+arrayid[i]).val(val2);
                        }  
                    }
                    else
                    {
                        for (var i=0; i < import_data.length; i++)
                        {
                            let val = import_data[i].replaceAll(' ','').replaceAll('.','').replaceAll(',','.');
                            let val2 = (isNumeric(val)) ? parseFloat(val).toFixed(8) : val.replaceAll('-','');
                            $("#"+arrayid[i]).val(val2);
                        } 
                    }     
                    for (let j = i; j < arrayid.length; j++)
                    {
                        $("#"+arrayid[j]).val(null);
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
                                        <button type="button" class="btn btn-primary" id="select-button">Cargar Variable</button>                                        
                                    </div> 
                                </div>                                        
                            @csrf
                        </form> 
                    </div>      
                    <div class="card-body" style="margin:auto; width:95%">
                        <div class="datatables-s">
                            <div class="datatables-length">
                            </div>  
                            <div class="datatables-filter"> 
                                <button type="button" class="btn btn-primary" id="btn-import">Importar</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-success" id="form-button">Guardar</button>   
                            </div>
                        </div>
                        <table style="width:100%" class="table table-striped table-bordered table-hover datatable table-sm" id="data-table"></table>  
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