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
            max-width: 83rem;
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
        .buttonclass{
            background-color:#525C66
        }

        .table-bord td, .table-bord th {
            border: 1.2px solid #dee2e6;
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
        .pink_percentage{
            color: rgb(219, 14, 202);
            font-weight: bold;
        }
        .teal_percentage{
            color: rgb(135, 231, 235);
            font-weight: bold;
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
    <script src="{{asset("vendor/moment/moment-with-locales.min.js")}}"></script> 
    <script src="{{asset("vendor/jquery-ui/jquery-ui.js")}}"></script> 
    <script src="{{asset("assets/DataTables/Select-1.3.4/js/dataTables.select.min.js")}}"></script> 
    <script>
        var idx = -1;        

        /*PRESS NAV-LINK BUTTON*/
        $('.nav-link').click(function (){ 
            setTimeout(
                function() {
                    var oTable = $('#procesos-table').dataTable();
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
                $('#procesos-table').DataTable().ajax.reload(null, false);
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
        

        /* DATATABLES */
        $(document).ready(function(){            
            
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
            var tabledata = $("#procesos-table").DataTable({
                dom:    "<'datatables-p'<'datatables-button'B>>" + 
                        "<'datatables-s'<'datatables-length'l><'datatables-filter'f>>" +
                         "<'datatables-t'<'datatables-table'tr>>" + 
                         "<'datatables-c'<'datatables-information'i><'datatables-processing'p>>",
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Periodo',
                        buttons: [
                            {
                                text: 'DÍA',
                                action: function ( e, dt, node, config ) {
                                    dt.columns( [-10,-11,-12] ).visible( ! dt.column( -10 ).visible() );
                                }
                            },
                            {
                                text: 'MES',
                                action: function ( e, dt, node, config ) {
                                    dt.columns( [-7,-8,-9] ).visible( ! dt.column( -7 ).visible() );
                                }
                            },
                            {
                                text: 'TRIM.',
                                action: function ( e, dt, node, config ) {
                                    dt.columns( [-4,-5,-6] ).visible( ! dt.column( -4 ).visible() );
                                }
                            },
                            {
                                text: 'AÑO',
                                action: function ( e, dt, node, config ) {
                                    dt.columns( [-1,-2,-3] ).visible( ! dt.column( -1 ).visible() );
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
                        extend: 'pdfHtml5',
                        className: 'buttonclass',
                        filename: 'DailyReport'+getDateSelected(), //function () { return getExportFilename();},
                        customize: function ( doc ) {
                            //doc.styles.title.fontSize = 10;
                            //Remove the title created by datatTables
                            doc.content.splice(0,1);
                            //Create a date string that we use in the footer. Format is dd-mm-yyyy
                            var now = new Date();
                            var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                            // Logo converted to base64
                            // var logo = getBase64FromImageUrl('https://datatables.net/media/images/logo.png');
                            // The above call should work, but not when called from codepen.io
                            // So we use a online converter and paste the string in.
                            // Done on http://codebeautify.org/image-to-base64-converter
                            // It's a LONG string scroll down to see the rest of the code !!!
                            var logo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAT0AAABjCAIAAABmCkD5AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAJVFSURBVHhe7f1nkF3XlaAL3nO9y5veI5EJ7w0JOoAOhEQjR0qUSi2pqqZK010VL+K9mJkaEy/mz1T9nYiZmIj5MRET0T3VPWXULZWKEiVS9CJBAoR3CSATibRI7931dr61182TF+lgSKqq3vTCwcl99tlm7eXXcdcqFAoOh4OdZck+l8tx6Ha72G8AppN02Ri0mQ33bP9VQD6Xt5xOprYLxfo8W66Qz1lOl1M2qcxmc/bal5G3HDlHnr/OvDRyWo6UHDkcLuodKYfDZ44oex0OT96RSiU8gUCukHVZbkdW6OlyuSzL9MnLocOSKQoOM6WCwapgmmTMvC6XI5GRY5fHqQjFcw5QoyyHOYfHcddyFGyc/8Ckvh95KBWG+0dvaeR8Kpl2uT05C1oKqdOZPJShIAR2OdxLBQFTgCn8dcZioaCrkMvIIC6Po+AqWM5CwQUnnYbayvcS1FYCpxTZQgF5yYGDHJraVM6RM8jo1EGdGdZYxQXmisvMgx4Ax2kAgKYZJO8o5BxWDvQsdyCTzlteLw1gtL0QKzpfXl5u5QV/kSiXT8WmqLfINKIrTUsAIUYutD6TTrtcbspU5vJZr0dk9X6o/3Dc+hIBzD1eL2jYs5eWFVSHKRgFkwbwiMVqwXIVya2KNBPP+v0eU2HAuzwWReiiNEewAh4hnbOQd4qswESGNiPdrbecyCQSWbfPa9qnTaUthWklYLqQTArzAGavcFsIo80dG0qprfCHobnOu/FcpbjdP1bCgoIjlstDHEiCqmC8bg0tJpLJiYlxbZN0hpqbG+oqAtVhx3TU0Tu6kI0vVoe9m2vLG8q9jkLW58jKUKiH01+qt6ChmKwi2zJwKpNOeb1qnB05I/8Olw/zAaew2sB81jHYPwk+zxzZB2vQW6eVh78FvIVp4DZ/V+ptPiOqiB+w3NlsIVFwsca4wzG66Igm8qmFaQZ0T9157MjhioryUDDgENMPu52gZOUZ39AR2QUwP5AJr6sSDHAoZ5cUWwtaaYN9SAEoPaWwXv1XDWCby2XRW8qpZNLn9yuqapJsf8Xa1e4qpNIpj9tne2CX25UwtEYVB+eSb/72Perz4So5DSP9FYvRRFk4UFVVXYjNVEbCO7a27qlzMqWqnm65bMYFi9SlGkCAzN5FlbITObjSLexfSDlixu1m08wsEMjF86nonel4W1PdK8ePNYS996m3DwuKEbS6L54VCoLGBm1XIFbasvSU1tvSooW0xDvLGnLl+vDN2z23e3p9geBC2hEOhVKZrM/jPnTo8L7NVTfuzJw6fSoiDHc80lb37LEn2mqDrnTK54UJsK0geovdNEoLGM0STUDH1gSq8bRQmrIWbGaBEvj0jyZOnzl/Z2Ss0uv40x98u6XCH3aji8JodNzhFhNv6630VEAOC8YQW3hV7/TMXM/w5OBiZnAmHk8k+ycXWVTAkQpmFn/yox/u3VTmzyNC4oFM1yW9hUCIaSKRmJycmpiYiMdj9E6lUmVl4WQy5fEU3Usmk3E6nS0tm/bs3mMLPX3FKZlDmwd6ygabE/8iwOzR6OKZM2d9Pu/c3HwgEECFMJuc0rzA6/WEQuFwONTQ2FgeKSdU1kWpVoA7jdgIYK73Tb7xqzepjHvK8p6Q5QkknYFcOklNyF2YSWSCheSupso/fukpLL14ziWvaxXSEjZrnGygVG9VCHqGFn/5m9/Nph0Jh88fLEumMn6fp5BJRGMxBBEWzkVjj+/d+uOXn42UlRMglJoeBZv+K4C1sKexHtqg9Qp3n13GE7C1V6OztUC0Yk3+rtujBGw01CWUAqeSLqdqSDzh+Lu///nYfByNjVRUj89FoZK08UVSmWIwEo8tVFZUU8BfVbozexrDP3ztuDuT9xYyPrdZIJEKimrM95I7NE5rJW0ESKSw2lpOp8V04HjnAUcAzWq/PdBxZxw0HC6/z5l1ZmL/4fvfQMcImPGl7HCnBZePZMwHZcQBm6nNXOYPqOeoHBub/OUvf3lxMlvwR8orahKuICvipJVasOJTjLlrU1nAkFgJDIVcf/3Xf0MJlk3PzHR13b558yZ6m0qlJRPIQ6lYIpFERJJEaZmMmIJkyu/3NTU3yqoNo4oyIWBoUaxeA9ar/+ogm8XQuFDOmZkZlsa6sE0IH3usEivK5/MUFhejcyj0/Pz09DRKiwnHkWUyKafTLTjDY5JJq4AZrSwPVTe01G3a4g+GUwUPMbPT5U5Z3pzTYzndHqIpbyi6MB905ba11CEJhXzB42IIicvMUIyFSoiM5CWUggWYWzG51F65fvvG4GQhWJP1VWQ8lTmPP+evpj7k825q2bxvz+7jTx157OC+ynCIIZWWtkZtDIY7a7Vc0pJVKg3zcyZELSjeulFcvdFbV2TKd8EKJVwTSm2HDiE1MrNsOMic05koOBbiorScjaYyjxw9/uKTB9pamq8NzqYyOcgezTqyBZLESo83GE2nc5bb6XYnE9FoInv8sZ2wwONyE0c6RWhBFQ8jUywjTKUu5u4NL8UKiJNJEnVDkD799PP/9v6p9us3+qcWCNFhlsPjIzlFPB45sLcOYy6rlkUV2DuZt+A2hBA5YmdIbRUI3UWTiZDdkbLKLft27drl9EeGphdSnkjK4YlbPqIzoqpdO7c1lcuQBu8iiN4yJu4Ise7p6XW73ZIHWx6TgiO1Tp8v4PN5EHGPJxAK+dEBFrNly1YZSJltcFpTMMyZZVjd4KsGlJZQGYKPjY2hkyAcDAYJH0wWIFyhrIc+A7RBgd1uD0Gvx+NGgHJ4XcCC5QUnDtjhaKgJbWqItLbWtzY2DY5OTqUsDwrrj9DScgX8rkIqmXLks/u2b4Z+zGPMtcV8cmFDdNUiJLOokPDMEMgiq7HwJ795++PZnC9l+S1fRZJQyHJlk4tuT+jIrpZvPrljW2vl5spQJIBLcogpEI0iNc6L0zCHjMWikBXjRoQ/1NKC6XTjnFj6ZbGkj0GAsKKICP+lHi5LcKoRQVF1Vc5VeIzcY24kdSfrYsa7/a2gIjjQzrSXkvZjY2TzV05RL0Mwk5UFd10OjLElJwsRnI6FnOOtD8/1DI64AuX79+759rPbQwGrrtIXTznv9HRmLXdLxAON09FpEkvL5XVhRvNpltFaGz60txV6+Jx5N7PKsKo+/BeSKc6GPAZVoZ3YUzYYRhNyG5cJatLptMPl9gQC0bnZrK/MZTmTDl/UHck6fegsSktc9ejuNvQWr2rSK9SDntCbyAhyMSDjE3OJyUBl4UQ6k0X8mEauWVSHGurrh2eTk7OLrmC1G3+Tx1HnDmxvQW+JeCG98EOwNE4glUq0t7ffuTNUVlbp9QbxReYs6Bf/pVJkz94czivDGgg1idei4jAMj/PmWgsFm9Y2aAN7++KwegpgzUoFzlguJ8o3OzvLodvtz2YKyKSGx/mchcdlIz2jPpnIhEPlnIIaxWse+FC35vlCNLQY++fKOYI5R53bsaPOubk66M3GkZJcOubyhnL5TLLgI7gl+5peEDLmzKUsuXQp0oIWM5wz63ASz4AZh86cRF/pguN678JA1FFf11DwlzOO3+3x5NNYBL+VeuFgc3XYUe52+IohG6Mh7cZJm81yZMQsyMUsZC6Lq8zn07lcJp2XCJylslFIOh0ZaQE2ssmlMjTWaQ7YO4uNIRqrdrk8LiPcEt47nLgF8M9LHms2SRELrMUhfoMuIgzS0+Cm40tSwBQisnJM4JgpQGbRT4kPRWwkV1HEWEUhn9SNU+ISLCcoMh4TSE57Z9JbvZkYclNdFaRgC7gc33qyeX9bg2d+ID0/6YiOk6rUB53u2CREcyQWHLnk3h3bkgtp6CZXdEFVyQX1XEYwiMDzsNXEyaBayBhiEGnInQYoKSQpkF9nWAsKppTc++QTxN4//fPvPn54H7PAfTYZPpNwIgayTBTcSx6TN/VCpSVYDi40zfYgTXl3LuG1kmWWAy5vrS1zkXfF55LZYuTvSkUZkw0pghxYf/4I0vPzC9PTC9oIpgIUhKOSw8s/dI4/bKLFDrQ3E4vGnJgVY5cYcFWU9a8FVJASSaJiSUGXQWRRjLwpi1Eplg1AgenpKeyX2hpZphFWt+gfoUvG68z5CmmGqIyEoXI+PiWcy6ZFgfMZkhNPpPa9s9ens3IXxwwIu2VYyIdAwEg2ez5SnETOcfJihydQPjIXR/8zTi+jZbIZdz5VIdKydKW6kGXTXmgNuYyWAZEzy81eD9E6o3tya0qBppSpMRMjl6LeRqFwS6aFaaOQSWdR+ozoqt7CQB7E8NhIo8DSv5CPGRIjFfJHySVazR+j22R0heWLqGq25ICBkKiihsupHKRZAlmaaaI3vohEbt7uSWWySWcA3agOy7VdNgS90e149pE9T+1pa/BlD27f/O9fO/7NZw6S1mZGrteXuV99fNvRQ8215V6IljP2UaDgQkFZfhEro8CATCoizSlBSa8klQKOSS5nGWyVKCG/N+eyCVwCLgcGIKMWERALaZQQtus9RwFzaglClnAHLjMmh2KvzQUtBWY0pn8ZJfEhyGgstuDzSYcCUaVcCoVqywOr6jIhZdXqublZuYSAHNr2w8j/vx5YsikCpOULC4tKKrFHBoph/SqkVVLHx8fjS3prgkaWKSt1O0UlsJTaDCrH8w6X14+BT6bjDrcXZaPe8gT6hyYG+ycXUVQISLhu4m0TjIlMFC98YWxdPhxO/2hifGIMRx0MlaGxfuFDJiC8csxHY+z19pJReLmBqIwvkM5Yfras5S9YXrTEK4qxpBsY8jyan/HlM8502gViBG94OZlV7mNqM1ROLk0brGTLZ5gcd+H2ykbMmkpnUsYopArZFM7cWB+6EjMyqU/urKgYo7rZrAQxeQKKLGGFiSwkJjZiRyMzvswuOYfMKZUiVaJNgYK7zHLJlhE3SIQsrpgNMhK/yLWfpeujAG7ZC/Ec+cOt1d995fmf/uS1P3p+/45q555Ngb/8/on/07//4f/43Wcf27czmM3LxWQJdbB/iLEbpaU7iMn9ORCTgFIGhKAYI0w4jaU9K5R4Aey8sheOWyAfhC+Qi9mRAHNVbE2gm7nPawAL5Q7BIxNnSQidzWVyuUQejuBroVXOmXK4OQUwpm0L1I2vCUK7aDSGBBFDUha/Svxg/AMrkkURZS4pOgqMVhMwkAcieKgyTfSOka0k/7Kg6roCGb0KBZ3thawHrAgKkOQTg7BuahgKx6VnxR5LeGP8ihH6WDLtSc1vrwvhFQuJOVTO4Q3NpXJ4hnje6h4cx5HGTZ6YyQhf5CK2oa24GnQT425546nClavtVG5qqDm0rRH9J7nlkMSJvQ0gQeAlomayUdzasjmRU1LFqAS02ayFtyROtiNPYm6SbeIFCfk0ugaMi4MslitXKKRBTJySJTE2CEcz+XhMTIbL62HIVD5trAaxpUTU9DdDGF+dJUxGxIuBALjoWdlyxQ0gzdK1Q0ZZhHoc4k+zANoQw8MkNhqlzfMMYB4vOCBjyhX0ReQqMRDw++nPanCMViHrdKWDzkKF26r2WcTD3ryjrTa4rcbbECi0lrvLPHmf1wUpUukcFk2jEpaMBcFgsWqywHyeTEnMRDGaMGQBJEEwVyfyDg8qvRzhFkiVGcERDhT1SvmlIB3QRWGHyR0MfzDaNh2gttuVc4mYkSkZDXf7BDc5eRdI2pUqRsuAEkzBKbGUVLk8HosMFv/jcYOY/FsO5YwqqNCrWKPqyWTRHem+1PH+a4PFRckCNPJHWaSKgip36d6sgnWK6V1ajmTCnJTzWlO83Yr+mUOBqqrq1s2bKEheSlNvqOAOx61gxx2ctiOdlUzO5+OM3MItFERmiNNo73R60GYy4c6e/oAj9ciuNiqJkRykSUu2lsiQvTDJgNw/yJPNiq5wyMhsxjHLIuAY0TH/8y5voYC3VE+IiSCvTOikkiOYenNInixxuyitOUtLkaFsLuBxBkMhXDHo+uV5hQJKgtzLZiYFmNfnlBlZVMHhwcRRZ84ISBvk0mzmMhVxuSSRkBZ3KtphLAg2zYuH12ZmXP6aTbqQnY73d4dDIcqxnBOqyjj8Zwl5dzqZCzm9ZW6nM5cmmvDnHWUlqQHrQV1TCK8r4PL6cHdU0k2WzD6Xkk3y7WzGkrAZ1c0xigTVmBioKbdtltkMsQpk+MIKBtEasiT2sDaRdxLJFytpYsigaQjdmBH3KxtZkrA/w8YsuWRSlZke8qiUAcaUqA0fsBKWaYt7LZYyGfiehfkSFQIcEUoi63aLJdVFrElx8UhSNmeXxP5fHYBcLpvByughC9DCXVCCPSzWArZJDuUoLzLHJhdJ2IQChsJCxHRCRi7EZh7f0VgV8JCOorpy0u1Nu4N989nLN/q8PsvmgHgIgwPiwh7HRdbVNTybniVIDm5tlLt2DILmF8dB8pCGpDz6tgzGWxZTypw8WQkgGWySxpF2Uu1ypN2urCsgD/fRw+UhIEcQ8QSav9FBQkctc+REzxF/r8MVTFkWfROZPC6XaFqubuXRKx+eKp11ptmbLswiV7PyMqksqCAXuZF02YwgsnGCcXJpueWGfmctLxs2CXcq16KI1pHdgqTytHHIoymItemLOhWyuLVYOjufTJN3MAS2jDwiWnCnhAACPl9AUvFM2uPxisHCpuDWmNdyx12+xZwz5/UJzqZx0WwBBUypSWdcHssdgCwEFLRhEwxdgawjkCp4UjkW68hYTrlataS/4vSME74L3GIrfJ6ilQQwUHLh0FyyNSA5ElIE14iylB0FOOL3s9qMSaZs8DmzmiKtguXKZQ0mHsb8S2GJl0Axy0U5dVsScbhFViwFU4MJ3fjSlIr86u2rA+M5RUVSqfTCgpgYPK2d3C4bI3tpBlAqAgpWp2EIgHAv6bIAgSZg8y2WKfhycQp1Vc6De3fl0knNb5PZjD9YQeGTSzfnjbRInCxhpsThcgxYLgRldNFx+mpnwR85tKstGHBUuNLx2ILf7WEcdbmZdCpuy6kB+xIOUu8jdEZjiX5ZHnwzF3IYFnPABmazlj/qLlu0/CmjM2xE5uKC5DaHxP0SOrJnBrcnbbmilmsu7eqfjP/9G29//M57sYw8NCty7HAmWJnHSeBKYD+XKjA4E4FljshPRVkuXcmNHLfceHR4cqKE8tiJ3Nb2JRzyEN8c3c1G5j/r9CTcZegJZ4lmiR7dhbTk5CSuxjVjI6bnY7NpebLirqzBrBHbxMpdHreLPNyRT2VSGcTdJavuX3T8l9+d+3//f3/FQrIeeWyD9UnWiuirxcT6uAIpyx93uBfy3rTTz6Im59OjWcFwzumIM4XOYiaESsaiOPHA4ohVL5ZAr/2mio8hi4+Wa+alUMAyZTJ5cn6xC8wLOxYNQZginc+LdTMkzMYXTYeiLSCn1yOgNKK96/EUDyRmQnn+3siWOtjSFkbE9YpOInHXFdolyf+XhDUNQSabjcehj0DRDBmQVRikbdvEqtnsa0hyOUqbLFlN48nuAvs5RGDf5qqQu1CxdK8GXnrLaqOx2PXeBeRGpJ98aglD5EYu+eQcE3OJydHBhvLgvn179ZTP482lY36vBIQ2yCPsWlqiM6KjSbJscpPThQqBDa55Zj7dM7T4yZXhj05fZ3vv9PUPrwxf7J4cm0+roJjLM3IHmyLqQdaNg6Wes9NZx3Ta+vT0ufM3e0fyfoSeMREsZh+cSzLOZ59ff/v0DTbGvzm0iAKLfJMjKmmFViKKVkFur6UzeTlwOJi6o/0WyPzi/Xa2tz++zsYhWPVMxkWZHXKFRtygRBMSfKfdHjRsPu/LeyRIxpCRRtrPRXEKqjJyLGvuMFnOtNeHm2Woq4OJX71z8lxHf/9CeiJppQvFq3qS8GuAmict9ohPJocqOEbjjuvdk6zonY9P/+MbJ3/21jlwY3W3hhbtWTSzsIOuNWFZx5aSAvHMBTfpcZao3O2RIMhYLpbMwpni7TN9kPTKwPRMPHu3cRbZKhbWAnnuYnh4eG5ujpBDH/1Tz4n4LkuZYXARpJKEW8S5pWUzfimdkQchqTaCLheZl12KgXwONSiqyYrtIaC0VymCchkJQ5PP6nWydDrl8QodZ6Znenp6ff4gZ8VouknesqKi4kZlpTBChlgai3HkXmih0NraGgwGZToZXShTMJdMUG0jWSI6l7uGxycmd2xu3tJaV1Hm6R9bnFxMsjI8Oz4TTxkpr05NDx7a2+rG2Dud8WzeTzQtwuxI4W0sxxsnO6ajye1tLUcObAq4HL3Dc6NTszl3mcEF5c/68smDWxvrakLMiJ5aFvmnooGFTVpGnlJOd8xhxQqOsan47z489db7H3965tytzo7J8YlrfWMDE3PdA4Ptt/uv9U9Gqhtrys29KRFlmCKQdbiT5mEiQoOJmfw/vnuuc3jaHwhUVNdu2dqcyDu6BqY+vdr3+5OnrvcO9o5MDs7Eh6bmr/cOtXd0TsUK1XX1oYAYP2c+IU9MoHvkAy4P6WgW81dwfHDm+iefnT3bfgtkRg0MDI+wdQ2M9I7M/u7cDbcrWFMWcuNxiYhcBKjW6GJ6YCreMZYamFy8M5cmlnAHKrPJxWzBqnFER0YnrnZPsM9YgYqIP2a8/GzWMTCyeOrS7d+dujKfccFxKxPfv2dXc7m8BOMlBDZkx2Li8dKWE3JNRB03BhZ+8eb7lztu9/YPTMbSqXR+ZC7eOTDSfftW153JuUUi8EBNxGc4bhXSaY9LHqHL5VN5p2d+MXmuczDn8ctqwSGx+OTuloYIfr0gIpSX22ikmrFsPulxYVxmso6O4cSb731+/sKlc539MOV2d/et7t4rly5evd4xPr3grdqcyFm3JlIpEmFiFoxLfP7R7Y3NVSFJtfH04lkQVGtZb71eX1FRRVSlwdp6K4D857PZ7KZNzR63h5YmxkZfTLYLykuquzQeIIUvBdYcCpPB/MY6uLIZshLLjSkxaAwO3pmYmPR6kFI5DbbgJTgt6e1KEJzFMLW0tBT11pBfVgNvZBRhIYzEEn9+tWdmdrZ1U9PerXVoQ1tT/ceffh4uq0iSIDvhsDOXTcanR/z+ik0NETTMawIsE9D68FRd/VOfXu5IJhP/7sWjbRU+fOlE3IU0Z10hCZPycsPAk4m2NtW3me4FwkLhnGyJRIakLkNmJk9ZOrqGFt/+9Oo/v/vx0NTc4V1bX/r6ide+9sxTRw5Vb97ROTDq8Jc7PMFkMnnjxvXa5q2RiEduJkkQLi/HjQwNDsWc7b1T//z++bcudqcZ0OFazHu2bd2+qTry7qftb3/4CWoW9nsc7qDb7YmmkdWQK1xbCFT2jc/13JmoDPiaiDYsAu6il8g5AyS8M3nHm299fPlqeyyRqKuuPv7yN48fffzxRw5VlFeUhUPJdCFOGF7V0tXd7fEF97ZWyR2XfH42Z/32g89+9fa7N2/3dk0lLH9EwtqC5feFcLozQ92dN9rvDPTd7ulzJBd27t9JCPDeB6evX+v86Mqt7ql0zl+RtTxs5BEHdm1pKPf44XSBaDsfTzlR6FjOwrgOTObf/vDTz06fzntDAY/n29985fWXH33q0Z1H9u4sCwSn5+TKxcDISF9Pd9pT0VAfgWvY3Bz5dDbjdLFAz+RC+tyN23lfOXor99Iy8ccP7m4OQIUMSitpM97d4U5Z1nzecWs48Y/vXPr96XPJeBQyBssiWJY9O3c8cWi/L1zuK+Su9o+1d94eW8iMJQpetx+9dRITpRdtvRWZlX8I6MPqrV6aqq2tC4UCHGIOJdo0GgvIxRssjsRxouB2rvilwEpcDICqIs1O7jfAf7KlfJal3uq8RZxscABrF5G/hBKii/ettwKIOeRAcUR5nQViabm3fuHmQCyZ2lRfu6OtLmg5Qj7HnZHpvqmFQLiaOBlN87sKyUw+4MzguEiuiQUTubzH4yaJQa1/d/pG38jk/raGl57YacmLVtbc7MK5mz1YHc7n8vL4NHH+wda6pvoIHptaEGAj3bIKWCosgzvpcnQOLb73+eXrdyYLnsDzR/Yfe/qJvY2hkN/l97vKyn3Tk3MDc5mcvxbblogu+FyuR7bXk9D6SDxdzuhi9Be/+MXJK7c6BseTzlDSE3HL+xJBr9sKOLPnrvd3d17bVV+xb//+Jx/Z39y2beuW1nBFzcDQGIKbdQbzLs/07Ew6Ordvb6vXklc9HZDdHYg5xKF9cr7v7I3bOL/GltbXXn15b5OvPmRVhqw9myrb6msTmRx+2+ENk/pta6re1FzplycDSZit8dnoQjQRqGqwApVeYjjSwnRcHkawnNX+QlNz845NdSGfb/PWraHKus8/PX1rfC7rCc8kMoWKFstXLgJZyLlzCdVb/K1TrrnmnW5fIidPPPYPL/7Drz+YnpsNh8sxYX/y7ef3tEVCLgcI1Pgcm2or3P5wZ0+v3xdE//uHxvzlDQ01PoTB47KgDErF8seTnvPXu2y9DTozz+1pKCehlytShYKTVBYSObARI9P5X39yuW9yrrWxIVnApWQe3b/rG88/um9n/a6myOGdzTsO7K6I1HaNzIzlgv5gRZLAECaDcCZeorcihqq3awnufYAbd+J0xuT+nty49np8TnnDRm5IGp9bvF5lQu6HnOKBQJ5GdDrF01oOn98vPlEq5Snr2dk5UNVmQPE+0ANBUXXNIHr31VzTB+bixXTLa553SacKx5563JOat5IzHqIytzdZ8DkCkY7RKFlusSVySUvSqkVHZ0+/Lxc/eOjw8gOM5rKkPmCQcXrlnpC5S8z4S03kgjCr9Xjd2Zw8mHele/LnH6JfY3Ecksf95GP7G8vk7W1/Xm4tlLsdbZua6UZymHX6yipqunsH9FIZkEpnAoHgzp07cYZaI0JjFkzjW0PT4xNjh/bvffUH3/3m8f37t9c+vTPy+I7ICwebt7c25tJJLIsLZ1XexOy4L1wtIbcEog42cWhXb/UmXOVIP2SpDsv4UEmi1pyjodz7zWP7Dm7fHB1HPTxyZR6iCHUFnjmy70//5Id//vrXXn1qZzy2KNGHN4QptLLR544+/pPvPffK8WM/fv2bNGOxP/3z7/71//HPv/utr+3ZvVvS4GwG0tnXsSSRADC1ZPXkzE4nyf8H73+YTMVBjP3jh/e1NJd58/LiO1shFgv6rKN7mnZva5NwoLx2YjH+wUcnWU684EiZB0jxpTrqCoincioYJDCwidUoHf7Trz9GaSsrqucyrsW5KWb8+pNbqr2FarfIDIjWuR2H9zc//+hebzauV7mA0ucujFYtw0MqFc42kUhMTU2Ka5LwU54CoR5VQYHZ49/QXOqXH1r4KoF4j7nARNFgXdTwJxqLp9NpvanzRUDv3WgZh1L8eIWBhWJgKG/nVrittsYA/jMek8dWHVkSswwCFMta165eEbE0dynpgR5+9Hl7KhEvr6jZvzUCjdBnZDrgl6dfADrKreC7gWZsBMuUQSlRsMbm00Tac7EkCunzeCtCflEPaJBJyuuaMJtALVjm8vod6eLNMCR1amwRthS/p+F2Pffi1xH9V55/Gn+VjM9pM3c+1RTMf+/5x777yhMtZY5wIRkppL3mOSGmePnJ/SF3gTYqZBlf+cRcFNrjxguF4iME1IwvZjFbs6lcVC5GFxWzkE/6XNl8IlHhs04cPQC50tN3xvu7pY8z63bkg85smc9CIZkoHPC4cml/bhFqYAoxFtRgjCJhb225lxEQeiKdgMsB5R/Z1YbcyzhG6O0LvEBWrn7Im5XzBceZm3LJCqWlnv0j+7aAmddKkof6CknGEvp6LexdKpMl3Q3Xb2UJ567coDrv9IvSGvMdJw8tAb3TbiuYPP9iXj88fe4CAhAMyU2+1Oww6336sS1llgPkCVYj+WSwkCTtKbcc1DdVBJVT8qzr+vCQeqtvz5AvEYKjpRJByhNXLrSFTTWWINk4XbnSYzp9heD2yCLBhNmYTo0Fs0+MT4izNRdvwElvcZXe6Lp/UB1yFm/9yQhIIeDx+iLITs5BXkPkiUjt3bGt0p31W3IvF+nBIzmDNf2Ti9hd0FJtwdle7+iggOmli0M+QWI7GwG9n1TKPFkSJgPaSqwkT4l7Pc4LN7rujIzJa8CpTD46ubmxjkbIo8/nJ4rWbgg6dgR/FZJnRkRSo4WiQJMcJRMJLA7aiL3fUiv3rtReoCFlVbVUgp4znwyRKORS7MuyGVxTOOAs9xZoI4/RmjsWA5NyA8PjyJOg4EQoz89Mx/Pmqa9w3cmLHYmcWCvA7ZQn/pzmmyFtZUIuK7kUjOASc/JYIrrtXpQBUXi9TiuPkRpwpaLsiVAgiDOd9mFNCllVXTmbS0ukY+ILOTZ0UynPWS4sy8RM/lr3nUJQXnNlw51yyksI7CQId6eMe17EJuYcO7c157wRMXn09Ubg17TMLAKdLfGEqyGbypv0VDoO9k8SVTGI+v9MOsV6IWkuk/fBzVwi4Bc8Az43XKO+dfMmTI8uoRSMKi3L7UPqbSqVIvUiBG2/3n7jxo2LFy/e7Oi4evVq1+3bFMbHx0V15QnQh9GQh4BkMgEaBm5eunxZ/nR03Lx5Y2hoUPTWvuGO8pqnpooHDwKlSqX+F5hPW4SmpIVIh9slN5Cg/s7myorKmtm5aXG5hMrmBsZs1o3BFg0xQfvZC9fZ1za2iLEvDi20ynvlngdQeh9In+4AXPI6jgAJictcLZ+alLvoqJDPmcWQn3hkW1DuOaQzGcK5rH6qSgFt1Jso+Fv2UAS1R7p8fvk+DtEalWXhAEJDLMqmTwKhDJgnBJryfEwe9GGZQaMhaHXKPLypLndmZhoiOJ1p2niLTzrIPS0MUCLvxGv94xsnrw4mJufTc6nCdEGe34jn5NoeEelP/+THTz/7dFBSrnQyiWfE6jm9wTKckv0sISBG0GCFBmAqPLm8Ry79pbxIP17acpQ7U9BBHlxZsj5hKyt6m8+YB8tFic/fHo3GYglvbSLYPOsIjeUrfn9t+B/ea/+7X3/8n9/87B8/7vz5J92/eL/973537T//9iwjFNzhuVROnhvHwc4v4iEBgoK75KEEEC78FXEyZZztxc6+BFbUIzfkoT9hEeZA2nmcKbnUEtCHT6QGyDmaQ/IBhuJhKdztbB5Ob4Vv+FvEtKPj1k1UpKOju7vn2rX2q1evoTJDQ0OaairojaWvFAjV29tvXLp0uavrdldXFyiBBnssCxE7eBbbFQpOuVNVMHdSHgDIl42s3g3G4WowhiQlkgmnJc8CNNYGnzuyh0p5m8wAToncBoN9azyWdjkGFx1dnTep37O5nlAQNdD7+PYdvKKCpeP6Uoh8s8acUpLmC/J2AQWswOzMHP4TN/uT75z49z94rqU2iF3wmOiDvdwwNDKA/jAaJj/pDOQ9IaRZ+ucYUK4vUiSjQ0Ur8XSotFPkLOfyupJzeEj0QdrmCxURnyVPXzmZF7RpLMMuJWMK9jMhjB/ye8X7uT3YIBzOrfHE3/7ze/+Pn733s/fPf3C2j7R8IUpsJr6OzPlwazXmg46hoIvwE4KgZhAk6PNp/KmpvoJRRbmCRVluwbg8mUSR1ES29usH6LAWFCAhijTQ3WnVbC96Y28ol46du3ydnL9/ZIINvrRfPAunhns752anUFdrcaTSEQvGx9KzY/nZkVQ+KY9dyAMugpsZ+C6Qh6t8FpaOuclHcLb+YBkMhQiEJ1hYbQaHhOkW3ISZYg1IauR9Q/OdE21DvFYsmH3BaKvu5SKpXu+1feNdT3ogKbb+KSzVFEgXvN5AwM8uEom43T72ZWWVTvN9iaQJ/TVQ5pC/pdtDAzOXdudQBs/no3LF2BkOh+FlVVUVe/Musdfl9MoHGyWqcWmESWPl9/1AcenG1FkEvZYzD0eQXbn460jIg31FYLUEPER3FmGew7G1MdLszeBy9e65CjeZcPvtgemso6tnOGGes92yfRuKgRrAiJzDiSw4TW6D5IlgGd+LbizMz3IK5tlLN0/JOl0e56vffumvfvzSHz2//1BTgKGoRVcXs/m817toCVaEdn3dPXRBbkqv1tDS55IbTS7zxTORADQ2ncCF0ixmXtZRkFMEkPqUj8OdteRDioSRNmhQV2YlUUINIKES+001ETSH4BzvLfMGIolgw6yn+lTv7Htnr/3yg7PoMAp8e0LIm7WZYrl9fndeLr2LcqQWpvHDxVNLsCTH9JJnJ/OW3xNQRygg1wKN0GNSiV+Ebpa8VU9lPCEhkmllIB07trP+f/6zl//yj7/9P/zku/+7//Ufsf1v/+JP/zc/+TbbX7763E9e2PfT1577kxcOfP/rTxIUhBtaiH4L8sxMEQeHy68ZDUC2wl7QLvjFsOYw5fJs0l0Pe3nDwYAYNew7LEeyREcwdpY85KNjisVcisPtZzny8qiWOHltc78SvA5AC+X48h79icWi6Ab4mIc3inahFL6I6q6IveVmpss5PTWdk6c7PMyrL/e7XMyxct4vAHnDeKd9IRHyWbFiNKjvTNpAi8YySVyDTpEe9ZkAMVLHnfH+0UT74PTEYnz3trY9m4TTXsSgUBzKjpOLkE1rZAjoxOZ+l7gaHA6ztlT4q4LusCeFqcYSqNNBB8bm0zi0//rz3/7f/u//z7PXbmqSVgqCsTx1rEcyuCafCnZebSRMNpWYnDycvHwtVZNJW8gAGqjSCm7NZUf3tKZmhzlEe4mW8ZlIsLes1lfZHA82kF6+9en5/8/P/ulXv7+O72V88l65DFvIZ5bej1WwH5NaASDDpgKhWMUtWakGyepvDdocy5Nc0URevTdMwdPqRSwUqaXMoVe52BrKvdtqg2x7N5XBoD11fgkHzNZGOEMHyI/ZLgG1XLGsUHAZbRdGM02QLEXzYrY8TkcGrfRMpuEhHlfuv5jbH7hem7AiNstWWiEPXYrFL6y3KwGlKr4ExyzyRRv8Yabote6GB1Vd2q/owmHefFyS8uzsrDx2YQKYXBa5debkxRPpoPeEvghIZCKv78nbpEtVQuGY04sQLAdjRl7N++0i7iSummupsyVHwu6OL2Y/vXK7u7ePmsOHDqAq3lhMglMsqUnCw9ZdT4/akZLOYckrK1nLkucxMumkO58p8+SDEpEJyCOHBcfNocWPzl7/2T+//bd//7Pbd8a/9dKJZ598nAhN24CPM2OyZXm3Rx7tkhd5SBTz8hGPQC6OrUFibKGRdebkVQF5FEO0Mc9GXWBJhlRpCQriCbnUjFi7JBXJ0subd7zw7P5HtjU6ohO4zYAzrxfG1J9DEJJMQtaot/b9Czf/4b0zE1kHSS/eLGkCHKPGyxfYSy/RKSBs8iGB4lERwB+AbprlKjCUBFuIezpGIM0/mKKYS1pulukrpDmWK1dmpbIVsvVWvsabieQSZY5sIJvxZjPQSu1E6byKm+alQoFCDoohA7FkWoNk02oZZDqf11H8nuPS9Rd6OSRFDxbipcgD1JuYQ6bWeR9Sb+XOz1qAcqE5iYR8Pk5v5KryrAn3o7qqrqs1thSy2dzCwgK6StnlhnhZO6P+4kqrwIRqR23VLZJPdcBACjtiueUrB6IU4nIPbt/sSCxgjItpKsFnIHJrSATliT3G2SLcJGP6EV0DUVRmlXux7xIrYJDcbpfP740l0rFYSl4odRLByvuAv3i//T/+8nenTp+dmJ5+8pGDP/pf/fTIE/ubmxvoZd+VZa/xm3GMZjlkuQV5xljKRui1ALBq8i5FDt0mfFoOOdYCpz4q62JVUih3O156/onjB7dVQpjoRHS8F4kUF+0N4XtJuVEeZ6TJU9VyvX/s1IW+uCTyTr/J9tebJeczN4KZy8p5BBn7XZ21Qa2avhAC+DxuLIh4SHMZfGhMLuzlMvg6fGjWcuU8OFOS55y82yAvJBOVwB3CWXkdzyB19yUiHa1U05B8bArzaiBWcAvCLHkmkVFWSmaEG5BI2Xzm3lj8FbDC9ICCW55sKvKiKIUPAaju0iB3gaoKDhBPKM/6Oq370c/7h9VXuRKJeDIpX8DCx3PIAtlTLlXaNVF9UFDVFTBv0jmSJsoqzb6WRDponPATh/fZLtcfrMAqF/xVhKzY4O07d+OdiGw9HrlIiwP3FNxoezjgJE1S7ULb6XL3l1Dc8riUmCpMNFbA5Q6FEg4nPva//Pbc//Uf328fmCQwi3vKnvv6K9999Xhr7UpaoSd5TwjRl7f8HKSs8p560invvhClZALVpclVCcjXeZyWvIXoLNFqGwiYgwE/dEe89eqo5XK68/mF+XR12PHdF/b/n3/6rZ++/tKLj+3V7z9Zs/1Wcl6umbu9yfgcku0JlMtDGoauToeLGIRlrxbnu6mBNIOSKua9gbHtNETWqJce4osTM3nzzpAbywtVRWQlwNJPiwXkNWaMqXwQ0qu+YT0ofVMaOoB8RXVV8SKTua1gBSrIrsmx43lLrkGadzlwspIdyLxyOJ8X1vs8y9+sku4CxiCyF3G7O0x/CFihD3pIvKrfYQN7TJReOjLnvyhgAmxPz5gaJMdicXnjX2owivLmgE4HGtJuFZIPAfSXmQryVIA8FGaCiNLX6/SyrTCAOXMOnyWat6POicuV9/IcBY2WdV8eDm1tNI8ryyOLUpMrYHyLPOes/DGN6YiepxMJxmcymUTSNOwDGGGavFiOW0OLn13puNw/m/NGks4ARuFHr3/n6KFmO1/Vy54S/ZZcksWSy9VmI81skC+9lixQL4hZefn2sLx4JPlH8VwJxA17abz0Qp8rmsl3dN6+3Dc5HZVHgrw+64mWAAr8f3j9if/5z17+o5eOPrWtpoHgerpf2ru9voh8DxmZFnxw2vKBCZxRPmgeJVu+rmNASGDAvOBuLu+UVK4AVX5EkH1dlbMi5CdrwJuJtyftd/nPXbkxb0J0NnncW55kzsn3teRpSXm4JSZPzIpjNG8aQw2h1HrTCUlz8mwveEP55ZeEYEGwApfbO7oANSC+DoarV9wyS8qo17cU9G6FaSipsJFrVLfksuGXAiZOtjwe9/T0NOErAQN2aylgljkfGuhuj6AFlFT2uTxBciabxMCz6S0f0Cjqt8QE8vnLFdsDgRg5eU1NlFZrWBmk1GjTBrjO3ogtQB4ogMuVp2GycsNe1Qahad28CS9ES4TDtJLHOYQfS2AHSIiXnZqKprHcQpa8FAeJn/F6nDg0lPbmnUk0xxmswUYc3LurriIQzOaDhWyZJZGqXJItxOU5kHRMMVGQb00iNMWjZZBJ7w6VBQgxNMrIL10oKwHy28WCX4wX4abLl7KsmXj2YsftN3/1SxVTRmG6SCFd7bNa/PlXdkb+7IV9P/ja4we3NMo74lnzZn06FU0Y9q2fW60JSAKYidVYCqEBVF0VQOpNDQSHGod2bXWlF/D2epGMzIUgpX80QYhOA5/TywpQUuIgNEVW6nHijRcZhNNkJM60lV8sLH8KYSUId1xMKHNiJg601ubjUyxQuJ9Nk35fu3oFM8FgJCak0EFnyucgS5cHknUEQC+JJ9zFb02Z1RW/yFW8D8T/Lwil3symOCmuvCluLJMd2T6o6q4OsG2NVZ01ZUlu5WIy01hkJUJQk4oUv5L15QLJZNbpSVueeE6uFhZrDSAfJCrwA0uLBUXBCINxubuaKm2XK6ldLv34jkacoXyBMy9vgEk/p59IyQwDP42Sl7jHZCquAaS2oUvRSzscOLTOnv64FQxUt+FA0J8KV7qlTN5WRxqQIekGmzMxK7Wg5kBNODpGtOZ0pZ3IWT6pTmk10IxFqkNQkCdv4W/JPTDNnPXer0oYdPBHvKlgnbey4YOPTvYMLSKn3kJGpDO7WOZOsyfohDgvP7mfmBBrwt7j9ZEmMIjkVuYrEygMCX/CFdQLWoBeXRdSG9DrAvLivh6moo5cEgu1+iIWNleuqOXkemGl8eE6Jqoby1pvf3S6q0c+4kfSQcAMndky5gVp5kLNrndPXuyeTOXlqRLp5Vi+NZwwflK+U5N3Y8rTTn/B5YsXMqCEmWhra8VM6J18lllfXUEmf/Hc9bkUqZEzZ8TJDAOKZC4CBE3mUJZAREDkTD3LzoCSuXELIOv4a1m1rV124f5BnJrZ5O3WvFzgzmSy8/MLjEQtwQwBrW4onry+jLyaDjaATamWahnEAG0p2bJcGhBUSTxsE2A+ZzGPh89mk0TLbrdPM1t5mLF0goeCIkoM4yRAzCQLWAWltTwjTC5Aroj0D03MqF6JiAvawlcXcZd5pPHgocO4XDgXyC5i5uEcaafoCamvh7jAnc57s2Y1GmybZ7DUM8fQtBBRP7OYNIcBybjklo07IOrhcIwP3OYs3JWgOlhBYY7gWalnGhCjXr8kz/3YQEQaTWQIX3/1zicf/PYjfRwPGbFvyZJZaXKlF1EQGvlBBiPKWcuLibFFVgETk8qkrVCVjkCoyV8MU21N7ULaMZvKnbnZP7roiLt8LNY2Olg39EEVlZgCs4I6BQMawOPO5HsaNFCIxxYrLKEhZX3UWR8FYyhR3VxKFNGAz+OWhxyI1J0ezS1tk5RLyyGRzuOH9+Wjk1qJhhOn9M1nf3O+9/Orw4PgabnjlnfBkhxk3ryFf+pC39/+/c/e+NWbEwOjcUMZubywBPhtNRP4Rhy+hNwOp/HbAvu3RjDcqdnh/MIIh3MZF5n8ezcmPu8YmcjKA5XxvNt8/sKP8KgAKPcBDLdGW9SLXIlP0LTF/m7LlwTySrrD7ZPnbQiVp+7cGRwbG5+amhkeHtEN3XO7PZgGVGLpHYCNQGNsgmEtzy8sMibb9MwMw1Lo7+/H2bqcyAlBzJIB+ZLBSbLjdAXc5rItG6kg7OwYixGJpTLZa913rvcuiAfT55rIG7NO/K0+AEQqC+eQS38+gek9tmcTuMJnuTXvxMTKZ1wSBZdx4PJbcnRBTIlUVXnU+lIvou8WL5TCtOfkU08ixcix+eoiPEZ1cewzM9OMM50qxLPudMH/z79+68603CtOxhfhCu5ldm768q3+318bPnv52lghGE/h2/D2S8/3ev1MjWfQuxoMpcBoKO2iw4moyUoTBH3FSybSPiAPNiv4nPK9RdqE/N5wKES+fb6j9/KNPog2m3MvZr1pVyDnLgNDZuzqGXZEx/Gi+pYMNRIgmlRTR5uYi5qH8uVuChu6kY0vihy7HKhQzh0S9+j1qZITAenzUhrna26Jhim18ehB3L7laG5u2N/WQPqAWSQCIl4IlDf1Tc698cmF//zPH/7q9/J5kE+uDFP4xzdO4oo/PXu+4I/s2rEtEa5nQPCHYuAAboKisVxawKawBJEQHGM+yVrKLfnCc31FmFRFFRK7yYre+Oz63/3u2vnbC6NxuYHH1j8Z/8X77b/55IIOBX1hKOxgvRhE2ESdOgZ8lutvVr1/S0pKQffr6QBJInsYLr6oZENzSMjxRrFYbGZG9Kq3t7evr29gYIDC6OhoVVVVwC8uUV6zX2U17NnEIaOExPKmjXmT1oX765CnoK91dd1iVPaDg4Ozs3PZbNa8o441kG+vLCe364Pij0Ka/d0gZ1a+fwu2kAzhIOQbmVi4fHviswvX5o3hb2tpnpye6eu+1Ts4HZ9fRIR9Pm/Ii1uWuIVoyeeT51p7BwasTLzM53vp+cfKfI5CJu9xFVKFbCbr6p9JnG3vuto7ceFa58lrfQhiwV+O0oo+mB8umY2nBofHO/sn5qbmou6ytMsTxke6rGTBMZv0DA4Nut1ewi5i7kC4enZ+YXxyNp3OXeoafPPdj7v6B19+7QeP7d/a3Tc8Oh+LBAOWr3Jqenp0sK+iovr5px5pqQtNz6cnF1M3hhPXOm7H8h6d1Mqlksmkz43tcfvNivD2Hpe1sJDOuF2zi/nB4ZGx6Tl/IJiRD8ZFQx5Hc1kwlsoF80mvz4eljcZT3X1DmVzW4w9d7+qenY2FqhpDPivvdkUlIrWu9Cx89OlZukdcuS1N1S8cO1zhc7jyyXwhm5pd6J3OXu4c6bgzMZcsRLP4GK/lK0/lcosLCyMLlscfJtIhXI8WnJMxR+9EYmgycWd4rHs6kfeEoR6mx5VeLPN7wd9anM5ks8FAEKxw1A0VnqryqtvdvciJeSZUXnX2ltU5gjXZgtV+Z7x9YKKn/07/+Oz0YjKfXPT7gq8cf/qZZw5Gwh6H053JO2cX06NT8b7+O2MLSUbIMoXcp7HCwUDIH/ZgQFO5SEDMBi6npjacd/gmZ+bmF2NRhy/pCnoCFfBufHLqavfQ1eu3rl25cubyzfPnLw7PLBTCDZgnK5+GBbl8vpCK3entvHT6zKlzl1MOb6v50SkyQHk44ey5c319/aFQRBNCpF/0yuzX11tpaTL5VWC0nQZG7d1aQEf1VYTjx4/X1tbgaYl1dW93AuzZ1GKwZgmtMa65jL519MEHH5hP6vgIxRnN7Xbr47VMJBfvDdzP6z5r4K8Y8Feud8mTG8eOHaupqQENTqRzYuCxiP/xv/wMByDN/JF8oJJCxrz74svJj8SlZ8co/w/ff/nQgV0UJE72SCKEtv/jf5XfpNqxuf71175VQSpoLk3jbHGbH7/z3hsXuzjLmClXMOMrp2yDPkhA2sZE+g26XfUVP379m5GwF+LNzKf/4b0zd9AgqzwYimhYRRDoj42Ji6ivePrZp3duk1deMO0nPz+vPx7HYSYx/+Jje194dr8VS//sn9+eT6bH5uM6O0mypJFLM1a6c08fe/Lbx/bTq2cy/uZv35uYllvQs+aRh3y4kT2NnYnZhvJgKrb4jUOtzzzztNMb6JmJ/+rjC+BmHqx3668K7t7WVh4KegOhySnJzKEYOTCV333lCbLBoCHL4kLiww8+PNU3C6mTIbn5XMTK6yeg8KTmbWp/66UTdXX1//DW71MJoQz4sy+rqMGhEdqAEsjX5+QO7Z49e1988eueQCCRycNdSNcxkT935cbpqx3OsNAn7Q5WmLwXkEg7nyhkEs5MDNyO7N6ys6XG65MYAIK//e57t++M+0JlSjH5MTdDUiUCM1LcFkj96Mc/Kq+qcublNhtBysBk/t2z17sHRgkZJK0wEQrBA8xSItdVV+9/+kRdRfg//fpjpiZbjucdVQHPFreEYOUh95E9O3Yc2IUP92bNQ0Vfot7K1SDUpiC/VoRGif6yHPNTYKgZueiTTz7eurlVWhqFLFVUoHQ2avC6ktaK2SG6lpcE3//gQ/SfwdmTQgfJh6SlGAjtJbB6rFXwoHpLa7ax+fQ7H5+mjTdSU1lZGUum7SccSXGJG33xiflY9sRje/bs3iHv/Zjh6ThpmE28+s0XXzjcWh3wOHPpFOkCjoqk8Wr7rZHu27n6NkTQviJqP7OuL3nOTc8wXXR8IG/eYvuTbzzv1Y/luwSrCze6bg3PTEwveAOBuViyrbYsUl55aGvD5tryqnK47HDn5Xef24cW228PdHXerG9pa9vUfHRPU9BnIYgn3/x51Gfe4KuVDzjHMoWQvv2NNC9Mzc7MHdtW9eTTz3E4FhUKUFNZVVFTW5N0Egd7QUxaJmJ248cff9znC8RyeVxi1+DU1d6xhflZsuV0IjE/N2UlF7ApSCqi39ZUt3ffvv1balEZF3g6Ei6XZ3ExcebM2e7ZpNMXhtQMjp4LNmYW2S9MQYf85J3HHnussbHxd5d7tFnEh5Ist+RwanIquDCUSMS3bNn61PETQnnO8Z8wOyeftu6YSF652j4yvbgwN22/So0qEtlS+M7TB/XHr73iPORHocfHx984eR4uQwEa6LxSMBj68zF9Qwv0fvj1Y+HyKo/8oqZ8dxbzTSQ/MZOXd9XGZBXy3oKzUFcdaayp2t5Sz0SRci8m/v/1H/+2vr5h5+69W7ZvQwzCAWe1eRsBcy+I5+RlyS9Zb5e+BSkrZJ3UUGCva04kEjt37ti7Z4/5wIpTvajpt5He4pbpy6nFxcUPPviQU+gtVkDHRGkp6xdkpf+S+lEuGpG14IH0FiCjIL7CpCNe9Fy+KIF8LE0IkDulU4VqY5gV/5iZH52hV4qY2bw16i6k49FUMOiWz7qZ3FK1X/NFkuSgviFg6lWWmJExpdK8CYjeE55lMuksNjEQQAQ5ByYkQuRXoEFnXErRqOQcXvm0cPFajrRkEI951878PC8uSA+ZWpZg9oAurcxc+vaZn1zIe71kYmg79YohiGl7NZxqIxiTkot4OCMvIWB4EFm9tDY9Pqm/POBMx1pqw+LE9CeCswgDMkNwng8EQvYPzJuVFfeazwsYSrK6nPwWuTvukC8qa+gGApySDLMgmAPSjEOzRg5pVTDfVLC8XnkX2gREcG16ITE9H1O7ibbUVTmhJIyQBzzlu0AIm3zjBWHLu5yLZjplii0MQq603KnG4sImFsVZJAeFiBUkDiLzRycI3JQgjK/8kl5u+bEFABM/fqevfvMWdFjHhLYSRVA2xKSVTz/D/eX6W4/HSskj4gJ5ES8PCkYKqk6yurrq2Wee0Z/NLgXVmhV6C1Cj4TRed3Rs7PTp09AOKDUH4m/BRDtrN+BL1VuRenk+WQBmwxJ9to7YXMR9iYswTM2hfGFITnsKqAF23bg7qjjrKsi7oOZ2qFuuUSfzvlCAZBLByHjlIqSyx2d++APnI9cjyB+NRMobf0ZWJIMQRZKvODjMCz2AhOVLNgL6gmEumzZm1JnLZ/V93VQ+jfToEjhEjBQ3nZQapYiLGeQCsoCqh9dNsu3JWt60uSiAXDpdDOVNEDnRviBPcEUzPg0mtUZ+aoiCy0diVyiX4B8pRBNUT8rwHgYNq5BOmgvXoZCPxbokBlleC0NpbIJliC39ThpgKJlzO/OJVFajX04JwmZM0UuDOVQza/dhNIpL09+eX/r0ucNdFo/F3H7xlmgUIwh6S5ZIac448qPV5h4ndDA3qFFnOSuBjBuLiGmUbz7KFAWIbu7hFLJi3wpybyWPdShkQ5bf+JssJiBhpdVkA7JGQ1IZCvohQuYxDwn9sR0md5DxzKQ0dheWHtH4sgAFzWSwTWnRWPGpAuCqGSmQTEqWS0v9LMZ6sPoUq9Uf/GYo9uiqUVdH8Rs0tsbbhS8XoGM6LYJCQmfsaCadlIfsEM1kUn/bQhhKy0xSrkeZx4ayuYxigyRxTBuPVJPxZ2FPLJZCLf1Bj/BfbidYwmxpjJVIOnMpucNBy1SWPafEHmOt0yn5nqD4s5y8o4rSyvf+5fFaOpahm+lUQJ5rJ7vIMKZb3EQepUVjsQ4hJ/TKlnmE8Sgt8qG4sRe5wSIkEj7zhAlBgUFWZoTkev+GQbxyASHJXCGR7LzT/L6WN5fgkEBOTYiKI8AScKEV5fIT7LJl817Wkk/SEsXGXMnDSflcKOgSpTXmXnwgHT1OlJa1IKM+j3zlsZBPhVxyvwU82cBcfqDAygX8mGf5yg+aTNZUhlHM5igwvpVLyOPqio8Oy4b9Kvm9Akd2kal9VgZ7iq2U9++JSPXtkEwepc3mk0lo4vXg2OWXzMz9dqUMfJePnNDYU8AHBg3R0E/WKHeqLWcqCaPlYi2V2DiVfJhiFWJCvVyCGcUAMSDIa+iZTskD0mT0rjwcF08rv32aYzSorfMCeKRi6YEAs7ja2QLyxBLGwOVly2Ql7WSPS8xmSFDdGB5CZY8Xq4MZlt/gtjcDuYJ8w042y5LbvHmcgYwmT9a5vd7p6Rk0X32smCViTLlRbExUKaC6RnvXc7b3D2oEDIZOYgRLfmfRmctinXKEqawCf+fzy/cGjdo4PYRdpp6V4ocQLPp6neL6IBbN9BlB86MeVjBofnMnJ895uVwBB6F+Nodc4jWCTr/lCklia3TZg/YVZTGH/CA+jO92yUe8UD9XroD00NclnhBx1zAKaSn+mI1c9s8WEG+dVzdHDh8gd9GFkWYLQ04yNH+xjdMhSV0A/OVziPKbdJbTH7BkNQGn3wlN5Bu/lpQJs9wBuAARQBc82cCZ6eQHAAvyE3zIpJzFn2CAcI26KPGeLvmulizTiW5QiXJCRr/8lhZmjoSIBeAe5beChXR5Bw3YKBs1oCN8ESKzyWrRB+JtYjRH3oTDZFqi/2gFmuxBnGCBWQubNJe9EymFqvJ9SxM+oNg0BlvwZIF+fwC+C00gIIG13HvOo+HCVmEx1PcU5IkIZnKFnF44IrwwmYIg4PJANw4ZGJBfJzXMJRKx8swq48ieURAC80YGUzGaEF/oL5Q0TDGhjRlE1v9Vg8Q+5glEn4+ULBuLLRYTrSUVW9qWQU655EMAEuWxBnQ6i8NLIXqcYsClq8e6iq8cMBbsRYHlIraLTcRBpEc2eTESrmTFxUmUjVB6veSe8JKOxdVRa54EgYuia/JQLQuF78iQ5PCcl/dIchKQI3apVNrn9xN9smcOmkEB2jBCSj7pjkrgCJOIDAIn+Mh9Ms4LMGCxJLTS3uCKiLuYsbjJEzfLzRSMWVHZuIuwIpQS8+PkCGulmekrzfQQ5WRzIVhLVkCHMiZJPhUorbF9edmo1F4GBBklI5v+L1KMzSwE61Oc3a5fOiuILa1CarTEoPIJEXcgILc2ETksAkqPOrFmYphUVn7aVzRUfnUFDSlAT1IPOhLUkVQQ7OtWgidgUwZlZaX6bIFdL6BrkVTLjMbacVRIbiqZgk26HOUdKi1pqOGCwdwUdDPUMuMp6PhSI9pg2EEYUzh//oHz2wcFuV4F8yxnLLbw7LPPNDY2FfVumSg2GA0xLCBFlbLJLROx2NmzZ80zWPIYXTF3VU/94EjeZ35bWytXCznWGbAc7IlO5xf4N085EJBAJhSSPVrLHkCNQqGQaBfugtDa3MFiBBtZCho1UEZclJ3R6CIhSTGJyGaTiaQ/4M+kM9Qjea2bW7w+4x9MF1L9oaGhWCxaXV3d1NRcWVnOLPZ9NZ2oFBT/FWCv6/5h9cj3BHsKuy81peOsh8MXaUMqAR/dHgI9eXgOqzW/EJ0YnxgaGiTfjcflK59er/wibDgcgobNm+TnFCtMEq4AkeFgoOQzGqWwHh3sG5YgAzvGx8dv3epMpzO7du3csmUL8oPS0swuPBzI+H8wvWWPn0omo3v37tm7b5/GsEZiV0BR+gFjTfmDY3LOz8+fP3c+GpUvaSzr7cPCg+qtXLOWBjhb4cq1a1dv3LiZSiXD4bJMJqMpdypFHuiD04jCI488UlZWJoko5qrkjpdcTzJXyLGxWqASBYbBFy5cYNKsXCAgfRW6YKrNsMlIpPxrX/8aUkV30IjGomc+PzMzM6MIVFdXPXrkiJ41IcAaYCOgIEsx8CWxd12w57VnBKj5shBYfxyRjVQ6g6ddXIzduXNncHBocnJSvL+5Q8lZmIVBzJjf8vN45Gmqo0ePVlZWqj3dGEqXUwqYY0wnPGQe0uLLly8PD48kk8nm5qanjx2DreJszdkvuPC12fylg0gvzqSQ97j90+auPaqgKrJqk8gejZVXl4k82cynFfA/RaU18MVz1wcF48pkdiheU1O7aVPztm3b9KqYSQF84bDcQojFYiMjYwgKZdanioTaszRAXWvBfPNKC0gJZ6enp6LRGPJEzMZoaC97JAkr0Nraun37tkCAmDlDL7rMzszMzc0zXX19A/NOTEzForGNldbe/0uBLaalhS8ouwprjqMOyOf1JRKpW7e6urpuj46OBIPBtrbWxx478vIrL584cWL37l2YPJJXfW4Ps2s+tS1KC6nVpK4HOunqTeMdAC4y3MzMLAwqLy8nTpyantFoHKmQBuZrLdr4IWBtTn/poOmoRMtOC/pQRpNlW7Vy3aSB+GcXmzzbIs4njjRLvZ7+w4KqBOimzLt7mM9nnn7mueeee+aZZ3bu3Ik1gevUq6ZhvEdGRuG99DRg9E2YpLhLfI9eCjVESmLxWGfnLaSH7rC5oqKCEI5I+9ChQy+99CISRgERhN80hvfepRs/jIGqa5Skb1yshqK9WKKqgh6W1vwBoHTGB52aVay5rTcOZpIolw2VBSAUNg5+EUO1bm4tj5RVV1Vu37H90SOPEr7CPgBKTk1Noq4MC6uxjzrUA4FqO0EZAkNYji3GAzMyoktewylx716vXvUAydK1lG73hD+Q3pYCSMdjsbR523kFuvYmea9u0kGcK2tHYImQ7eBW8nez6eFXCprZwgyXiZMVqKypqcFm6yG+l6UhIsDc3ByBmdwAMIyESUXHa/qayzTCNo2pcM5kXCg8yo+7XlhYwPAHg4GtW7cGgyFmYRCUXDMi2pP31tXVEH3Mzs5CloaG+lA4hBW0x18NRap+YdBxHmBbCjQUtPIhAAKuua0HEJZsampyure3D6aglq2tm2EWpzBw0ApiYgqrq6oIZzQb0rBZ/S17PIYZ6cFA/S3MwmQMDAxgwRkIzoLAyMjI5OQUBW0J6FxrbveEP5DeinMhOJT3+MTTTE/PROdnZueII9bdpqam2JPWTnM0N4M0Y7T+MFq6GlRnAKW1cZWSq1CDAKiuUgZDtNdIQGZ0dBT5INA3/QTohfGloJdYtTKRSA4M3CHFgqPwGDdLwQziJ30VLkqG4ULakHh0mL5I2969+6qqqsxFqQbKkXCZPeAKPZE9pDcAAg+tOQ8HoA3opLKhxlDqC8SH9wli6RwF4z+zhKnsGxobl4wIhJYrDmqLSWhbWlr0UkLa3BunnrN32ZsHAaZGNgi0x8bG0Fh1tkRSeP+JiXFkxjBxIyN7P7DGdSlEBQo/5HUpG5c1O0r2RrCcFt6JZ5LpihNtCLQR0+X0ovnFqi8GK69LlZAQDNe7nrwm3LkzePr055FIGT42EoGG8n1m9vF44tlnn6mvr1cbjEAQPmkXWKuVsHBoePizz041NDTgbOExHCWswOsy9ZEjR/C3IujGMyPuCJyOQ1kr9ZA9+oyG0N1GVdfEoY2/On8dKpfL2g5cEaMZI3BW27PPZtJuj9wdpQwatAEBcNZKexUUdF4bQ/tqHEA9XQg9FE/2Ov5S4+KMOrVW2oemUOwCApxiQNrIsMZxMc6awEToySefnERnICzC9q1vfcPv8+nv0RiSgrNcHGLkdCp59ty5np4efO9zzz1PNgRxdI33A4xgg3ZhdnkIueOW/fw8yR0rQo2fOvpURXk5BLE9wQOBPdeXowb3D3JRXn5mDhp6WUYwUOb3BSlsALQxX1cP4neKo/yrAZUhTDSur7a2FsXTC0tGhZy4XNIkmKSNAaU7Uq6FbC43MSEvcCMxeGmpEq7LRSn2GlOpsAKIGnukjb0Id14e/zBnipUIOnJDpc4oQmk8DHskiU3bsKeWOFC7oxWsgi6qKsWORrWQLfbmVPHLB9RTaQa0lRa9ctGAUzKZGVBbovZsKqNUEjqy55AxAXTDNBZdpYYC1kpH0ENGNoWisVM60Jg2kh5tGE8iZdCQASkjQvCCZESXo6BmTpfm8/th36ZNLZFI8T6QtrTJuwEYfAVsfEAbk0GWhKcxJoNUtjgr+JD0Uni4INwGxvsD6W1pfIvqortut59NQkCCQlMu3ZwuOF0s00AeqRFp+8rjq4cDWBUOh9A9ZKVYxZKdTiKlhWhUD5E/eKzSwB52IoLz8wtDQ0MEcs3NTXpVE2AQ+qbNxxmQe3qJkArIq1HIGdOJTIsHwwKIWJsBJcCjAZWInfRdek7DKIP8SCKnKKI/DCh6aFl0kXFdEvPrWe2lMs1olDnFLJSpAR9tT2MKNEaPKDA4E1HJ6mhpd9StOItDsn0OzSxyW5VKAAUWBOU2eKgUB9VYWrLJaaO07GnDXFRqr42BhQDQk2SEyIguGB09xfIVB4bdvmP74cOHt2zdwuCakhgotlwPbASK/DGAySANnJ2dxV7U19cpN8GBUJmQeWhoEGpAQF3LQ4DO9QfS25Ug8bLgLRxeayOYloK2UQeGF1rjg2T/8qB+A/eI7uEzVXVhEtzC3Pb19qmkAksSI3sUjo4DAwO0aW3dHPAHUGRzFtoU6M6AgF5wVi8ksaikvkiz7NGlufn5mzdvdHXdJj2jhomWkBGtowYR7Ovru3HjOml2KplcXFxkNJ/Xl5FnfCVWpAvtleoUOKsqKrqBcBm3ySH1Rd32eFF7bcwsNGY01StVTgajJZtpgH3JjY2NX73WDqo6F0Bcyh70OMsm9DEOnEq6MJeeMofyIo4qqoKODFYbX++FUKwJdkA0yAgv8HU3b968ffv2QnSRQRjQ0EoeQqQxqyAxqa4qXmJEzZiCU3q4GgSBtayGot3f38ceU75nz169WmlOOogxZ2fnRsfGKStP1xxkA1imQ/HvVwyrXSWaCdOKB6vANsalHWFosfSvBjR2BTCluIuWlk1IidTn80gMhcHBQSQGcqusA1gs9tTEEglcALK1qaVFuaigFtrYLdEfauyQGC/InpFRkpGRkZs3bly9eg0TrsaCDhqA0UuVpLe3/9IlufUfjcaIBsvKykAGReq+3d3R2XHrVhdaPWneF0WUGRMslgS66OSROcSaeprdMD+8ePHCxfMXLly9evXWrc6Z2Rmdjr7KMrHHGAkTU6B++jwJs2h8OL8wT8fOW0AnRoSOzCKTmo8V0wBVYSgw7O3txR7Jx1L6Byig9qg0SNJYScECDYJrA6ewHng8jCAUhp5erxfrBjWuXb3KgNMzM3mHXCAw65W3B5mXkZkFHJR3G4y/Akq455idnxs3i21oaMQWVFZWQHNlqO1yDadkCqC07/2D66//+m9GRu7+To3cOgVpaGTY+HCwoqNiR+XStrxbayv+tcGENLYyf3EggzN/1zJbckYeHW5padEHGIFSXEohl80tLC729/dv2tTc0NCAMA0PD6vqIigUkskk2kL6hEAQqIr2WnLND3oM3hns6elua2vb2tbG2kZHRxAs5kWMMNL03bJlC8InlDMeGBxiMbmJvRiN3rhxE189MzNL45qams0tLTRD08kpaIYIcgqrj3uZm5sFNxqACbHi9evXu7q6xsYmpqenUcWRkVFYT4geJkb1eDUsN94+R2KK4rHuWCza2dl5+fIVUnEiQKQCQSS8RyfHRsei0SiOvixSposCC7kMYWJdVXUasBzWAmKXLl0aGBhEY5Fs9g319R4veaZcjYNiqOvly5cuXrwEhsyFUSPRIOCcZKDJKQYPBoPEAGaZ0h4k0QdhwyqAzn6/PxpdHB0d18F1jw+cm5vHkI2OjEABeZTAsoL+AMEKY8IFAjsdXGE9vlO/5ikoeburCyqx5CNHHkUGUFeITLogFtCyOEyn0w1NjQF5+HwZ//UmWg/+UHqrUFpJWfR2HXjYae8Tviy9hc1j4+PoamNjY319g9/nQ6bn5+fpDksAaEgkXF1TTYxkJFs+3MU/RAqZJlTdu3dvZWUVlXCBjrYZhs1bt27V20JQCjVAFS9fvnzzZsfVq1cw2+ghjZkoGAw0NzUxOPqGA+wzX/Tq7u6Wq2KFQiAQ9Pt9lVVVJ09+ipdjRuxIJCKPZyJDiBeTooRIMJ7BHwjQQGaU96jFUoyMjhJe4qBof/Dggccff2z//n1b2trMzZUMSiUfr86k/f4Abg2193hkPzs3d2fwDtjSQJcAMUEJAwe2ZnwL/cHSgRvWAR3GprS3t6OhTU2NR48effLJx3fv2UW7hYVFSDE5OQFhEdGKynKSa5Ec/GRWPi+u5FoBhCrMwnKmpqawhjTjEGqwp4w6UQABFj45MYkzB3kYTz0izymWIBPIBbCNBNGWCsmAxGgVoMm19utoJknTtm3byCmwaDPT04mEpAYgoBYZbJAWxUdHWE/A1oO1zdWXD+D1oKh9lUDyctfDyQ8L+ChY4lt64p9ADgVWZqCuFOCTXKWYmdEIkAZseAM8J/FSdXVVfUOdEMakA6S4SBWARgHUGEMqwET8w4eUl0dqa+VqB6JmpE2AiRBxhiWdJhTs75e0mXp0j9EQ/VOfnUI/H3300ePHjz/+xONPHzt2+PChiooKZKi2tpZmSLDxpRKT80+vPxFMEtaiUeFwmCQcQOdZBWFF6+bWTZtaGAGcmY7wGe0lFE9nUp+fOfPOO+90dt4CycpK+QQXisf4pOLmocLi6mhv1iqX64jYUVoUrKGh/vHHHye8pEzO2dTUrDrGktHbqalJUUiXfClFVrf+rRQCYEaoKC/H9nHIMsGBybA+CiBAFI1NkWsQff2fffYZZoVomTa0RwvNMPcF9oUuYG5+YWZmhvFbW+WLPwCrINrCLEJkDtkzNSSFwjrXw4HKmAAOHJmiUED+7Ju3xopog/sC1U+RxPsC5lpz+6pB389a3pb8u6C+ysRusBoECIEAKCPu7Gtqq5uamrLZLGJBPfTF05LgkdqpnEHORDLR1XULsdm+fQcpsvQt5JAhvAJM5ZDu7JMJ/dVyOYtwRMJlTzzxOL7oscceg/0MTjPlH72QZlz8nj27n3nmaQLjeDyuWk0zXCLm48SJE/v27a8oj0TCYRSMVHz79m10J9QCVcQX/8xyQNK+uHXp4iW0RW9K79y5KxwuAzsa4EaYa/u2bUgk6ldVVYXDvHatXXB1OKjEkTKyDksNZSiA1di5cwdl0F5YmMdeRMrLQI9gvr39GhjSknDaI6/cOXDgGAga4J8RehCAPnhjnC0E3OCKkQ1E7+y3tLUeOnQITPCBIKPkVYVBu3CqUCkQEGsC/mfPnYvH5Ulvr8cHPWWUdUB5DSjrxRYXCplchuSFQyhWUVEOnowDGbE+LIrpmAUEsESUh4aHVXhUXWhmxlsubAx/KH/7v2iQ3NXtVk57XJ66uloNhwBq4Ja6XBgJV2ASERoiCGsj5RFUyMiS9LUvKSuoTCigJ2zIK+LoD5ivB64FAX9gU3Pzvv370RzCMEZgdrwiTqy+HqzketXStSsPiTHDgSpiREF+tdg8LInlog3hNgqPkHGKELqyqgLMxZqbNbIWJJUol5xTrIZ5jg//jHvZt2/f8eefQ0XpjrKBBiEJjhrXh+F45JFHOIUOP3X0KRoPDt4hQKiuludbyB1AicnRWGSaEIPohHq9Q8YswMaXkVcAfGG9qO7+/fvr6+tYqZqA4ukloAYKsBD40tHRiSVipRocFVusAnihBchLGTUm3mGZZEyQa8uWNkIwVUhoHikLbzLvCcIOtR2QCyspBs1clmOjoAPahY1hXczuAh37v8NaoN5JAbWE2egMUgKH1IewJxcdHByEbXpleGCgH4eDx4uUyY3+XHZlyARfi6UiFA9VDlA1lEFrVgOSij2ggIdhr8qPiUAfkGPth1BS9ppHX9QJgGQ0GluSVOdCdJG4kSwaKeRsMpka6L+j15DJdTs6O9rbpYw3piMNGIe9vpaEN2aIiopK7AwN8Gn4zMbGhlBQbs+S+O3ds+fA/gMkxKAhyGTSqA1KTkJIG8VB7kyZYJjxoQbrZXwBE8HeU7ZZJnNRgNoQBFt28NChY8eOEWKwomg0yowMy96OCDQ66O7umTL5wsahMgioy1VMkAGsGKaZMuNgW7Fid+4MDg+PjI2Nz84VL1vg25mIMiAX+WNRMcdmBApMyraBsSiFezViVHv777AK9KYOvmvpUJQEzpHe4Dzl2oYx5+R14+MTU1NTyBB7EsJIpLx50yabqFpQbwao3oqeG+FR3ork3u2QVwAN4LrYjqVx7QQ4iZk3z9NzhjagAc6MhitTxVYh1sYAlQQL4XCIPdKM+pmrSgPt7TfQXvY3btxkj/BhHcgkaYCS0wn5w4syvokGRS3V7QcCQcWNGs5SJtDAq+N2cHS7d+968cUXjx17Wu+r4WkZBAzRXo1ZAKO18jt9eggocdYEXRQjoCHaDFtG2nz48OEXXjh+9OhTRBBq11Bj9jRjrziTikNJEMC0Sc8Noai98pabmGbGxPxBok8++eTs2bOffvrZxx9/8umnn2IOoBJrgc6KG9TWe2MPB3fprblZKoP+/y/IdxgfgAJqlZXrAPxTKSHLJQyOm0+PwiH0BzWWpyySSXPxNtna2oqzpbEoj/n6nw0IuuGuPGuiNbZVxqjr+EUhXgtoilbYsSWygmgyIEJMPZW2uNNSvSVni/slu4DnBHlqmI/uRLaI+ze/8cqPfvTDH/zgBz/8ox98//XvvfKNl0987QTbs88+e/z48WPHjlZWVaUz8mg+4yCdkEW1Qm63GFBCcRYFFvvich44cOCVV17euWNHXa2E8RoUZM1vWBJ1jwyPLCwsMohaQIWS4rrAUDhzNjQQTJSABALU49LJol944QQ4436hACRSxwt6WNjZ2Vm9na5GeT1QD4mhYWQWCrkwzXCtrq4GdpPk63V7Ng4RBvJ5pmAhhrnyrDKmMJk0H6c1YK/L5sIGcC9/+9/hXqAOQa+CwD+oD92x1nolUyUV6cd3ETsNyA8mjRFDasJDY2GjYQICJH+MssFd2/faYLRx+cmhu6DkWppcxFqSNzXtukdPVCAQZY2TpYWZjmFVb7VGjQROlKUBJqqUy2xygxd9Mz+ziLfE7lRWVFVXVdXX11dWVhKLVpSXq59kUfINiaVv36sRAcAE3FgI+sMGDgg3ObB6YA7pSPeFaPTS5csfffjRlStXiZ8V/yJ6JStdmxRLgDPXYW2igRsLV31DYUg7ybfJ/FFjzkIBgIIylJXSXbqtBUphCXDMFQEwnJgYJxsiI3j0yJHnnn/u2WefIYF/8qknn3/+OQ6ffvrY3r3FZ6eU2tBzbm5ucnKKiXQo9huvqBTu0luX+SyM5cCEMFZOCiuuu/4vZVsLZMnF4n2D01H8DMAKQENI5Kqrq+ATAqdWlnpcLnHU1q1bKivK0UzYD6tKHSfNVHoAtEULylTgfviK7VBBR0oQETUHOiZIsDeizClxwhyWAg3AnAaUxT87nSgV7iIej4EqvpQhkWbQEMElOgMzs6OevrY5oAFTI8c6BV5IY2aakbUWzUeh+EUuBFd9YyKR7O/v++ijj9789a+7urog4AsnXti5cyeKVAwzDNjFkrqVABqsgjEXFxcxNLSkzLyqiqCnWSiEqawsP3BwP+EPqCrdgHte/WLJDCJMNcF/IhEfGRllyfjVUCCA/QqHyyKyhSGgHnIqHA4TLdNdmQJhh4YGCb7MkAJKGWXTxnDvFv8dNgBCUpgN6N0LCoiISHAmHQiEmpoaSXgQbfgkNyR9PnwXzqepqRl/Bc/psoHwAaqoGh7DVPZSWFLj1SAuy1y7MmUJ/yhoe6TKVBYDVJppPbLH6Ow5pFKDc/akncR1AMjPzc3PL8zjr+ghaGhHcy8aGQMtdRpoBdquSignl54NVuIU1dV8u0fbaw4JKqTfo2PjZ86QEp4nZ25sbDpx4sQTTzwZDgXtj3gCWmB2pcMGABqk3JcuXzl16vTE5BTrwlQxL6foboiQYY14S1qC6vYd28GWAEGnwBZBQ0X4nkDUTzA/OTnJirdtYxxZLMaC6UyEIg9pc4gCYx00cQCQBwhLaM1pDjUy170yfWOQmEE5ilTZxkBTMiMta2/w+IFgRfd7bsVuXxnk8mlmYbFmuqzTJR95xiuyds4qcVFC9oDNP5qK1Jo9hyKgxo0Ayi2YDYegO+xHAJo3bYKM1ENPDDw6QJnIEBtPQSJh+eQdBXFBmH9m1Nl1aqw4e7gu3szwUj68agYHW5UwfLLP5+cArLAXpSkZDfSTV4zJnjEBo9JyqEtAr5iOBlpAyKhkKP1OJchjd5hrXtR2gfWCMPWieJmiaxWC5OVKGIipT5N3IZaiWejJqhUB0GMtnNVTsnB5gcETi8fQ2GvXrmHUqN+2bStBZk1NDfOyOsA0Ft9GgZWqO8Jv6wiCgOHiaiDGnpmZGRjoR7ATS5kk3SEC82Jl6Kk4BAJ+/CGoQgRiXZpRr3oOGN2TS/HgrzPq9TPNTkFpaGgI2jIC2ayOaXpJ6it2QUBuO1fXVLMKhIFZqDJtshPjExDT8EVMgHYUUpt57z4sCiRAbIBI+ZE/hkCkGA7sGV2FrORq8l0bDR4IVnS/51bs9pWB+b1ceVg0m02xcIhOmXqMNHtCWSirDzkq/yA6tINPYAZ+Chp0qWQrG2isCswphsJd7N69e25OPg6m/pb9rl07seyMxkA6MqxlWJwSosTsylcGQVtkdvMglHIOL81eDPhSyqepo8iSAdUKZTArQhNYnT5aSAWVBiSAFIsjbwK643F5DBDc6K7SjNX0B/yRSAQZQCTogO+9detWLCHLx03JBR6PvNzHOIgpXahnIUoQI4LF13E4ZM8soKFhNpsRR1E2ekEQvVEMsGpEf8eOnRLfm9+XYu3m0Q75RBEjLCzMMyzj2CwAGET2d/uoVDLJ8n04dJ8PnzYzI49bQhYoybCqh5RBhu6sIp0S+acxxCddpx+sIfk8efIkcfvFi5fHxychC+3lsrzk58IgbDGrmF9YHBkZAWE4C7l0TEghjZcYp/a9PFK2ffs2WjIXh2rO9FENmoGe+cS8ABn+aYHPL1y4OD4uX8ng7LLkQV/+V1RUwiQoyEDgbRYs76MxASbG3qixobT+3+6GvrBSk+pIgMS6VEzRgrq6Oq9Pvr4Hk6iBYtAOdlIQZoiZM5rjciL3ojFGkagXqTCvhuIG4e6WtlYCZiiJzNGA6RgZ+4p6w11aqs2mO9Kp3AUNBB2U2DM+Gy1FxVe9tElL9gxOK6QEYE8NYRl9dRDWlUymCEQ5gvEghjuQGy3yOfWiHCj+NGZ8vfsaDoWRMHBWbwkQB167elWf+kK8oAOr0HgSmYtGF0lNWQ7qyKHmh/F4nI6MoGLKdIzPBibovEzscKBRXV23QZLZIQ5LqKysBDlVcuwTUovdQTiZFkoyNVqDTWQQfWyLQWx5ltWZjdV5zbuQjIzx6unpJtGFYEJPaS2Kp/Rk4VAjFovPzs6pvdaHQKZnZtrbr1E5Njbe19ff3t4+cGeA9oTWugoxDYRtmdT01LTqfMR8Clc0cCkKU7eMwoMP09K3vr4BFVPXyCl6jY2NEcmDA4cgAw2Js86eOdvT04vF6e7uuXz5srw5TFCz5P8BliHJdFlZmOEABgLAQ9cssmADhLQ3A/LTEv8297CHPWRSIgIUOEQkxU/K47itImeIANQ39y2EWCYxoxI2QAPqZ2fkZRqGmp2dgWHUq6FVsaML+rZ58yZEJxwuQ/gYFnnSoWAnHbEaHNIYpfX7/RAffcMBUGmYIJESgCoyfsZ8VkLefzdxsuQxBjQ8Bn+EiYKr+Ma5uGVGo6URYpEKpEeMt7lUQ4U0lpFYV5bJVZTNgGK5yDM1AAEr/NXAwJ1LFy8iQ9gaRmMcj8sjzzBfa3/vvffxDIODd+jIkuWUQZKOqC7RDGMSPKNm4MAUoAImbHi6ZDIJEVBLfTMJYlMPUnSZnp6ZmJgi7pDu1KLnszPMwnTooe2dlsiwrMDQCrRZOzhA1aGh4Vu3ulA3ZR8spcBfw4UcRqe3twcrg6kkPqqukmeqx0ZHmZpJzZU5L/H2xMSkOluVCjF8ki/IS9RMxBJgHjWydvO9VXgn+bNHonGhtuQIzsqqispK+V6n4sY4oHfrVieSCHmoYxsdHYMU2C+dGmsyOjqK+jGCrM2A66//L38NcTEVaDqtEV8WzDwMaijOYm3A5Wb1IiJYcSy/pvBvc4+ismf9NnclQbOs2dlZKnfs2N7W2kaegnpzaMhOFE14I/khXVh/PCEWGjOMjjHC3Ny8PtxMc4QH30xjSMoIqARygydnmF27doXLQgSjMqL8frIrHo/hc/Bm5EhwCMmjGVxgXjA08i3f9cFBumCqy42cz8zO3THfZwYTcMbKRCrKDcsFFDdGw0xwltHYV1VVIQFmXgEQRUPAfHBwkElxaGYia8eOHSAMgXBHcLmhsdGMk6QSKC8vhz7IEH4A5aFvb1/vzRs38RiozP79exsbGwPBIFiZ93sGiB6RZpVO9qBJvC2vywnW4vbQrumZWbpzFs9BMxQVLFkJtOzo6Lh+/fquXTtYIAMKYvKs9fzgoCSToAqGzAW9GI6+qrTs2SAsBKbl1NQ03ZFwKAzaFJgZzaIvDVjancGhGzdu9PX1RiLlpNZMB5XgHQvH1dOXwUEPgSFvqqmphW5MQl9cNIPTDMeIHoIJDdBMeEcbloAgSKAEVqh5oWCCnpRewcKOCwkkFBKbRSXRGfICa5Cc4WFGHaTeDCVJPmq+ZetWM3URLGphOSXs6M2bN0X+5G6k2A8YjxyYZsugP+EnP4H2bxmU0woIgXFNsiIkGOa1tbVh6pAqDCfBGOGxtlTAw3Tf7kaCkXsYgChQiTzhUSEaI2wxoOYWA4zMffrpZ9PT07W1tU8dfYqkF61gcHQR0Yfm0BkEYCFMQuAgvtoUY0NxWd7y8oj5+lFkfHyMcYjMUTbqDVMlRGJkvCUNwEHfB0RG4R2SAZIsLRyWd/q3bt1qzIdjamoK/InH+vsH8KiaJdFsy5Y2xmlta6swr+mBJ0Es+SfeRjFRk4c0Q7RUSpJkcGD2pqbGPbv36GLxbAxOM5DE8qBvIMmeqVEY1MO884gCoIeZqSnC0XZUlzFZMgizHKgxOYmO+Xfu3PnoI4909/ScOvUZHTnFmIlE7Iknnti3bx/tMVLs0QDZL7NU6uFqnzzg1W4cqUSh+vYitgMFQz1w9Ug7y4fyODfmMl8soaWMhmH6/PMzihW9WPjOnTsOHTrE4dz8/K3OW2g1a1SxgW/KLJwuK21t3bx9x3ZztRwPmScaApOxsVH0U+kM3UAJRqN9Znw5RHIo4/CRPVJbFgs1aM/I27ZtO3jwANJFAwXRW0iva4bo+GgmwOxxCK6r9RYkzX7ZZf9bBPQWyWOBgMoKVAuFwohUU1MThsxQ3ISvRnuRdUACjVx2aHiYrAONhUPGU8nTgubeh7xxApXb2lofeeQR2IZzh4pob/v1dhQAZT508IBeOqJetaKzsxN+IC5yWdiE7uw5rQEkw8IUxkS1ELtJ820K+MpemylfWQVl5bSqNIegp6uDiQgouB86dHDXrp25bO6s+XwhSk4XfAWNjeXKsQRGe+yxx1o3txDhqWAw6e3bXbgXHAXtaQyowIESst7U1FxdVSUBZC5PEtjefh066JUClUU1i1QuLCwwAsnCnj27A/4AXTiLw+juvj0/v4AhgJJkHLW1dS0tLYg+dKEBmGA79BV8FKyhoeHA/n2sS8PR1UoLQHgTeboWoouzMzPIs4ksogSlUBWTBLVZLy0ZDXyq5TPY8pEataeMhj53dHTi89EosOL03r37Ghrq4crI6OiNGzehLdaQATEBBE2ske6Li+JIRW+3b8c+mCnAMU++ChnBGZKSljK73raFNeDDHq5xSMCL+YBHp06dVltJZV1dzaHDhyvLK5RcChJH8Yexle6ImrpfrVF5XQ0ryHRPWG+c9eBBx39QQJAgiuG4AItlb9MFZfD7A+Bgk0VJQVqosoITNpeNNI3UmyvFxhhjxlWpNWfFa4lRx00FgwgBbYxREL3lLFpEpsTgTEe9aiNlu4bBKZMwG/stsqg8WoEzAkcKxKEGCLTUaz/YGsnKjATjRTlLWohMoyTov84CCEGI3IzDZGESpBkJpqXiSXuUf25O8gjCS6wIKoRz1pXSnUQR4tCLQJTpMHAcKv6gqoe0tPG0y6p48wvzqkhQiQUaDykLZHZtFouLZJNDGsOn1CuSC9BVAKxIgZrSeWEBlMDjUdaJMFg4XjwkVFQO2rEVygkCHOLE1EI1NjZoqEJfGquEaFn5ThstcIq9YYGgBxrgDw724CoSS2y6S3iWuCMd+/v7iBTKyiKYDDilrNeJgKLe2qu9T1Ay3X+v1WT9csEe/z5hPTQYh1PIupSXDDk1pevVszaU2nu7gVauhhV9bVjdXgeEneZoJZROqrDeihTWm3c9WA9/hdXzrokJleDPUPZaUMjiwd1gT7censgrw3LWhDDyJKZKcOmqSwdej2426Iz2dBuvV6EUtxULsbtTp3gWD9dfl57S9jbcEx97zofU29WgI643zsZn/zBgrxnYAE9OKflKKVuK/woerCYxDdaj+2r+KaxurzNurLf3D+vNux5sjH/pWbvGJlFpja23dr2eWoGPPeCK+tVgt1wTFAdAObUabNxKDzcAbVnazDYcegisRon295xiRa8VzVaPqWBPW9RbG9Zb8MZwT3op5/6tgBJRaaeY6wLtJdiMKeVQKazHrfVg9SArZlwBa877oJMCayK/AegUpb3WqwGoJORDxFfMwtJse3SfCNjjlJJlNRH0LLAB3diX9rKxvU+4p97aWNGEthyuGWVsgAPt7fh/BdjDrNRbwCYK+xU4rYZ7UupfFdgE2mBdpUSkWSnBV69xPSppvfK4FNbjx5qgjP9XBSvEz0aP9SoR7IWrmpUSE9A2D7Eum86KgHa3J71/sMfRQ4X7HGfNtdwT6KV6WNp9zb73bADY9F9Xb6WQLybc9wS66Kz3uRgbVsjBPWE9fq83Tmn7+6ELQDPVN5W8FSOXDsip0ji2dMzSuUrhgejD+OutF1i9ZBrfDx3uB9Ybx4YVA7JeXVop9djbleztwz8ArEd/hftHo5QO90ND2tNsxXpLiWOqlxGwx18x+D3pv5HeAvfsr6C4ql0pVt0H0OU+7cJDQ+labKoBG3BOF0JhA721K1fkn6v5oVCKxgagGG6Amw33yZd7wnqIrTe+3b6UmAqKttZDQJt6dLEbr7c0e7qN8dGzjKbj6OBrwmr0SmE1Guutdz3YAM8N1qv1pZUPOq8Na+jt/QCdSqe0cV2PMatB239ZersBHQH77HoEtUEbbKC3K2BNvV3dZT30VsBq9NabnQHXnGVjbL8U0LXYqCqU0rN0FeADiVbQs5QaD4RwaceNYQV6K6AUW4UvkW4gqbPbs3C4ekbgi0xa1FulyOqBNqZUaXtFd038VkMpa7Vwn7DeUtfD025vN1hB0xXAWRUy4IH0Vj0MBe1Sio+9WGCD9a7Z7J7r1QYrDv8AULrAFbCCwoakOcgJXWwz/UUQ3mBqG0qJuRpWkPeeA94/kgzF1Do+vfQQ2VgzDtUGa8I9Z3T9zd/8DX9ot2ZTrV+9AQY/KWXlGQZDCPPRvYL8sqjcbp6ampqbmxsbG8vKKwqW13wnIZ1J6erkAU4UQ17Ulocw6UkbLZfuGVVntA+BjPySjTwwjNqYBwz0MSOjMGYcCgB7uVvtlGeeoA91RnYs4wAAuSvvMl9gYAQaR6OLXo9XarJZ2ukzyauXqQXzMIArGov29PSwRp/fFzAPmieTCY95qpkB2QQxtyuZSsrynRbLHxwc9Pv9sbhALpdNJJPmlYH04mI0lZaHYGVF5oXbdEqepOHQPOkqOEA4syj9hoY8BwIONJCpBIrrtcmih3S0wV6F4k+BxgzOyKDKNFBDu1APoZaWLJU6u3akMDIywsL7+/vT6RQEZYGQlDaLsejMzGy+kPebb5HGE/HR0dFIWdjgKGgzLugzrCIsD3TL42vFeW2c9RQ9DCbyLHcRjSUWz8zKr5lDlWgshjyAAASROWQ5BdEWtzAIUlMDSkgA/I3FY1NT03T3mqcgKKjwCDEN2dmWpFHqbTyXsIJp8oihUlJE2UgIKLIXWYJlmTQcn5ycymTlcTe6MyNDuV3unHzYwGBoesnIS/PaBeZVsitTkFb70IaHjJMB+jGH9qYgTC7+krI8OvPmb36LzDWYH5Lo7x+YmZl+4okndu7Y4XK5sb76sAuNdRCIQo2OqQ8GGSoUecywlIQNRplhiD4Bo31poFbcHsSuQcLkOZVkEnKYGeVp2OnpKfvxY5BJJBNa7rp9+9y5c6++9lokHLaRNMMWn2gBmBHi6nNwAKd6e3tPnfoMVfwf/6f/qbqqquSZmOLzQ3Z3Tjkd1s2OjmvX2pG2YFCeGqd+dnY2HC5D9CnX1ze8+OKLtbU14Owzr9pq96V98SEn6gFdnZZ1ydrLXr4CzUCYAoLCXk8tkb04uDlb/JySdrdpCx1c5qNZNuhCINepU6dAvqmpeWRkGJ354Q//XXNzE2d/+5u3bty4/vLLr+zavWugv7+3t29ubvall142H3Au8l339hLs6WA6kqqLpZ72pW3gvvKRfSKRuHHj5tmzZznl8bgrK6tefe07NvEZgcLY2Pjnn38+OSmfTcxkss888/ShQ4dOnjz57rvvfu1rX3vuuedZrnLZBuUgI6htAigjftDEHlbBJhSEtSVfG3Cqu6fnrbd+i2mA0fF4Av4y9XPPPasrog2L0rlobMa7K2csZa5ZeFGctAYo+tuHAFalSBvzVnwXFMPQ3d3zt3/7t4888ujx5583j/s2btnaVl1d/cYbbzz3/PMYTlAHY69521P7ZnPZVDLlUX3A+gqiWZRNh1WqAbgyn8+HQYRz+CjamG+BC0HFIBVwpOYtJeyquFeLEZjC4/Wo2Z6dm7965WpHR8f58xdwrdPmJ6pQtnQ2PTk5/U//9E8HDx7csX2HzEZH+XgaNiLPImEhVhM0xByaQBiyUsksNbU1oVB4YGDg0OHD4A7bQIjGgYBfIwIQE8OB3ZCn/F1Mt33H9qEhfNVwIBD48Y9/fOLEiQMH9h89+pTP5z958hMOMTLgzBqZMA8dMNTGvXOIEVTtFdILR0FvadUF/SUxeVYZJNE4aCTIO5lX7LpYd4M5UQbHlMGftcMAAgSlahLNN48EoyFMR0fGZ0A8jMQmLn2+z48n+U//6T8+//zx73//B/v37S2vqLh27drmzS36cumtW13Dw0Pbt28vK4vcvHnz2rWrcPapp56ko0o2TNFYBkASmIIlUqmWWr0ZZ6mHiVn5zRN5LUEsplskJCev4Mkr4hBz5+5d+kR9LBbz+wOVlZW0ZI0wjrXcvn374sUL4N/cvAlF3bFjB7wYHh4mRMLcbNrUDDKMRrADoZhRxQ9qg4NiKKQwL7RCLsggtLXkkyZQW+XT2HE31KTl0iqEkrW1dY8eeRRygUNNTe3rr39/3759SkDYC4hkmpcrGVZGMB6b2MQwMQc+DK6kUEMGMIUNyyr+QGDEpmjCFYxUWFi4jz/+/b59++XJb/MuJacwKlu2bDl69CjrwVuy95hHZyXgkKfZczTAjUA1hgVFwkbOQn3DKvnCAGU2BB32cAppxbF4fX7huqAhgRTT4RnYTJeCEpcCHZlIeuXyBw4c+JM//mPsbjQaS6WSjQ311CPZiNf3vvf60aeeoi/1YIhxYQosHBsjlJWVoc4yiFk5ZTEWhk+IIzWYIdATk2R+gA/2UOYszRkHZGgDMFooKA4T9sgvbplvf9MRsuzZs3v37j3YZgSHdcFPCIIG04BDNB/OMRr1tBdam8eAwYG+yJMEhIY4TCFWD5AXCuTtcLozCJtBQRowBY05BSMYmQaGeu6AX18DQFBETwx3hHR04ayiSmPsVCQSaW1tFVI7CniSlpaW+fl5tJ0GJ7524tVXX9uxY2dDQ/2LL36dmAt/SBYAVVUeNN2lwHJYm3Bcvpshb+RS0OiAxiBJpiAeSSyHfNKNdUJeg4x5vtfvJzjiHMTEs/X19cng4gzklQP2Q0NDnKAyHA4TC8AUuuzZsxdeP/roo4gcBISI4Ex71svs0BWswAEKUFCbKzSUp/89kBokxbCbX8enTEeGpS9lmdp4YABSQFuCXF2Lef9E3p1mLvpSQz2HTAcCzCtTu5xKfxbBsDRTkbM/HKe9FB5Wb5c0FuLqIbNmcpne3h6YevTYUfNcuKiEChNTPvP0M4lEEixVmHBKhgEC8IA2oM7iaQlxIRZIq3poYExH6CK0NtJGFxpDBdqbs+J1IQ0bBZmaEcQ2y1nKoIF5RpI4dfDgIeTp2LGnGR/FAgH0eevWrUn5MkOGKdANBmE6UIK4Bg28XJIyjamHpozMIWKtkhGLxakXThiCwA/Zy9cwirpkr45Dn8/L8hVzFVMK7F9//XvyqfFMGmFlQ1ZYEqeIS5mIpTEC9QxlGCfvoLMuGpz89LOTn5xUQWQcxmQQlETxpwttIIIsSkIR4ReNGR+6gSTjU6bAgHRhdWalqL28AY9ZpAtnYYGOhr9i1TCa2Tlk4V/72te3bdtOK9YFnR9/7DGifXrF5XUl0VW//AaPh4kYX7TOEJPl6GsJ6YwYSmpspYXprB3sGBxagCdnASUvHREtalgOOrlpUwsB1OzsjKSU5ieOmIWENpVKNTY2054CC1RpqampOXz4YE2NvP3DepmXVTALvZhdlmkyTNqTsuEbqOEs8yq/pJexPks2LqPkZRBaIpaisaJ1IqI0IAnSF7aoYUAqQVtMrbFTpGnU67CA0h8mMqxwhBbGJXAIDobpRXjIOBkvbrhIdCG8lLjLcszPL/zqjTcee+zxvbt3s07O4iSMXACIQgHXpMHn3Pw87JyYGEeSGIF60EJO5hfmExhUl2t+cSG6GI3H4+FQiLXNzs7Ji8GmJZLBvrenD2sK11E2eR85lcKkmVAHzZJ3MohExXeJQxIgrGJMKEh5ZHR0cXGBOKqiohwykeNPT08Ta23atAn6MggLYXbcfTAQZKlYdCGtua7GAZJBKCtWVvQgPz8/19nZSVAUMt+jIq6bnZlLpdOsLhgKmgBHdEliPrGuovB9/f0zMzNElfv27ZVTlmN4eOTNN98klJI3y82PUOhvwBICEhqURyIoMppLsHr58hUcC776+vX2isrKYMDf3n6dSsS3rr4umUpOTU6xNCw4LpH2wyMjlCEaGPgIHzweup8+/XkkUp5Mpgg0mKJOfpJPLKNBVmancnhkGLIwLxoOlUSpzI/rwqnxiQmIPzUlbwIzIHyprq6sqKiAttCEmOudd99DBIkPkbtz586xcnKQ3p4eTCC2m6wY3R4VLpCtyMXLuro6VI4I9ubNG1NTU6QMZZEyojGofru7+7PPTjU2Nd64fiMWj8tXfkymAJ4MzgI7O28RqvT398NcKre0tVGJBn722WeYaUSIQADibN22Fa7hKREMls/SGurrUXukh0NkhjHv3BmQL8IUxD8jBhCL1Ix8qv16O4sF4BXhHOihpYxzh9aDQwsL84iuyphM4XShciDvDwR65de3bzU0NMJoVNXY3MylS5dn52aDoRDmj7Uj2Po5gYXFaDAYwjqNjY+TbEJhMgBiT/gId1SbRPcM3ENvWcOaoIqK1IKuOFvjDKH71atX9u7d29bWhhDoWXiGDYGUlInlDLq9ly9dlhTG6eruvo2IQEEoPjEx8cEHH0YXF1DCd9999/r1GwMD/fCelX3++ZkrV660X2vHitfX19+4cePNN38N+dCl9957t/3aNciKoCBh8BIJgF6Dg4MI6MT4xNYtW1C9xWj00sWL0BqdPHPm85s3O3p6ujdvboVDl69c/e1vf6MJD2FEe3s7VKM701VVVUE1EDUGD43DlHoKubwyjOACXpp30OcOHT5ESE2La9euww8o198/MD8339jYAA2FDoZEhlC527e7p6YmISMZIBYEhLu7uxcWFp544gksGur6T//0CwSuqakRhC9dukSmSlBKzednziCOcBdluHHjupqYTz75WCJAn59UCvfe2dn1+eenR0ZGd+7cgeW73n79gw/eR8kbG5uwYmc+//zChQsoDPxjkMnJSTJAXBNeCCNFSH7lylUoUF4eGRi4c/r0qWwmu3XLVhIwBJGFoMBQADqfO3cWbwzL4DjLxEGhihD5yuXLn3762dDQnf3791dWye90gj/mdXPL5rNnz0F57BH8bWtr7bp9+/Lly8wFC5DLDz/8EIWHBRjBixcvEl2HwqGLF+h9ETMEHz/88AOwfezIEZQEYwFJjfNwMsj+/QcQEsQPNu3Zs8cfCEIHMHzu+ed7untgUEVF5f79+xZjsfPnzl24cL6zs2Pz5s3Nm5rPnjl7/vyFvr5eTBiDo2MQqre3D76zHPwqfIF60Jkl0BG27zuwD1cPqVmOyHY2c/r0aXT7kUcegQswV+IUecdT0or+gQHMOmnCwUMHSQ8YGZqzQFCCaMjJrVtd58+fw7Du3Lkr4PeTF8Dfs2fPQGFI19FxkwZNzc0YWURLTb8q4LLnfSBAaQ0s57f4fSQPlpSXy89ngDSqSL3cFzFZImVSO3jz/vvv19RU796ze0tb64EDB1nGlavXOIuB6erqnJiYAuPv/+AH3/zmN2AG3GLYb33rG3/8J39cWVmJAkOdOjxLXd3IyHAiEX/ppZeffvpp+n788Sfy1rLThYYjf88++wzRL1I1N7+AQz7z+RkkAymn46uvvfbd734Xk4l6o4y4WUamAA6Dg3e6um7T8fnnn2PeoaFBvD3CATNYAjva9PUP4AFgxpNPPrl16zZxzlF9q1Neqe/o6JCP05NF7d3L7NQg31ADBuP6aSbJmnzIyovbOXv27McffwxB0DROoU6c+s1vfgPbfvTvfgiJvva1E2gsbZCYhQX5+AsdJycn8DBY97q6WvMOujht8rcXX3zx0OHDTz75OIdQhrCwsrzi8Sce37Vrt/hb8z1BSMTCCXaA773++g9+8IMtW7YiH9gfODhwZxCC19fXHdh/4OjRo8R4uKDpmRkCC/XGpDCsBf98/PjxtAHaYHzfeeed8xcuYIpBBpVjLgD3gnZRwBOC9okTJyhDK+iGi370kcNo6YED+0mPocD4+Njx48/v27f/O9/5Nm3wgXQ39nEQMWCx8nZ7dQ28UGIyFAjjCSiABkaKNpAUi4xwdnR0YsgqysshAjJJG3lnOFx28NAh7BctWT7Y0oZ5OQts3779J3/8k+PHX4AUCAYTUfnee+9FIhUvvHD8sceOcAoNP/XZKXiE+iEzXztx4oUXTrBk7BdGGZGAPpyFXPRF6szAAqTxnKKA81dpIS/7zqvfgWUsCgeDJlN59tw59PbRR488/czTx58/XlsrQs7guEYEm39olm730FtIsOamoNmUXqqxAdsJ6kg5k5lDyUMkojTf1EK7CGifeOJJr0eygoryyCuvfOPdd98hJDh08EBLS8uWLW0wEmu3qbkZgYOyBw7ux9pB9EOHDkJlOKG50+7du2mJ+927d99/+It/DzlOnToFI9EW1AnRr66pRqrQc7q/8srLeE4C0caG+vJIOb1IinD1iKP5mYwqjDoCgV1AAaihC/pMDMM4mB4wR1bQQIT41KnPMOpEPpwiEjuMpzVWKZZIoGCtrZuRNoS4v18ukyB2dGSlmuYBiDJyg6hhQf70T//kpz/96V/+xX947bXXEG6/z3fj+nWMDiE0fEJw2VQNzp8/X1dbQxqJBhJ3PfvMM//7v/orlo+fRIAwMZhkytBNb0vqFUtYEw7J71yaGjdJ1SvfeJkyLHj0yBEj1kHWS40JAp1Xr14FK4QbqVUrwzhwEybjRExBxJFTx449/R/+4i8wu9SwfAAbJJ6NmD4SoRLFYK96yyCsF/TohRbhTnFZ5LQ4HLJiwnKkE2ISv5w8+clHH/2eFY2ODqOzcA1GMMILJ47/5V/+5Y9+9EOyRywoyLA6lhPw+Vg7IoEagDktibQxTHjpb37rmzCUGhtYVDgUjkTKwAF/CHbYDhAAN5i4dWsbWGEK4Y6JRxwnT35KgUiVoODNN3+D7+UUe2SMBAEbSvw8Pj6OZcTHxOLyiUlmREiKeanPqyZDSGdYwBSPPvoIldigHTt2wBrCMXgBx422Z6AMarxl6xb4jr5873vf/bM/+3NstGgsC87JpUooIBGuLOjBgSGKJQNiACyXcCwSYWGgTg15oJwyl+CoAXWoAD+2bdvGIZEMbRBoVULiRiRVZNfvUz2HPZI7QV25RSFqj0XgUF0HYopkSJafz5Hu0wZTrd86/d73Xi8rCxNwjo2OosyR8gjdIQqyBQ+YkdnZaAnD5PqAXM1CLuVa/DPPPLN33z56EbvOzCDKEjiIzZafhJIrDSwc+4eIKJPIZHDFqArGO5lI4hkwPYQDGA4in6Vr1LIKvz8AGqWSBO/1kBHwz3ghtdBgBYAhZ5GGQMCP7LI0MMe4wHXVHw5BBpuicQ2VdNFl0l0yecTQLFOVR0MG3A5lpgZh8KEvlKSG5RMWPvvssz/60Y9CgQBx4NUrV6ADyMuNNEfRbIESyBhquHDm3/jGN374wx+COcktfEGFmAIMASwCjZE/BgcZgl7pUllJ99HRERwjOGCUa2qraUBHfEtjY+OWLVsYDVP+rW99u7KyAplRMwS2jMaGmCFOIIOcM1c2l0PkWC9rVCPCsPhqZqcLDdA0RYChROfd8usn1ACUMbVKTHIQVkoNVNKzzMWe7tu2bcXEHz58GIfMYkEM5QdP3DJRxi9/+cuPPvyIeVgXFGBGemEupbt5XgLciH1AhnqXy4PQMiZsNMh45PudPr/wO52OxuIygseN3NNdxaa1bTMNsESMLKptrtjB2YfVW3TLGDD2WBcxAC4n/k0OTSU5rZGM4sU9ajwuD9JEIZUSBmBf6UUBgYbNxv4JsCT6UpARXOKa5DqxuetLJQRCjkUBMllaIhaMj9JyCr+k4ogDwU9eu3oVGZWreXIlTG5s0AYZZEa6U2ZGUNWEQSIcnx+K1DfU4YVwbngAuuCpGB/1YCJ6sZ+YGEcxYC8ayymvz49Wo78sDfZAfWbEEuGHq6sqKaArSDl0YBa5LLX0LX+6InBIiRhRQ7Hdu3axNP3OEGqgRg1pYBWwMxAIYYn8fvkcOdyzYzBVDKgEUymAMzhQwOSZeeVqPFynhgbUQB8oz2EshqTKrUioC/2p4RQBMLnuf/1vP8fxEnRgntCoWBRPIkmbqKv5EuVA/x3iDmQA41tZWX7s2DEyc0ZAW0AVZEBSTSR76gG4wJ78pq1tC9YNZ0VwTswC/tqGsKipsZEgi3mJibZv28baQQnaSveMaCmbrgJkKENV4n0wFD74/Kg9xKcxzhblV/tif7gPQG3Yw3eVN2YkiFsKRkTfGNDWakC/iYUsIRWbN7eAEughXYjTo48+evToMWMiU4QJP//5f2PhzAiPhMjMJFmVPAVAG8XKBsbEOSk9JXhxumgDsFhtgGDTHQEDH+UvJkVP6cKRmYfUW40EALiIxmo5Eg6TFBGmkiIyOqYRoaFeZ5qYnGRfU1MLoZFXOsJ45HhmdgbKBoNB5B5pK67Z7LGwLBJMFXvAcFFiBlSaxsgli6clm1wXravDgBF3ffLJJwSTu3btpAumnbM0oyxTmBsbzC62S75lKc6EWVKpJAYCDP/u7/4eph48eIhEnS50RK9oSy8OQQbFAD3hiolbYL/WKHtIjdhjHVga6KEYBnmnBAXGAUIWpqYLY9ISQHPAitlZdIX5tQukjUpEgTbGpshjNxgUkVrzyWw0RJgHq82tJirRRmqYEUAIWBfmjOlAQy0pXcCEpaHGjEYNjZk3FouqBaGGbBwRhEEEqA2NjdhBWuL3ZCHGbLEKrC0h1dtvvQ3FmFTkL5lsa2sDB0aAVSDACKzRHEpSZwNLIFVhXR0dHSMjY4RIUmk+u0+STHYNPjgiKiG7mhtUkj2OkBpFWO8CGmfgJDQAQxMR5KuqKnGDxAiIAYgpwuXlErRTieGAWfAdYDr2sBJ6QhDK2A7asxYsI4cIAnSjGZQng5idmYO2iCuzE4hB466uW889/+xf/dVf4X6JrUgQFhaYIs0CmRSmMxroMZSaUcSS5aCnOjWEBRNaimulmzxikKOM5iOuiBB15m6oh2SBUDybyWJJb3Z0YBfoLiknfx4C1EVoBquHLAxjTGpELARf4Q0Cw+KNhuQSycSnn34KfQ8ePECAQfjH2owVd126eAlXCa2RewgH3ggrZxkWZWA9qjDi3wghvD5MBuSjBuGWNiaCYj23b3ft3LkDA3bhwgVC8S1traOjY+g5KYSyUCUAoDsshEbhsHgexoc98A9GXr58mdmfefoZEJifn0fikRKDjKyXNeIxwPP999/XjEXlgCWzj5SXNTU1Y7bGxsZxqiyNvK3r9m0wpyzsNA8iQ3Sdl+BNv3XGMlF+RA2sCMmgBh3RCvEYqXR3dw9qcOTIEdOlmCRzlnEoGF2V0AtsR8fG5hfmiUipJ++QkFq8QS8WjQbj42MwJWXEyKxVwkIoEwqFaSiGyO0xl/TSTU1NcgVxdBTq4RuhiV41oTGWB7biLohHsI+qWlDp7bd/t3v3HkgNuahhOhVQ9JwBUS6mY+2MQOBHCkf3LVvaDAIZEs6Wls00JkNGOokvENDf//5juRG/JGDil4wZooz+QEyYiCSMjo2D4ezMjKzI48YZshayx8aGBs5CeTSEGs7po2ZIrBDFmDmWDJs0cQB0cPwt/GUopGLX7l207OvrPXfuXHdPD5J54cJFSMSSPv/8886OTlaEyOklMWwlKKleUYAtdqyBDKPqzIgqwkoaU4lQiWqYKF3lh7ObN7fS4PTpU1AA2pKtvPPO70ZHR/HbH3300c9//vPu292sgsU+5P3bors1f9ixyUV5NMrtQXZv3+7u7+8n6mMtsG1sbPStt94Cj6NPHSUC7O3tgwQVlZVEqZcuXR4aGvrOq6/CeyzW+++/xxrwvXC9p6f3xg35oqfYY5IHudhwmZiklmCutu7mzZt37gzgnZifxX968lMc7JEjj4LPW2/9trq6GgagDOY+0zguBTn98MMP6FhB1pTNzs7OnTt3lpgQJZmamr527Roq0byp+VbnLewrPIZP6BXTESYRG0lwbu7Re9yuqqpqvXaP8mAOJycnoC9S0ra5tbGp6ZLARf0kOpaSdLemthZMhETmO7off/wJ64LNjH/9+g2W39q6GclGpNLZNEljXX19V1cXVCICR457erq//vUX9+zZ3dfX99FHH9JrZmYG4SO/CMpv0oq74BSRZ2dnJ2qPti8uLEIcuNDXV7yriepitlhjf38fWJnPplusi1Tz8uUrSD4qXVVVhSljaijQ3z8ARzZt2sSwUODQ4UNh/LmJnoj1QQBphjhDQ/JJ2lOnT2H1Xn75ZRTts88+IwBGEqLROCzovn0b3AhzCLbL4X1FObYaKzA4eOf48eMBv/xoCC7OpHBxlBmLT5ICenv37kW+P/n4E8yNuQe7gL2orKiQu3Hma/IE6mfPnsGmTE5OmikSW9q21lRXd3R0trRs2ty6eWR09LdvvQUysGBiYoJhQX5kdOTGjRukuqgHC7x2rZ3EB5MHZZANkhSWPzDQL9G1P9Dc1Lh169Zbt25hBFks/mB4eOj48ReqKqugIQMieBMTkxcvXty5cxfaCwfD5hPtYD41Of2rX70BtXFIkIuJmA5hRuFRBLAti5QR3F+5fBn+UoMO19bVYXHABGxBEjFi0l27dj/22BFYwErpjttrkGuW5nv2rOehgd6qwzoMpkXtFqTUjzDrtSKM+v79++xP5nIWwkEvlE2/cInpwjWhoprP1NTUwF0Yhr5ht2hFFAE7UWkWRpczZ84ywoEDBzAB6M+2bduxtQyCqSNKh/oHDx5samro7e2nGfLHnmERICQMZgwODlGmRsMkgJFRHgoQi/2hQ4cg9CefnCTYJu+izMhq6jiLq8EGwx5UDpwhPZKHeSZMwkwgamjd1q1b9APiuERdNeZ/anoGJmF68YpEbsRv1JM1lQ4O0AYJRn8ikXJ4SZ4Mp4nQ0GSGoiMxS/OmTUSJdKQ9+EB5xkSkYAeiQ6zBAslK6mprcEqYicqKclzP8NAQ7k7Xjs1iTAoMCxlBqqa6CnGHwpTJM2Elhgm1p4wDwcsJcgbA9tatLpbKSurqalmC+i5yMyy1P+DHXcM7WlbXVCcTSVaERSbQYBCiDNBD0DkLywjTwBlMoAYyU11dFSkvr66qglxEznSHgIoea9H1AnhNwlcKoEdHrFJNbTWDQwoCZgSbxUITGiA5bBzillkaZgJqKAU4xBLDd6UneFIvKZDLhRxWVlVB4XQmgy1gKJYJxaAVOOP/Mc16ywC7YJYvwaMuBwWenZfQmrMYJfY0ZkwGgUCsaHoK3+AnAUHINQxkOvw8jGaE6ZlZrDmIVVZW6de8WSyGAPO6efNmlIWRH15vbY215bJUhwkDILHNFa0kdSFk55AuaCP0JNhDWEGL+EoHoYt2ZK8jY8VBHVnUJ3u1yy9/+c8Y3Vdf/Q5nYRKBOsSiMfIRDErAaRcYSmkqqzWvm0BBxqFApWBmABxK5dL04h+hlGDFODoRQRoRlC09ipsuk0N5nlaenhcNZDpF1W6jvbRAJWgwLGQBc+p1pUyhdNC1cxaAmLoEm7DaANw4VEHRpZVOBBrgb4YVfLSeXpyikkMEVDGUOZZmVGy1BmOBmFLQShtnewrtwiGUMiuVq526Oh1EkdRrpysQk9xyCXN7USyQ8QFFz0Zee2nBTCfUsLGy9zrFUk1R2ADQYDqdQkFx0PGhh/bVYbWBDqWVKjC6cMXKxlPZp4Pb84KnUkabUaalvZbSNSrdqAFgn819Haq0YE+tiD2w3mpznXg9KB1ShXI9gGdrNqC+WLobWBtcgB+/+tWvOPzOd76FoLAePXv/sN74CqCEfjIR1OYQamq9Qmnf9VanbUrP2jVLI8udMyp1RSqRCqXUY2YOV4yjlaWwHhobgI6jIrUaOLuEqgBNtT1QOvU956VXsWTAbm+ScxlxxbClA9qzA8VGq8CMY8PygIi7HC/1WoHGxrACBx2TMoOB0T2XvCbcJwLrDb66+wOs5/5hHSKvDQ9EU7V8LI8YFX+L0oqBkgtgKzdt/6BwN+YyiPIe/tlixOwPxzygBLFiwZatNcEW3bsQWJbnItxzvavJwhLWU1obFLeNMbx/sIlmL8SG1SsCmNfeilWrQHymPPzDukqWJk8TLPe6J3EA5aluxaolAFuttJFcjf96sJrsG8P9t7zfdg8ND4T3PUHCQvMrz+R+k5MTpBlSuXRDxSY9TqxU0B8UGL9UYtbEXycqHjwImDFlfB3W7Deiz4pZ1puUcUq3Yu0q2PjsatiAAoBdr8Ou3lZTSZli9K1IAZtLtCztq2cVVtTbm5psrDmjKao6Y2kb6X73c34PDfZaSscv3fQsYB8+6LzrjbMCvpI4GXgIlVGirImlDbRZiC5eunhRr+/DuKNHj1ZWVejtBxtsjVX/fP+g61qNvM0wYGMMNwbGtwcvnWsFPVcg8EVmX913vTWuhhUcsZG8n75rgo3Mmqvg7IOubjVK6+GscP+rLoVSrFaMvzF8cYqtB38gvd24/WoUNm6vST8FzdG18kuH0pWWYliK22rMNwZ7tBWDbLze1bOvh4/CaqxsabMba5v15l09ArCi8ZptVkNpr427gKSydTWs13G9tT/QulbAaoTvfwkr5rUb3w8+9z+Lw+H4/wGNw2Jnbejb4QAAAABJRU5ErkJggg==';
                            // A documentation reference can be found at
                            // https://github.com/bpampuch/pdfmake#getting-started
                            // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                            // or one number for equal spread
                            // It's important to create enough space at the top for a header !!!
                            doc.pageMargins = [15,60,15,50];
                            // Set the font size fot the entire document
                            doc.defaultStyle.fontSize = 7.8;
                            // Set the fontsize for the table header
                            doc.styles.tableHeader.fontSize = 7.8;
                            // Create a header object with 3 columns
                            // Left side: Logo
                            // Middle: brandname
                            // Right side: A document title
                            doc['header']=(function() {
                                return {
                                    columns: [
                                        {
                                            alignment: 'left',
                                            fontSize: 18,
                                            text: 'Daily Report: '+ getDateSelected()
                                        },
                                        {
                                            image: logo,
                                            width: 80
                                        },
                                    ],
                                    margin: [20,20,40,20]
                                }
                            });
                            // Create a footer object with 2 columns
                            // Left side: report creation date
                            // Right side: current page and total pages
                            doc['footer']=(function(page, pages) {
                                return {
                                    columns: [
									{
										alignment: 'left',
										text: ['Fecha Creación: ', { text: jsDate.toString() }]
									},
									{
										alignment: 'right',
										text: ['página ', { text: page.toString() },	' de ',	{ text: pages.toString() }]
									}
								    ],
                                    margin: [20,10,20,20]
                                }
                            });
                            // Change dataTable layout (Table styling)
                            // To use predefined layouts uncomment the line below and comment the custom lines below
                            // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) { return .5; };
                            objLayout['vLineWidth'] = function(i) { return .5; };
                            objLayout['hLineColor'] = function(i) { return '#aaa'; };
                            objLayout['vLineColor'] = function(i) { return '#aaa'; };
                            objLayout['paddingLeft'] = function(i) { return 4; };
                            objLayout['paddingRight'] = function(i) { return 4; };
                            doc.content[0].layout = objLayout; 
                            
                            if (doc.content[0].table.body[3][5] != undefined)
                            {
                                for (var i = 0; i < tabledata.data().count(); i++) {
                                    doc.content[0].table.body[i+2][3].alignment = 'center';
                                    doc.content[0].table.body[i+2][4].alignment = 'center';
                                    doc.content[0].table.body[i+2][5].alignment = 'center';
                                    let text = doc.content[0].table.body[i+2][5].text;                          
                                    if (text != '-')
                                    {
                                        if (parseInt(text.replace('%','')) < 90) 
                                        {
                                                doc.content[0].table.body[i+2][5].color = 'white';
                                                doc.content[0].table.body[i+2][5].fillColor = 'red';
                                                doc.content[0].table.body[i+2][5].bold = true;
                                        }
                                        else
                                        {
                                            if (parseInt(text.replace('%','')) > 89 && parseInt(text.replace('%','')) < 100) {
                                                //doc.content[0].table.body[i+2][5].color = 'darkorange';
                                                doc.content[0].table.body[i+2][5].fillColor = '#ffbb10';
                                                doc.content[0].table.body[i+2][5].bold = true;
                                            }
                                            else
                                            {
                                                doc.content[0].table.body[i+2][5].color = 'white';
                                                doc.content[0].table.body[i+2][5].fillColor = 'green';
                                                doc.content[0].table.body[i+2][5].bold = true;
                                            }
                                        }
                                    }
                                } 
                                if (doc.content[0].table.body[3][8] != undefined)
                                {  
                                    for (var i = 0; i < tabledata.data().count(); i++) {
                                        doc.content[0].table.body[i+2][6].alignment = 'center';
                                        doc.content[0].table.body[i+2][7].alignment = 'center';
                                        doc.content[0].table.body[i+2][8].alignment = 'center';
                                        let text = doc.content[0].table.body[i+2][8].text;                           
                                        if (text != '-')
                                        {
                                            if (parseInt(text.replace('%','')) < 90) 
                                            {
                                                doc.content[0].table.body[i+2][8].color = 'white';
                                                doc.content[0].table.body[i+2][8].fillColor = 'red';
                                                doc.content[0].table.body[i+2][8].bold = true;
                                            }
                                            else
                                            {
                                                if (parseInt(text.replace('%','')) > 89 && parseInt(text.replace('%','')) < 100) {
                                                    //doc.content[0].table.body[i+2][8].color = '#ffbb10';
                                                    doc.content[0].table.body[i+2][8].fillColor = '#ffbb10';
                                                    doc.content[0].table.body[i+2][8].bold = true;
                                                }
                                                else
                                                {
                                                    doc.content[0].table.body[i+2][8].color = 'white';
                                                    doc.content[0].table.body[i+2][8].fillColor = 'green';
                                                    doc.content[0].table.body[i+2][8].bold = true;
                                                }
                                            }
                                        }
                                    } 
                                    if (doc.content[0].table.body[3][11] != undefined)
                                    {       
                                        for (var i = 0; i < tabledata.data().count(); i++) {
                                            doc.content[0].table.body[i+2][9].alignment = 'center';
                                            doc.content[0].table.body[i+2][10].alignment = 'center';
                                            doc.content[0].table.body[i+2][11].alignment = 'center';
                                            let text = doc.content[0].table.body[i+2][11].text;                         
                                            if (text != '-')
                                            {
                                                if (parseInt(text.replace('%','')) < 90) 
                                                {
                                                    doc.content[0].table.body[i+2][11].color = 'white';
                                                    doc.content[0].table.body[i+2][11].fillColor = 'red';
                                                    doc.content[0].table.body[i+2][11].bold = true;
                                                }
                                                else
                                                {
                                                    if (parseInt(text.replace('%','')) > 89 && parseInt(text.replace('%','')) < 100) {
                                                        //doc.content[0].table.body[i+2][11].color = '#ffbb10';
                                                        doc.content[0].table.body[i+2][11].fillColor = '#ffbb10';
                                                        doc.content[0].table.body[i+2][11].bold = true;
                                                    }
                                                    else
                                                    {
                                                        doc.content[0].table.body[i+2][11].color = 'white';
                                                        doc.content[0].table.body[i+2][11].fillColor = 'green';
                                                        doc.content[0].table.body[i+2][11].bold = true;
                                                    }
                                                }
                                            }
                                        } 
                                        if (doc.content[0].table.body[3][14] != undefined)
                                        {  
                                            for (var i = 0; i < tabledata.data().count(); i++) {
                                                let text = doc.content[0].table.body[i+2][14].text;  
                                                doc.content[0].table.body[i+2][12].alignment = 'center';
                                                doc.content[0].table.body[i+2][13].alignment = 'center';
                                                doc.content[0].table.body[i+2][14].alignment = 'center';                       
                                                if (text != '-')
                                                {
                                                    if (parseInt(text.replace('%','')) < 90) 
                                                    {
                                                        doc.content[0].table.body[i+2][14].color = 'white';
                                                        doc.content[0].table.body[i+2][14].fillColor = 'red';
                                                        doc.content[0].table.body[i+2][14].bold = true;
                                                    }
                                                    else
                                                    {
                                                        if (parseInt(text.replace('%','')) > 89 && parseInt(text.replace('%','')) < 100) {
                                                            //doc.content[0].table.body[i+2][14].color = '#ffbb10';
                                                            doc.content[0].table.body[i+2][14].fillColor = '#ffbb10';
                                                            doc.content[0].table.body[i+2][14].bold = true;
                                                        }
                                                        else
                                                        {
                                                            doc.content[0].table.body[i+2][14].color = 'white';
                                                            doc.content[0].table.body[i+2][14].fillColor = 'green';
                                                            doc.content[0].table.body[i+2][14].bold = true;
                                                        }
                                                    }
                                                }
                                            }                                           
                                        }                                 
                                    }                                  
                                }
                            }
                        },
                        //orientation: 'landscape',
                        exportOptions: {
                            columns: [4,':visible.exportable']
                        }
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
                scrollY: '65vh',                
                scrollCollapse: true,
                paging: false, 
                "preDrawCallback": function (settings) {
                    pageScrollPos = $('div.dataTables_scrollBody').scrollTop();
                },
                "drawCallback": function (settings) {
                    if (idx >= 0)
                    {
                        let row = $("#procesos-table").DataTable().row(idx);
                        row.select();                        
                        $('div.dataTables_scrollBody').scrollTop(pageScrollPos); 
                    }
                },
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
                    {data:'var_orden', name:'var_orden', visible:false},                      
                    {data:'action', name:'action', orderable: false,searchable: false, width:'25px'},   
                    {data:'var_export', name:'var_export', visible:false}, 
                    {data:'variable', name:'variable', orderable: false}, 
                    {data:'unidad', name:'unidad', orderable: false, searchable: false, width:'25px'}, 
                    {data:'dia_real', name:'dia_real', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:'dia_budget', name:'dia_budget', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data: null, orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['dia_budget'] != '-' && row['dia_real'] != '-')
                            {
                                $d_budget = parseFloat(row['dia_budget'].replaceAll(',',''));
                                $d_real = parseFloat(row['dia_real'].replaceAll(',',''));
                                if($d_budget != 0.00 )
                                {
                                    $dia_porcentaje = Math.round(($d_real / $d_budget)*100);
                                    if (row['variable'] == 'Ley Cu Salida' || row['variable'] == 'Ley de Au BLS') {
                                        
                                        switch(true)
                                        {
                                            case $dia_porcentaje <= 100:
                                                //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                            break;
                                            case $dia_porcentaje > 100 && $dia_porcentaje <110 :
                                                //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                            break;
                                            case  $dia_porcentaje >= 110 :
                                                //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                            break;
                                            default:
                                                return $dia_porcentaje+'%';
                                            break;
                                                
                                        }  

                                    }else{

                                        if (row['variable'] == 'P80') {

                                            switch(true)
                                            {
                                                case $dia_porcentaje <= 100:
                                                    //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                case $dia_porcentaje > 100 && $dia_porcentaje <= 133 :
                                                    //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                case  $dia_porcentaje > 133 :
                                                    //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                default:
                                                    return $dia_porcentaje+'%';
                                                break;
                                                    
                                            }   

                                        }else{
                                            switch(true)
                                            {
                                                case $dia_porcentaje < 90:
                                                    //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="red_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                case $dia_porcentaje > 89 && $dia_porcentaje <100 :
                                                    //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="yellow_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                case  $dia_porcentaje > 99 :
                                                    //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                    return '<div class="green_percentage">'+$dia_porcentaje+'%</div>';
                                                break;
                                                default:
                                                    return $dia_porcentaje+'%';
                                                break;
                                                    
                                            }    
                                        }

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
                    {data:'mes_real', name:'mes_real', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:'mes_budget', name:'mes_budget', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data: null, orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['mes_budget'] != '-' && row['mes_real'] != '-')
                            {
                                $m_budget = parseFloat(row['mes_budget'].replaceAll(',',''));
                                $m_real = parseFloat(row['mes_real'].replaceAll(',',''));
                                if($m_budget != 0.00)
                                {
                                    $mes_porcentaje = Math.round(($m_real / $m_budget)*100);
                                    if (row['variable'] == 'Ley Cu Salida' || row['variable'] == 'Ley de Au BLS') {

                                        switch(true)
                                        {
                                            case $mes_porcentaje <= 100:
                                                //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                return '<div class="green_percentage">'+$mes_porcentaje+'%</div>';
                                            break;
                                            case $mes_porcentaje > 100 && $mes_porcentaje <110 :
                                                //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                return '<div class="yellow_percentage">'+$mes_porcentaje+'%</div>';
                                            break;
                                            case  $mes_porcentaje >= 110 :
                                                //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                return '<div class="red_percentage">'+$mes_porcentaje+'%</div>';
                                            break;
                                            default:
                                                return $mes_porcentaje+'%';
                                            break;
                                                
                                        } 

                                    }else{
                                        
                                        if (row['variable'] == 'P80') {

                                            switch(true)
                                            {
                                                case $mes_porcentaje <= 100:
                                                    return '<div class="green_percentage">'+$mes_porcentaje+'%</div>';
                                                break;
                                                case $mes_porcentaje > 100 && $mes_porcentaje <= 133 :
                                                    return '<div class="yellow_percentage">'+$mes_porcentaje+'%</div>';
                                                break;
                                                case  $mes_porcentaje > 133 :
                                                    return '<div class="red_percentage">'+$mes_porcentaje+'%</div>';
                                                break;
                                                default:
                                                    return $mes_porcentaje+'%';
                                                break;
                                                    
                                            }

                                        }else{
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
                    {data:'trimestre_real', name:'trimestre_real', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:'trimestre_budget', name:'trimestre_budget', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:null, orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['trimestre_budget'] != '-' && row['trimestre_real'] != '-')
                            {                                
                                $t_budget = parseFloat(row['trimestre_budget'].replaceAll(',',''));
                                $t_real = parseFloat(row['trimestre_real'].replaceAll(',',''));
                                if($t_budget != 0.00)
                                {
                                    $trimestre_porcentaje = Math.round(($t_real/ $t_budget)*100);
                                    if (row['variable'] == 'Ley Cu Salida' || row['variable'] == 'Ley de Au BLS') {
                                        switch(true)
                                        {
                                            case $trimestre_porcentaje <= 100:
                                                //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                return '<div class="green_percentage">'+$trimestre_porcentaje+'%</div>';
                                            break;
                                            case $trimestre_porcentaje > 100 && $trimestre_porcentaje <110 :
                                                //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                return '<div class="yellow_percentage">'+$trimestre_porcentaje+'%</div>';
                                            break;
                                            case  $trimestre_porcentaje >= 110 :
                                                //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                return '<div class="red_percentage">'+$trimestre_porcentaje+'%</div>';
                                            break;
                                            default:
                                                return $trimestre_porcentaje+'%';
                                            break;
                                                
                                        } 

                                    }else{
                                        if (row['variable'] == 'P80') {
                                            switch(true)
                                            {
                                                case $trimestre_porcentaje <= 100:
                                                    return '<div class="green_percentage">'+$trimestre_porcentaje+'%</div>';
                                                break;
                                                case $trimestre_porcentaje > 100 && $trimestre_porcentaje <= 133 :
                                                    return '<div class="yellow_percentage">'+$trimestre_porcentaje+'%</div>';
                                                break;
                                                case  $trimestre_porcentaje > 133 :
                                                    return '<div class="red_percentage">'+$trimestre_porcentaje+'%</div>';
                                                break;
                                                default:
                                                    return $trimestre_porcentaje+'%';
                                                break;
                                                    
                                            }

                                        }else{
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
                    {data:'anio_real', name:'anio_real', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:'anio_budget', name:'anio_budget', orderable: false,searchable: false,
                        render: function(data,type,row){
                            if (data != '-')
                            {
                                if (row['unidad'] == '%')
                                {
                                    if (row['variable'] == 'Humedad')
                                    {
                                        return Math.round(data*10)/10 ;
                                    }
                                    else
                                    {
                                        return Math.round(data);
                                    }
                                }
                            }
                            return data;
                        }
                    },
                    {data:null, orderable: false,searchable: false,
                        render: function (data,type,row){
                            if(row['anio_budget'] != '-' && row['anio_real'] != '-')
                            {                               
                                $a_budget = parseFloat(row['anio_budget'].replaceAll(',',''));
                                $a_real = parseFloat(row['anio_real'].replaceAll(',',''));
                                if($a_budget != 0.00)
                                {
                                    $anio_porcentaje=Math.round(($a_real / $a_budget)*100);
                                    if (row['variable'] == 'Ley Cu Salida' || row['variable'] == 'Ley de Au BLS') {

                                        switch(true)
                                        {
                                            case $anio_porcentaje <= 100:
                                                //return '<span class="badge bg-danger">'+$dia_porcentaje+'%</span>';
                                                return '<div class="green_percentage">'+$anio_porcentaje+'%</div>';
                                            break;
                                            case $anio_porcentaje > 100 && $anio_porcentaje <110 :
                                                //return '<span class="badge bg-warning">'+$dia_porcentaje+'%</span>';
                                                return '<div class="yellow_percentage">'+$anio_porcentaje+'%</div>';
                                            break;
                                            case  $anio_porcentaje >= 110 :
                                                //return '<span class="badge bg-success">'+$dia_porcentaje+'%</span>';
                                                return '<div class="red_percentage">'+$anio_porcentaje+'%</div>';
                                            break;
                                            default:
                                                return $anio_porcentaje+'%';
                                            break;
                                                
                                        } 

                                    }else{
                                        if (row['variable'] == 'P80') {

                                            switch(true)
                                            {
                                                case $anio_porcentaje <= 100:
                                                    return '<div class="green_percentage">'+$anio_porcentaje+'%</div>';
                                                break;
                                                case $anio_porcentaje > 100 && $anio_porcentaje <= 133 :
                                                    return '<div class="yellow_percentage">'+$anio_porcentaje+'%</div>';
                                                break;
                                                case  $anio_porcentaje > 133 :
                                                    return '<div class="red_percentage">'+$anio_porcentaje+'%</div>';
                                                break;
                                                default:
                                                    return $anio_porcentaje+'%';
                                                break;
                                                    
                                            }

                                        }else{

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
                            .append('<td colspan="15">' + group + '</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                    }                 
                    /** */
                },
                columnDefs: [
                    {
                        targets: [6,7,8,9,10,11,12,13,14,15,16,17,18],
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
            $('#procesos-table tbody').on('click', 'tr.dtrg-level-1', function () {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
                tabledata.draw();
            });
            /** */
            
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
            idx = $("#procesos-table").DataTable().row($(this).parent()).index();
            $.ajax({
                url:"{{route('dashboard.edit') }}",
                method:"POST",
                data:{
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
                        $('#valor').val(data['generic'].valor);
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
                        valor:$('#valor').val(),
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
        $('#modal').on('hidden.bs.modal', function () {
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
                    <!-- <table style="width:100%;" class="table table-striped table-sm table-bordered table-hover datatable" id="procesos-table"> -->
                    <table style="width:100%; border-collapse: collapse !important;" class="table-sm table-bord table-hover nowrap" id="procesos-table">
                        <thead style=" border-collapse: collapse !important;">
                            <tr>
                                <th rowspan="2">CATEGORIA</th>
                                <th rowspan="2">SUBCATEGORIA</th>
                                <th rowspan="2">orden</th>
                                <th rowspan="2" style="min-width:25px!important;" class="thcenter"></th> 
                                <th rowspan="2">ÁREA</th>
                                <th rowspan="2" class="thcenter exportable">NOMBRE</th>
                                <th rowspan="2" class="thcenter exportable" style="min-width:25px!important;">U.</th>
                                <th colspan="3" class="thcenter" style="min-width:12vw!important;">DIA</th>
                                <th colspan="3" class="thcenter" style="min-width:12vw!important;">MES</th>
                                <th colspan="3" class="thcenter" style="min-width:12vw!important;">TRIMESTRE</th>
                                <th colspan="3" class="thcenter" style="min-width:12vw!important;">AÑO</th>
                            </tr>
                            <tr>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">%</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">%</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">%</th>
                                <th class="thcenter exportable">Real</th>
                                <th class="thcenter exportable">Budget</th>
                                <th class="thcenter exportable">%</th>
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
                                <th class="thcenter" style="min-width:6vw!important;">Área</th>
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
                            <label for="area" class="col-sm-2 col-form-label">Área</label>
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
                            <label for="area_id_comentario" class="col-sm-2 col-form-label">Área</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="area_id_comentario" id="area_id_comentario">
                                    <option value="" selected disabled>Seleccione Área</option>
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
    {{-- MODAL --}}

@stop
