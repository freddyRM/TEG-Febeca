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
                    <h1 class="text-muted">Vídeos más vistos</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="row">
            <div class="col-md-12">
                <br/>

                <div class="panel panel-primary mt-3">
                    <table id="tabla_videos" class="table table-striped table-bordered table-responsive-sm">
                        <thead>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Empresas</th>
                            <th class="text-center">Categorías</th>
                            <th class="text-center">Vistas</th>
                        </thead>
                        <tbody id="tabla" class="pb-3">
                            <?php
                            $videos = $db->GetMostViewedVideos(1,false);
                            ?>
                            <?php 
                                foreach ($videos as $video) {
                                $sucursales = $db->GetAsignacionesVideos($video['id_video']);
                            ?>
                                <tr>
                                    <td style="vertical-align: middle;"><?=$video['nombre']; ?></td>
                                    <td style="vertical-align: middle;"><?=$video['descripcion']; ?></td>
                                    <td style="vertical-align: middle;" class="text-center">
                                        <?php 
                                        $sucursal='';
                                        foreach ($sucursales as $empresa) 
                                        {
                                            if($sucursal != $empresa['nombre']) 
                                            {
                                                $sucursal = $empresa['nombre'];
                                                echo $sucursal.'&nbsp;<br>';
                                            }
                                        } ?>
                                    </td>
                                    <td style="vertical-align: middle;" class="text-center"><?php foreach ($sucursales as $empresa) {
                                        echo $empresa['descripcion'].'&nbsp;<br>';
                                    } ?></td>
                                    <td style="vertical-align: middle;" class="text-center"><?=$video['vistas']; ?></td>
                                </tr>
                            <?php }?>
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
    var table = $('#tabla_videos').DataTable({
        order: [[4, 'desc']],
        dom: 'Bfrtip',
        paging: true,
        autoWidth: true,
        buttons: [
            {
                extend: 'pdfHtml5',
                className: 'btn btn-success btn-pdf',
                //download: 'open',
                text: '<i class="fa fa-download"></i>&nbsp;&nbsp;Generar PDF',
                filename: 'videos_mas_vistos',
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
                    doc['header']=(function() {
                        return {
                            columns: [
                                {
                                    alignment: 'center',
                                    fontSize: 16,
                                    text: 'Vídeos más vistos'
                                }
                            ],
                            margin: 20
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
            }
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
