<?php 
header("Content-Type: text/html;charset=utf-8");
require_once("../../db/connection.php");
$xbd = $mysqli;
if(isset($_POST['accion'])){
    if($_POST['accion']=='ini'){  /// Inicio de Compras
        $form = "lis_adm_usuarios.html";
		$usuarios = lis_usu_gen();
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lstadmusuarios]',$usuarios,$contenido);
    }elseif($_POST['accion']=='newusuario'){
        $form = "usuario_nuevo.html";
        $tipos = mysqli_query($xbd, "SELECT * FROM tip_user");
        $option = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($tipos)){
            $option .= "<option value=\"$opciones[id_tip_user]\">$opciones[tip_user]</option>";
        }
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[tipos]',$option,$contenido);
    }elseif($_POST['accion']=='ingnewuser'){
        if(isset($_POST["data"])){
            $cedula = $_POST["data"]["cedula"];
            $validar = mysqli_query($xbd, "SELECT cedula FROM user WHERE cedula = '".$_POST["data"]["cedula"]."' OR user = '".$_POST["data"]["user"]."'");
            if(mysqli_num_rows ($validar)>0){
                $contenido = 1;
            }else{
                $insertar = mysqli_query($xbd, "INSERT INTO user(cedula,nombres,user,password,id_tip_user) VALUES('".$_POST["data"]["cedula"]."','".$_POST["data"]["nombres"]."','".$_POST["data"]["user"]."','".$_POST["data"]["password"]."','".$_POST["data"]["id_tip_user"]."')");
                if($insertar){
                    $contenido = 2;
                }else{
                    $contenido = 3;
                }
            }
        }else{
            $contenido = "No LLEGO";
        }
    }elseif($_POST['accion']=='modificar_usuario'){
        $form = "usuario_modificar.html";
        // $contenido = "Vamos a modificar un usuario".$_POST["id"];
        $contenido=cargar_template("forms/$form");
        $usuario = mysqli_query($xbd, "SELECT * FROM user WHERE cedula = '$_POST[id]'");
        if(mysqli_num_rows ($usuario)>0){
            $datos = mysqli_fetch_assoc($usuario);
            // $cedula = $datos["cedula"];
            $contenido=str_replace('[cedula]',$datos["cedula"],$contenido);
            $contenido=str_replace('[nombres]',$datos["nombres"],$contenido);
            $contenido=str_replace('[user]',$datos["user"],$contenido);
            $contenido=str_replace('[password]',$datos["password"],$contenido);
        }
        $tipos = mysqli_query($xbd, "SELECT * FROM tip_user");
        $option = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($tipos)){
            if(isset($datos["id_tip_user"]) && $datos["id_tip_user"] == $opciones["id_tip_user"] ){
                $option .= "<option value=\"$opciones[id_tip_user]\" selected >$opciones[tip_user]</option>";
            }else{
                $option .= "<option value=\"$opciones[id_tip_user]\">$opciones[tip_user]</option>";
            }
            
        }
        $contenido=str_replace('[tipos]',$option,$contenido);
        
    }elseif($_POST['accion']=='lst_veterinarios'){
        $form = "lis_adm_veterinarios.html";
        $contenido=cargar_template("forms/$form");
	}
    echo $contenido;
}

function lis_usu_gen() {
    global $xbd;
    $sql = "SELECT * FROM user a INNER JOIN tip_user b ON b.id_tip_user = a.id_tip_user ";
    $lista = "<table class=\"table table-hover table-sm\">
            <thead>
            <tr class=\"table-primary\">
                <th class=\"text-center\">Cedula</th>
                <th class=\"text-center\">Nombres</th>
                <th class=\"text-center\">Usuario</th>
                <th class=\"text-center\">Privilegios</th>
                <th class=\"text-center\">Modificar</th>
            </tr>
            </thead><tbody>";
    $usuarios = mysqli_query($xbd, $sql);
    while($usua = mysqli_fetch_assoc($usuarios)){
        $lista .= "<tr>
            <td class=\"text-rigth\">$usua[cedula]</td>
            <td>$usua[nombres]</td>
            <td>$usua[user]</td>
            <td>$usua[tip_user]</td>
            <td class=\"text-center\"><i id=\"$usua[cedula]\" class=\"bi bi-pencil-square edituser\"></i></td>
        </tr>";
    }
    $lista .= "</tbody></table>";
    return $lista; 
}

function cargar_template($archivo){ /// Funcion para Cargar Form
	$grafic='';
	$fp = fopen($archivo,"r");
	while ($linea= fgets($fp,1000)){ 		
		$grafic .= $linea ."\n";
	}
    return $grafic;
}
?>