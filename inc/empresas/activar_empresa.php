<?php
    session_start();
    require_once '../funciones_bd.php';
    $db = new funciones_BD();

    $fecha = date("d-m-Y g:i A");
    $usuario = $_SESSION["nombre"].' '.$_SESSION["apellido"];
    $id_empresa_user = $_SESSION["id_sucursal"];
    $empresa = $db->GetEmpresaName($id_empresa_user);
	$id_sucursal = $_POST['id_sucursal'];
    $nombre = $db->GetEmpresaName($id_sucursal);

	if($db->activar_sucursal($id_sucursal))
    {
        //Registrando la accion
        $db->RegisterActivity($usuario, $empresa, $nombre, $fecha, 'Activó una Empresa');

        $exito = urlencode("Se activó la empresa");
        header("Location:../../empresas.php?exito=".$exito);
        die;
    }
    else
    {
        $error = urlencode("No se pudo activar la empresa");
        header("Location:../../empresas.php?error=".$error);
        die;
    }
?>