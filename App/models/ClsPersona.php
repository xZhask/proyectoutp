<?php
require_once 'conexion.php';

class ClsPersona
{
    function ListarPersonal()
    {
        $sql =
            'SELECT p.id_persona, p.nro_doc, p.nombre, p.n_colegiatura, p.estado, p.tipo_persona,c.telefono,c.email
            FROM persona p INNER JOIN contacto c ON p.id_persona=c.id_persona
            WHERE p.tipo_persona="E"';
        global $cnx;
        return $cnx->query($sql);
    }
    function RegistrarPersona($DatosPersona)
    {
        $sql = 'INSERT INTO persona(nro_doc, nombre, fecha_nac, sexo, n_colegiatura, estado, tipo_persona, id_tipodoc, depart_ubigeo, prov_ubigeo, direccion, pass, foto) VALUES(:nro_doc, :nombre, :fecha_nac, :sexo, :n_colegiatura, :estado, :tipo_persona, :id_tipodoc, :depart_ubigeo, :prov_ubigeo, :direccion, :pass, :foto)';
        global $cnx;
        $parametros = [
            ':nro_doc' => $DatosPersona['nro_doc'],
            ':nombre' => $DatosPersona['nombre'],
            ':fecha_nac' => $DatosPersona['fecha_nac'],
            ':sexo' => $DatosPersona['sexo'],
            ':n_colegiatura' => $DatosPersona['n_colegiatura'],
            ':estado' => $DatosPersona['estado'],
            ':tipo_persona' => $DatosPersona['tipo_persona'],
            ':id_tipodoc' => $DatosPersona['id_tipodoc'],
            ':depart_ubigeo' => $DatosPersona['depart_ubigeo'],
            ':prov_ubigeo' => $DatosPersona['prov_ubigeo'],
            ':direccion' => $DatosPersona['direccion'],
            ':pass' => $DatosPersona['pass'],
            ':foto' => $DatosPersona['foto'],
        ];
        global $cnx;
        $pre = $cnx->prepare($sql);
        if ($pre->execute($parametros)) {
            return $cnx->lastInsertId();
        } else {
            return '0';
        }
    }
    function RegistrarContacto($DatosContacto)
    {
        $sql = 'INSERT INTO contacto(id_persona, telefono, email) VALUES(:id_persona, :telefono, :email)';
        global $cnx;
        $parametros = [
            ':id_persona' => $DatosContacto['id_persona'],
            ':telefono' => $DatosContacto['telefono'],
            ':email' => $DatosContacto['email'],
        ];
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    function BuscarPersona($idPersona)
    {
        $sql = 'SELECT * FROM persona WHERE id_persona= :id_persona';
        global $cnx;
        $parametros = [
            ':id_persona' => $idPersona,
        ];
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
}
