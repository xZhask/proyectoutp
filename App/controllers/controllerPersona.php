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
        case 'LISTAR_PERSONAS':
            $tipoPersona = $_POST['tipoPersona'];
            $listadoPersonas = $objPersona->ListarPersonas($tipoPersona);
            $listadoPersonas = $listadoPersonas->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listadoPersonas);
            break;
        case 'REGISTRAR_PERSONA':
            $nro_doc = $_POST['nrodocProf'];
            $infoPersona = $objPersona->BuscarPersona($nro_doc);
            if ($infoPersona->rowCount() > 0) {
                $infoPersona = $infoPersona->fetchAll(PDO::FETCH_OBJ);

                $tipoPersona = $_POST['tipoP'];
                $tipoP = $tipoPersona == 'Profesional' ? 'E' : 'U';
                $datosPersona = [
                    'nro_doc' => $nro_doc,
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
                    echo 'OCURRI?? UN ERROR AL RESGISTRAR';
                }
            } else {
                echo 'DNI YA REGISTRADO';
            }
            break;
        case 'REGISTRAR_PERSONA_P':
            $tipoPersona = $_POST['tipoP'];
            $datosPersona = [
                'nro_doc' => $_POST['nrodocProf'],
                'nombre' => $_POST['nombreProfesional'],
                'fecha_nac' => $_POST['fechaNac'],
                'sexo' => $_POST['sexo'],
                'n_colegiatura' => $_POST['colegiatura'],
                'estado' => '1',
                'tipo_persona' => 'P',
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
                echo 'OCURRI?? UN ERROR AL RESGISTRAR';
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
                echo 'OCURRI?? UN ERROR AL RESGISTRAR';
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
            $infoPersona = $objPersona->BuscarPersona($_POST['nrodoc']);
            $infoPersona = $infoPersona->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($infoPersona);
            break;
    }
}
