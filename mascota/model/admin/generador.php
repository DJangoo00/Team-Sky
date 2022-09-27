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
        $contenido=cargar_template("forms/$form");
        $usuario = mysqli_query($xbd, "SELECT * FROM user WHERE cedula = '$_POST[id]'");
        if(mysqli_num_rows ($usuario)>0){
            $datos = mysqli_fetch_assoc($usuario);
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
    }elseif($_POST['accion']=='actualizar_usuario'){
        if(isset($_POST["data"])){
            $modificar = true;
            if($_POST["data"]["moduser"] == 'true'){
                $validar = mysqli_query($xbd, "SELECT cedula FROM user WHERE user = '".$_POST["data"]["user"]."'");
                if(mysqli_num_rows ($validar)>0){
                    $modificar = false;
                    $contenido = 1;
                }
            }
            if($modificar){
                $actualizar = mysqli_query($xbd, "UPDATE user SET nombres ='".$_POST["data"]["nombres"]."',user = '".$_POST["data"]["user"]."',password = '".$_POST["data"]["password"]."',id_tip_user = '".$_POST["data"]["id_tip_user"]."' WHERE cedula ='".$_POST["data"]["cedula"]."'");
                if($actualizar){
                    $contenido = 2;
                }else{
                    $contenido = 3;
                }
            }            
        }else{
            $contenido = "No LLEGO";
        }
    }elseif($_POST['accion']=='lst_veterinarios'){
        $form = "lis_adm_veterinarios.html";
        $veterinarios = lis_vet_gen();
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lstadmveterinarios]',$veterinarios,$contenido);
	}elseif($_POST['accion']=='newveterinario'){
        $form = "veterinario_nuevo.html";
        $contenido=cargar_template("forms/$form");
    }elseif($_POST['accion']=='ingnewvet'){
        if(isset($_POST["data"])){
            $validar = mysqli_query($xbd, "SELECT professional_lic FROM veterinarian WHERE professional_lic = '".$_POST["data"]["professional_lic"]."'");
            if(mysqli_num_rows ($validar)>0){
                $contenido = 1;
            }else{
                $insertar = mysqli_query($xbd, "INSERT INTO veterinarian(name_vet,lastname_vet,telephone_vet,address_vet,professional_lic) VALUES('".$_POST["data"]["name_vet"]."','".$_POST["data"]["lastname_vet"]."','".$_POST["data"]["telephone_vet"]."','".$_POST["data"]["address_vet"]."','".$_POST["data"]["professional_lic"]."')");
                if($insertar){
                    $contenido = 2;
                }else{
                    $contenido = 3;
                }
            }
        }else{
            $contenido = "No LLEGO";
        }
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

function lis_vet_gen() {
    global $xbd;
    $sql = "SELECT * FROM veterinarian";
    $lista = "<table class=\"table table-hover table-sm\">
            <thead>
            <tr class=\"table-primary\">
                <th class=\"text-center\">Nombre</th>
                <th class=\"text-center\">Apellidos</th>
                <th class=\"text-center\">Teléfono</th>
                <th class=\"text-center\">Dirección</th>
                <th class=\"text-center\">Licencia</th>
                <th class=\"text-center\">Modificar</th>
            </tr>
            </thead><tbody>";
    $veterinarios = mysqli_query($xbd, $sql);
    while($vet = mysqli_fetch_assoc($veterinarios)){
        $lista .= "<tr>
            <td class=\"text-rigth\">$vet[name_vet]</td>
            <td>$vet[lastname_vet]</td>
            <td>$vet[telephone_vet]</td>
            <td>$vet[address_vet]</td>
            <td>$vet[professional_lic]</td>
            <td class=\"text-center\"><i id=\"$vet[id_vet]\" class=\"bi bi-pencil-square editveterinario\"></i></td>
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