<?php
require_once('inc/header.php');
require_once('inc/funciones_bd.php');
$db = new funciones_BD();
?>

<!--Div que viene de header_admin2-->
<div id="overlay">
    <div class="spinner"></div> 
</div>

    <div class="breadcrumbs">
        <div class="col-sm-12">
            <div class="page-header">
                <div class="page-title text-center">
                    <h1 class="text-muted">Documentos más vistos</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="row">
            <div class="col-md-12">
                <br/>

                <div class="panel panel-primary mt-3">
                    <table id="tabla_documentos" class="table table-striped table-bordered table-responsive-sm">
                        <thead>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th class="text-center">Empresas</th>
                            <th class="text-center">Categorías</th>
                            <th width="20" class="text-center">Vistas</th>
                        </thead>
                        <tbody id="tabla" class="pb-3">
                            <?php
                            $documentos = $db->GetMostViewedDocument($sin_limite = 0);
                            ?>

                            <?php foreach ($documentos as $documento)
                            { 
                                $sucursales = $db->GetAsignacionesDocument($documento['id_documento']);
                                ?>
                                <tr>
                                    <td style="vertical-align: middle;"><?=$documento['nombre']; ?></td>
                                    <td style="vertical-align: middle;"><?=$documento['descripcion']; ?></td>
                                    <td style="vertical-align: middle;" class="text-center">
                                        <?php 
                                        $sucursal='';
                                        if(!empty($sucursales)) { 
                                            foreach ($sucursales as $empresa) 
                                            {
                                                if($sucursal != $empresa['nombre']) 
                                                {
                                                    $sucursal = $empresa['nombre'];
                                                    echo $sucursal.'&nbsp;<br>';
                                                }
                                            }
                                        } else { echo '-'; } ?>
                                    </td>
                                    <td style="vertical-align: middle;" class="text-center">
                                        <?php if(!empty($sucursales)) { 
                                            foreach ($sucursales as $empresa) {
                                        echo $empresa['descripcion'].'&nbsp;<br>'; } 
                                    } else { echo '-'; } ?></td>
                                    <td style="vertical-align: middle;" class="text-center"><?=$documento['vistas']; ?></td>  
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                 <!--<br><p><b>ID de usuarios seleccionados: </b></p>
                 <pre id="example-console-rows"></pre>-->
            <br/>
            </div>
        </div>
    </div>

<!--Div que viene de header_admin2-->
</div>

<?php include 'inc/const.php' ?>

<script>
$(document).ready(function() 
{
    var table = $('#tabla_documentos').DataTable({
        order: [[4, 'desc']],
        dom: 'Bfrtip',
        paging: true,
        autoWidth: true,
        buttons: [
            {
                extend: 'pdfHtml5',
                className: 'btn btn-danger btn-pdf',
                //download: 'open',
                text: '<i class="fa fa-download"></i>&nbsp;&nbsp;Generar PDF',
                filename: 'Documentos más vistos',
                orientation: 'portrait', //portrait
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible',
                    search: 'applied',
                    order: 'applied'
                },
                customize: function (doc) {
                    //Remove the title created by datatTables
                    doc.content.splice(0,1);
                    doc.pageMargins = [30,60,30,40];
                    doc.styles.tableHeader.fontSize = 10;
                    var now = new Date();
                    var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();

                    // Header del PDF
                    doc['header']=(function() {
                        return {
                            columns: [
                                {
                                    alignment: 'center',
                                    fontSize: 16,
                                    text: 'Documentos más vistos'
                                }
                            ],
                            margin: 20
                        }
                    });

                    // Footer del PDF
                    doc['footer']=(function(page, pages) {
                        return {
                            columns: [
                                'Fecha: '+jsDate,
                                {
                                    // This is the right column
                                    alignment: 'right',
                                    text: ['Página ', { text: page.toString() },  ' de ', { text: pages.toString() }]
                                }
                            ],
                            margin: [10, 0]
                        }
                    });

                    var objLayout = {};
                    objLayout['hLineWidth'] = function(i) { return .5; };
                    objLayout['vLineWidth'] = function(i) { return .5; };
                    objLayout['hLineColor'] = function(i) { return '#aaa'; };
                    objLayout['vLineColor'] = function(i) { return '#aaa'; };
                    objLayout['paddingLeft'] = function(i) { return 4; };
                    objLayout['paddingRight'] = function(i) { return 4; };
                    doc.content[0].layout = objLayout;
                }
            },
            {
                extend: 'excelHtml5',
                className: 'btn btn-success btn-pdf',
                text: '<i class="fa fa-download"></i>&nbsp;&nbsp;Generar EXCEL',
                filename: 'Documentos más vistos'
            },
        ],  
        language:
        {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": 
            {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": 
            {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });

});

</script>

</body>
