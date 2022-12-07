<?php
require_once 'conexion.php';

class ClsServicio
{
    function ListarTipoServicios()
    {
        $sql =
            'SELECT * FROM tipo_servicio';
        global $cnx;
        return $cnx->query($sql);
    }
    function ListarServicios()
    {
        $sql =
            'SELECT * FROM servicio';
        global $cnx;
        return $cnx->query($sql);
    }
    //SELECT s.id_servicio,s.descripcion as 'servicio', s.precio, ts.descripcion as 'categoria',ts-id_tiposervicio FROM servicio s INNER JOIN tipo_servicio ts ON ts.id_tiposervicio=s.id_tiposervicio
}
