<?php
require_once '../models/ClsPersona.php';

$accion = $_POST['accion'];
controlador($accion);

function controlador($accion)
{
    $objPersona = new ClsPersona();

    switch ($accion) {
        case 'CONSULTA_DNI':
            $dni = $_POST['dni'];
            $token =
                'e49fddfa2a41c2c2f26d48840f7d81a66dc78dc2b0e085742a883f0ab0f84158';
            $url = "https://apiperu.dev/api/dni/$dni?api_token=$token";
            $curl = curl_init();
            $header = [];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
            curl_setopt(
                $curl,
                CURLOPT_CAINFO,
                dirname(__FILE__) . '/cacert-2022-07-19.pem'
            );
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo 'cURL Error #:' . $err;
            } else {
                echo $response;
            }
            break;
        case 'LISTAR_PROFESIONALES':
            $tabla = '';
            $listaPersonales = $objPersona->ListarPersonal();
            if ($listaPersonales->rowCount() > 0) {
                while ($fila = $listaPersonales->fetch(PDO::FETCH_NAMED)) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="nvisible">' . $fila['id_persona'] . '</td>';
                    $tabla .= '<td class="txtfelf">' . $fila['nombre'] . '</td>';
                    $tabla .= '<td>' . $fila['nro_doc'] . '</td>';
                    $tabla .= '<td>' . $fila['n_colegiatura'] . '</td>';
                    $tabla .= '<td>' . $fila['telefono'] . '</td>';
                    $tabla .= '<td>' . $fila['email'] . '</td>';
                    $tabla .= '<td><i class="fa-solid fa-user-pen icon-green edit-user"></i></td>';
                    $tabla .= '</tr>';
                }
            } else {
                $tabla .= '<tr><td> NO SE ENCONTRARON REGISTROS </td></tr>';
            }
            echo $tabla;
            break;
        case 'REGISTRAR_PERSONA':
            $tipoPersona = $_POST['tipoP'];
            $tipoP = $tipoPersona == 'Profesional' ? 'E' : 'U';
            $datosPersona = [
                'nro_doc' => $_POST['nrodocProf'],
                'nombre' => $_POST['nombreProfesional'],
                'fecha_nac' => $_POST['fechaNac'],
                'sexo' => $_POST['sexo'],
                'n_colegiatura' => $_POST['colegiatura'],
                'estado' => '1',
                'tipo_persona' => $tipoP,
                'id_tipodoc' => $_POST['tipodoc'],
                'depart_ubigeo' => $_POST['departamento'],
                'prov_ubigeo' => $_POST['provincia'],
                'direccion' => $_POST['direccion'],
                'pass' => $_POST['passw'],
                'foto' => 'C:/',
            ];
            $RegistrarPersona = $objPersona->RegistrarPersona($datosPersona);
            if ($RegistrarPersona !== '0') {
                $datosContacto = [
                    'id_persona' => $RegistrarPersona,
                    'telefono' => $_POST['telefono'],
                    'email' => $_POST['email'],
                ];
                $objPersona->RegistrarContacto($datosContacto);
                echo 'REGISTRADO';
            } else {
                echo 'OCURRIÓ UN ERROR AL RESGISTRAR';
            }
            break;
        case 'EDITAR_PERSONA':
            $datosPersona = [
                'fecha_nac' => $_POST['fechaNac'],
                'sexo' => $_POST['sexo'],
                'n_colegiatura' => $_POST['colegiatura'],
                'estado' => $_POST['EstadoUsuario'],
                'depart_ubigeo' => $_POST['departamento'],
                'prov_ubigeo' => $_POST['provincia'],
                'direccion' => $_POST['direccion'],
                'pass' => $_POST['passw'],
                'foto' => 'C:/',
            ];
            $RegistrarPersona = $objPersona->RegistrarPersona($datosPersona);
            if ($RegistrarPersona !== '0') {
                $datosContacto = [
                    'id_persona' => $RegistrarPersona,
                    'telefono' => $_POST['telefono'],
                    'email' => $_POST['email'],
                ];
                $objPersona->RegistrarContacto($datosContacto);
                echo 'REGISTRADO';
            } else {
                echo 'OCURRIÓ UN ERROR AL RESGISTRAR';
            }
            break;
        case 'CARGAR_SELECT_PROFESIONALES':
            $list = '';
            $listaPersonales = $objPersona->ListarPersonal();
            if ($listaPersonales->rowCount() > 0) {
                while ($fila = $listaPersonales->fetch(PDO::FETCH_NAMED)) {
                    $list .= '<li onclick="SeleccionarItem(' . $fila['id_persona'] . ')" class="item-toggle"id=' . $fila['id_persona'] . '>' . $fila['nombre'] . '</li>';
                }
            } else {
                $list .= '<li class="item-toggle">Sin registros</li>';
            }
            echo $list;
            break;
        case 'BUSCAR_PERSONA':
            $infoPersona = $objPersona->BuscarPersona($_POST['idPersona']);
            $infoPersona = $infoPersona->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($infoPersona);
            break;
    }
}
