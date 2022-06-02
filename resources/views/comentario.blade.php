@extends('adminlte::page')

@section('title', 'Comentarios')
@section('post_title', config('app.name'))

@section('content_header')
    <div class="section-header">
        <div>
            <h1>Comentarios</h1>
            <a href="javascript:void(0)" class="btn btn-success" id="add">Agregar</a> 
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
                margin-left: 1rem;
            }
        }
        .generic-body {
            flex: 1 1 auto;
        }


       
    </style> 
@stop

@section('js')
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script>
    <script>
        $(document).ready(function(){  
            console.log(moment().zone());
            $.get('comentario/'+moment().zone()+'/show', function(data){
                $(".lineatiempo").html(data);
            })
        });

        /* ADD BUTTON*/
        $('#add').click(function () { 
            $('#form-button').val(1);     
            $('#modal-title').html('Cargar Comentario'); 
            $('#modal-form').trigger("reset"); 
            $('#modal').modal('show');  
            $('#id').val('');   
        });
        /* ADD BUTTON*/

         /* EDIT BUTTON */
        $(document).on('click', '.edit', function(){ 
            var id=$(this).data('id');
            $.get('categoria/'+id+'/edit', function(data){
                $('#form-button').val(0); 
                $('#modal-title').html('Editar Categoria'); 
                $('#modal-form').trigger("reset"); 
                $('#modal').modal('show');
                $('#id').val(data.id);      
                $("#area_id").val(data.area_id).attr("selected", "selected");                    
                $('#nombre').val(data.nombre);                   
                $('#descripcion').val(data.descripcion);                   
                $('#orden').val(data.orden);                     
                $("#estado").val(data.estado).attr("selected", "selected");
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
                    url: "categoria/" + id,
                    type: 'delete',
                    data:{_token: $('input[name="_token"]').val()},
                    success: function (data) { 
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
                asunto: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                comentario: {
                    required: false,
                    minlength: 2,
                    maxlength: 250
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

        $("#form-button").click(function(){
            if($("#modal-form").valid()){
                $('#form-button').html('Guardando..');
                $.ajax({
                    url:"{{route('comentario.load') }}",
                    method:"POST",
                    data:{
                        asunto:$('#asunto').val(),
                        comentario:$('#comentario').val(),
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
                            MansfieldRep.notification('Comentario cargado con exito', 'MansfieldRep', 'success');            
                            $.get('comentario/'+moment().zone()+'/show', function(data){
                                $(".lineatiempo").html(data);
                            })
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
            <div class="generic-body">                     
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="timeline lineatiempo"></div>
                            </div>
                        </div>
                    </div>
                </section>
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
                            <label for="asunto" class="col-sm-2 col-form-label">Asunto</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto">
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
                    <button type="button" class="btn btn-primary" id="form-button">Cargar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}

@stop
