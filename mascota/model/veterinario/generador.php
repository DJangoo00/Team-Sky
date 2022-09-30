<?php 
header("Content-Type: text/html;charset=utf-8");
require_once("../../db/connection.php");
$xbd = $mysqli;
if(isset($_POST['accion'])){
    if($_POST['accion']=='ini'){  /// 
        $form = "lis_mascotas.html";
		$mascota = lis_mascotas_gen();
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lis_mascotas]',$mascota,$contenido);
    }elseif($_POST['accion']=='lst_owner'){
        $form = "lis_adm_owner.html";
        $owner = lis_owner_gen();
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lis_adm_owner]',$owner,$contenido);
	}elseif($_POST['accion']=='newowner'){
        $form = "owner_nuevo.html";
        $contenido=cargar_template("forms/$form");
    }elseif($_POST['accion']=='ingnewown'){
        if(isset($_POST["data"])){
            $validar = mysqli_query($xbd, "SELECT id_owner FROM owner WHERE id_owner = '".$_POST["data"]["id_owner"]."'");
            if(mysqli_num_rows ($validar)>0){
                $contenido = 1;
            }else{
                $insertar = mysqli_query($xbd, "INSERT INTO owner(id_owner,name,lastname,telephone,address,email) VALUES('".$_POST["data"]["id_owner"]."','".$_POST["data"]["name"]."','".$_POST["data"]["lastname"]."','".$_POST["data"]["telephone"]."','".$_POST["data"]["address"]."','".$_POST["data"]["email"]."')");
                if($insertar){
                    $contenido = 2;
                }else{
                    $contenido = 3;
                }
            }
        }else{
            $contenido = "No LLEGO";
        }
    }elseif($_POST['accion']=='lst_visitas'){
        $form = "lis_adm_visitas.html";
        $visitas = lis_visitas_gen();
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lis_visitas_gen]',$visitas,$contenido);
	}elseif($_POST['accion']=='newvisit'){
        $form = "visita_nueva.html";
        $veterinarios = mysqli_query($xbd, "SELECT * FROM veterinarian");
        $veter = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($veterinarios)){
            $veter .= "<option value=\"$opciones[id_vet]\">$opciones[name_vet] $opciones[lastname_vet]</option>";
        }
        $pets = mysqli_query($xbd, "SELECT * FROM pet");
        $option = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($pets)){
            $option .= "<option value=\"$opciones[id_pet]\">$opciones[name_pet] -- $opciones[breed]</option>";
        }
        $medicinas = mysqli_query($xbd, "SELECT * FROM medicines");
        $medic = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($medicinas)){
            $medic .= "<option value=\"$opciones[id_medicine]\">$opciones[medicine_name] -- $opciones[type]</option>";
        }
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lista_veterinarios]',$veter,$contenido);
        $contenido=str_replace('[lista_mascotas]',$option,$contenido);
        $contenido=str_replace('[lista_medicinas]',$medic,$contenido);
    }elseif($_POST['accion']=='ingnewvisit'){
        if(isset($_POST["data"])){
            $insertar = mysqli_query($xbd, "INSERT INTO vet_visit(id_vet,id_pet,temperature,weight,breathing_freq,heart_rate,visit_date,recommendations) VALUES('".$_POST["data"]["id_vet"]."','".$_POST["data"]["id_pet"]."','".$_POST["data"]["temperature"]."','".$_POST["data"]["weight"]."','".$_POST["data"]["breathing_freq"]."','".$_POST["data"]["heart_rate"]."','".$_POST["data"]["visit_date"]."','".$_POST["data"]["recommendations"]."')");
            if($insertar){
                $id_visit = $mysqli->insert_id;
                $data= $_POST["data"]["medicine"];
                foreach ($data as $key) {
                    //echo $key["id_medicine"];
                    $ins_medicines = mysqli_query($xbd, "INSERT INTO visit_prescription(id_visit,id_medicine,medicine_dosage,amount) VALUES('$id_visit','".$key["id_medicine"]."','".$key["medicine_dosage"]."','".$key["amount"]."')");
                }
                $contenido = 1;
            }else{
                $contenido = 2;
            }
        }else{
            $contenido = "No LLEGO";
        }
    }elseif($_POST['accion']=='newmascota'){
        $form = "mascota_nuevo.html";
        $owners = mysqli_query($xbd, "SELECT * FROM owner");
        $option = "<option value=\"--\">--</option>";
        while($opciones = mysqli_fetch_assoc($owners)){
            $option .= "<option value=\"$opciones[id_owner]\">$opciones[name] $opciones[lastname]</option>";
        }
        $contenido=cargar_template("forms/$form");
        $contenido=str_replace('[lst_owners]',$option,$contenido);
    }elseif($_POST['accion']=='ingnewpet'){
        if(isset($_POST["data"])){
            $validar = mysqli_query($xbd, "SELECT id_owner FROM pet WHERE id_owner = '".$_POST["data"]["id_owner"]."' AND name_pet = '".$_POST["data"]["name_pet"]."'");
            if(mysqli_num_rows ($validar)>0){
                $contenido = 1;
            }else{
                $insertar = mysqli_query($xbd, "INSERT INTO pet(id_owner,name_pet,color,species,breed) VALUES('".$_POST["data"]["id_owner"]."','".$_POST["data"]["name_pet"]."','".$_POST["data"]["color"]."','".$_POST["data"]["species"]."','".$_POST["data"]["breed"]."')");
                if($insertar){
                    $contenido = 2;
                }else{
                    $contenido = 3;
                }
            }
        }else{
            $contenido = "No LLEGO";
        }
    }elseif($_POST['accion']=='Cerrar'){
        session_destroy();
        header('location: ../../index.html');
    }
    echo $contenido;
}

