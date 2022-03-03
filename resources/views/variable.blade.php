@extends('adminlte::page')

@section('title', 'Variable')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Variable</h1>
            <a href="javascript:void(0)" class="btn btn-success" id="add">Añadir</a> 
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
            var dt =  $("#data-table").DataTable({
                dom:     "<'datatables-p'<'datatables-button'B>>" +
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
                        title: 'Listado Variable '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoVariable'+moment().local().format('DD/MM/YYYY')
                    }, 
                    {
                        extend: 'excelHtml5',
                        title: 'Listado Variable '+moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoVariable'+moment().local().format('DD/MM/YYYY')            
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: moment().local().format('DD/MM/YYYY'),
                        filename: 'ListadoVariable'+moment().local().format('DD/MM/YYYY'),
                        customize: function ( doc ) {
                            doc.styles.title.alignment = 'right';
                            doc.styles.title.fontSize = 12;
                        }
                    },
                    {
                        extend: 'print', 
                        text: 'Imprimir',
                        title: 'Listado Variable '+moment().local().format('DD/MM/YYYY'),
                    }
                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                bInfo: true,
                serverSide: true,
                responsive: true,
                scrollX : true,
                ajax:{                
                    url: "{{route('variable')}}",
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
                    {
                        class:          "details-control",
                        orderable:      false,
                        data:           null,
                        defaultContent: "",
                        width: '20px'
                    },
                    {data:'area', name:'area'},
                    {data:'categoria', name:'categoria'},
                    {data:'subcategoria', name:'subcategoria'},
                    {data:'nombre', name:'nombre'},
                    {data:'unidad', name:'unidad', orderable: false,searchable: false},
                    {data:'estado',name:'estado',
                        render: function(data){
                            if(data == 1){
                                return 'Activo';
                            }
                            else
                            {
                                return 'Desactivo';
                            }
                            
                        } 
                    },
                    {data:'action', name:'action', orderable: false,searchable: false, width:'50px'}
                ],
                order: [[0, 'asc'],[1, 'asc'],[2, 'asc']]            
            });
            function format ( d ) {
                return '<table class="table table-info table-sm text-left" style="width:100%" border="0">'+
                            '<tr>'+
                                '<td style="width: 33%">Descripcion: '+ d.descripcion + '</td>' +
                                '<td style="width: 33%">Creacion: ' + d.created_at + '</td>' +
                                '<td style="width: 33%">Actualizacion: ' + d.updated_at + '</td>' +
                            '</tr>'+  
                        '</table>';
            }
            // Array to track the ids of the details displayed rows
            var detailRows = [];
            
            $('#data-table tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    row.child( format( row.data() ) ).show();

                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                });
            });
        });

        /* DATATABLES */

        /* ADD BUTTON*/
        $('#add').click(function () { 
            $('#form-button').val(1);     
            $('#modal-title').html('Cargar Variable'); 
            $('#modal-form').trigger("reset"); 
            $('#modal').modal('show');  
            $('#id').val('');   
            $("#area_id" ).prop( "disabled", false );
            $('#categoria_id').html('');
            $("#categoria_id").prop( "disabled", true );   
            $('#subcategoria_id').html('');
            $("#subcategoria_id").prop( "disabled", true ); 
        });
        /* ADD BUTTON*/

         /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('variable/'+id+'/edit', function(data){
                var _token = $('input[name="_token"]').val();
                var area_id  =   data.area_id;
                var categoria_id  =   data.categoria_id;
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Variable'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);                    
                $("#area_id").val(data.area_id).attr("selected", "selected");                  
                $('#nombre').val(data.nombre);                   
                $('#descripcion').val(data.descripcion);                   
                $('#unidad').val(data.unidad);                   
                $("#estado").val(data.estado).attr("selected", "selected");
                $("#area_id" ).prop( "disabled", true );
                $.ajax({
                    url:"{{route('variable.getcategoria') }}",
                    type:"POST",
                    dataType: "json",
                    data:{_token:_token,area_id:area_id},
                    success:function(dato){
                        $('#categoria_id').html('');
                        $('#categoria_id').append(dato.result);
                        $("#categoria_id").val(data.categoria_id).attr("selected", "selected");
                    },
                    error:function(){

                    }
                });
                $.ajax({
                    url:"{{route('variable.getsubcategoria') }}",
                    type:"POST",
                    dataType: "json",
                    data:{_token:_token,categoria_id:categoria_id},
                    success:function(dato){
                        $('#subcategoria_id').html('');
                        $('#subcategoria_id').append(dato.result);
                        $("#subcategoria_id").val(data.subcategoria_id).attr("selected", "selected");
                    },
                    error:function(){

                    }
                });
                $("#categoria_id").prop( "disabled", true );
            })
        });      
        /* EDIT BUTTON */

        /* DELETE BUTTON */  
        $(document).on('click', '.delete', function() {
            let id = $(this).attr('id');
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
                    url: "variable/" + id,
                    type: 'delete',
                    data:{_token: $('input[name="_token"]').val()},
                    success: function (data) { 
                        var oTable = $('#data-table').dataTable();
                        oTable.fnDraw(false); 
                        swalWithBootstrapButtons.fire(
                            'Borrado!',
                            'El registro fue eliminado.',
                            'success'
                        )
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
        

        /* FORM BUTTON */        
        $("#modal-form").validate({
            rules: {
                area_id: {
                    required: true
                },
                categoria_id: {
                    required: true
                },
                subcategoria_id: {
                    required: true
                },
                nombre: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                descripcion: {
                    required: true,
                    minlength: 2,
                    maxlength: 250
                },
                unidad: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                estado: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 1
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
                    url:"{{route('variable.load') }}",
                    method:"POST",
                    data:{
                        id: $("#id").val(),
                        subcategoria_id:$('#subcategoria_id').val(),
                        nombre:$('#nombre').val(),
                        descripcion:$('#descripcion').val(),
                        unidad:$('#unidad').val(),
                        estado:$('#estado').val(),
                        _token: $('input[name="_token"]').val()
                    },
                    success:function(data)
                    {  
                        console.log(data);
                        $('#modal').scrollTop(0);
                        if($.isEmptyObject(data.error)){
                            $('#modal').modal('hide');
                            $(".alert-error").css('display','none');
                            $('#modal-form').trigger("reset");
                            $('#form-button').html('Guardar Cambios');
                            var oTable = $('#data-table').dataTable();
                            oTable.fnDraw(false);
                            if($('#form-button').val() == 1){
                                MansfieldRep.notification('Variable cargada con exito', 'MansfieldRep', 'success');
                            }   
                            else{
                                MansfieldRep.notification('Variable actualizada con exito', 'MansfieldRep', 'success');
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

        $(document).on('change','.area',function(){
            console.log('change');
            var _token = $('input[name="_token"]').val();
            var area_id=$(this).val();
            var div=$(this).parent();
            $.ajax({
                url:"{{route('variable.getcategoria') }}",
                type:"POST",
                dataType: "json",
                data:{_token:_token,area_id:area_id},
                success:function(data){
                    $("#categoria_id").prop( "disabled", false );  
                    $('#categoria_id').html('');
                    $('#categoria_id').append(data.result);
                },
                error:function(){

                }
            });
        });

        $(document).on('change','.categoria',function(){
            console.log('change');
            var _token = $('input[name="_token"]').val();
            var categoria_id=$(this).val();
            var div=$(this).parent();
            $.ajax({
                url:"{{route('variable.getsubcategoria') }}",
                type:"POST",
                dataType: "json",
                data:{_token:_token,categoria_id:categoria_id},
                success:function(data){
                    $("#subcategoria_id").prop( "disabled", false );  
                    $('#subcategoria_id').html('');
                    $('#subcategoria_id').append(data.result);
                },
                error:function(){

                }
            });
        });


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
                                <th></th>
                                <th>Area</th>
                                <th>Categoria</th>
                                <th>Subcategoria</th>
                                <th>Nombre</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                                <th style="min-width:50px!important;"></th>            
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
                            <label for="area_id" class="col-sm-2 col-form-label">Area</label>
                            <div class="col-sm-10">
                                <select class="form-control area" name="area_id" id="area_id">
                                    <option value="" selected disabled>Seleccione Area</option>
                                    @foreach($areas as $id => $nombre)
                                            <option value="{{$id}}">{{$nombre}}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="categoria_id" class="col-sm-2 col-form-label">Categoria</label>
                            <div class="col-sm-10">
                                <select class="form-control categoria" name="categoria_id" id="categoria_id">
                                    <option value="" selected disabled>Seleccione Categoria</option>
                                </select> 
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label for="subcategoria_id" class="col-sm-2 col-form-label">Subcategoria</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="subcategoria_id" id="subcategoria_id">
                                    <option value="" selected disabled>Seleccione Categoria</option>
                                </select> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="descripcion" class="col-sm-2 col-form-label">Descripcion</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion">
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label for="unidad" class="col-sm-2 col-form-label">Unidad</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="unidad" name="unidad" placeholder="Unidad">
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                            <div class="col-sm-10">
                                <select class="form-control company" name="estado" id="estado">
                                    <option value=0>Desactivo</option>
                                    <option value=1 selected>Activo</option>
                                </select> 
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
