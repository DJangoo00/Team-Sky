
<?php
session_start();
require_once("../../db/connection.php");
include("../../controller/validarSesion.php");
$sql = "SELECT * FROM user, tip_user WHERE user = '".$_SESSION['usuario']."' AND user.id_tip_user = tip_user.id_tip_user";
$usuarios = mysqli_query($mysqli, $sql);
$usua = mysqli_fetch_assoc($usuarios);
?>

<?php
if ((isset($_POST["guardar"])) && ($_POST["guardar"] == "frm_usu")) 
{
    $tip_us = $_POST['tipusu'];
    $sql_usu = "SELECT * FROM 'tip_user' WHERE tip_user = 'tip_user'";
    $tip = mysqli_querry($mysqli, $sql_usu);
    $row = mysqli_fetch_assoc($tip);
    
    echo $sql_usu; 
    if($row){
        echo '<script>alert ("El usuario ya existe, cambielo");</script>';
        echo '<script>window.location="agregar_usu.php"</script>';
    }	

}


?>
<form method="POST">

    <tr>
        <td colspan='2' align="center"><?php echo $usua['nombres']?></td>
    </tr>
<tr><br>
    <td colspan='2' align="center">
    
    
        <input type="submit" value="Cerrar sesión" name="btncerrar" /></td>
        <input type="submit" formaction="../index.php" value="Regresar" />
    </tr>
</form>

<?php 

if(isset($_POST['btncerrar']))
{
	session_destroy();

   
    header('location: ../../index.html');
}
	
?>

</div>

</div>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Agregar Tipo de Usuario</title>
</head>
    <body>
        <section class="title">
        <h1><?php echo $usua['tip_user']?> | Formulario para agregar tipo de usuario</h1>
        </section>
        <table border="1" class="center">
            <form name="frm_usuario" method="POST" autocomplete="off">
                <tr>
                    <th colspan="2">Tipos de usuario</th>
                </tr>
                <tr>
                    <th><label>Identificador</label></th>
                    <th><input type="text" placeholder="Id" readonly></th>
                </tr>
                <tr>
                    <th><label>Usuario</label></th>
                    <th><input type="text" name="tipusu" placeholder="Ingresar tipo de usuario"></th>
                </tr>
                <tr>
                    <th colspan="2">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="2"><input type="submit"value="Guardar" name="btn_guardar"></th>
                    <input type="hidden" name="guardar" value="frm_usuario">
                </tr>
    </body>
</html>