function lis_mascotas_gen() {
    global $xbd;
    $sql = "SELECT * FROM pet p INNER JOIN owner o ON o.id_owner = p.id_owner";
    $lista = "<table class=\"table table-hover table-sm\">
            <thead>
            <tr class=\"table-primary\">
                <th class=\"text-center\">Mascota</th>
                <th class=\"text-center\">Dueño</th>
                <th class=\"text-center\">Color</th>
                <th class=\"text-center\">Especie</th>
                <th class=\"text-center\">Raza</th>
                <th class=\"text-center\">Modificar</th>
            </tr>
            </thead><tbody>";
    $mascotas = mysqli_query($xbd, $sql);
    while($pet = mysqli_fetch_assoc($mascotas)){
        $lista .= "<tr>
            <td class=\"text-rigth\">$pet[name_pet]</td>
            <td>$pet[name] $pet[lastname]</td>
            <td>$pet[color]</td>
            <td>$pet[species]</td>
            <td>$pet[breed]</td>
            <td class=\"text-center\"><i id=\"$pet[id_pet]\" class=\"bi bi-pencil-square edituser\"></i></td>
        </tr>";
    }
    $lista .= "</tbody></table>";
    return $lista; 
}

function lis_owner_gen() {
    global $xbd;
    $sql = "SELECT * FROM owner";
    $lista = "<table class=\"table table-hover table-sm\">
            <thead>
            <tr class=\"table-primary\">
                <th class=\"text-center\">Identificación</th>
                <th class=\"text-center\">Nombre</th>
                <th class=\"text-center\">Apellidos</th>
                <th class=\"text-center\">Teléfono</th>
                <th class=\"text-center\">Dirección</th>
                <th class=\"text-center\">Correo</th>
                <th class=\"text-center\">Modificar</th>
            </tr>
            </thead><tbody>";
    $owners = mysqli_query($xbd, $sql);
    while($owner = mysqli_fetch_assoc($owners)){
        $lista .= "<tr>
            <td class=\"text-rigth\">$owner[id_owner]</td>
            <td>$owner[name]</td>
            <td>$owner[lastname]</td>
            <td>$owner[telephone]</td>
            <td>$owner[address]</td>
            <td>$owner[email]</td>
            <td class=\"text-center\"><i id=\"$owner[id_owner]\" class=\"bi bi-pencil-square editowner\"></i></td>
        </tr>";
    }
    $lista .= "</tbody></table>";
    return $lista; 
}
function lis_visitas_gen() {
    global $xbd;
    $sql = "SELECT vv.id_visit,vv.visit_date,CONCAT(v.name_vet,' ',v.lastname_vet) nombreVeterinario, p.name_pet, vv.temperature, vv.weight, 
                vv.breathing_freq
            FROM vet_visit vv INNER JOIN veterinarian v ON v.id_vet=vv.id_vet
            INNER JOIN pet p ON p.id_pet = vv.id_pet
            ORDER BY vv.id_visit DESC";
    $lista = "<table class=\"table table-hover table-sm\">
            <thead>
            <tr class=\"table-primary\">
                <th class=\"text-center\">No. Visita</th>
                <th class=\"text-center\">Fecha</th>
                <th class=\"text-center\">Nombre Veterinario</th>
                <th class=\"text-center\">Mascota</th>
                <th class=\"text-center\">Temp °</th>
                <th class=\"text-center\">Peso</th>
                <th class=\"text-center\">FC</th>
                <th class=\"text-center\">Modificar</th>
            </tr>
            </thead><tbody>";
    $visitas = mysqli_query($xbd, $sql);
    while($visita = mysqli_fetch_assoc($visitas)){
        $lista .= "<tr>
            <td class=\"text-rigth\">$visita[id_visit]</td>
            <td>$visita[visit_date]</td>
            <td>$visita[nombreVeterinario]</td>
            <td>$visita[name_pet]</td>
            <td>$visita[temperature] °</td>
            <td>$visita[weight] Kg.</td>
            <td>$visita[breathing_freq] l/m</td>
            <td class=\"text-center\"><i id=\"$visita[id_visit]\" class=\"bi bi-pencil-square editowner\"></i></td>
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