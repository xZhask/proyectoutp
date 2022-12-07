<?php
require_once 'conexion.php';

class ClsHorario
{
    function ListarHorario()
    {
        $sql =
            'SELECT * from horario';
        global $cnx;
        return $cnx->query($sql);
    }
}
