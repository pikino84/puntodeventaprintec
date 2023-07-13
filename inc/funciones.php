<?php

function restrinct() {
    session_start();
    if (!isset($_SESSION["rtLogin"])||empty($_SESSION["rtLogin"])) {
        header("Location: /usuario/");
    } else {
        $fechalogin = $_SESSION["ultimoacceso"];
        $ahora = mktime();
        $tiempo = ($ahora - $fechalogin);

        if ($tiempo > 1200000) {
            session_destroy();
            header("Location: /usuario/");
        }
    }
}

function ln(){

    $ln = 'esp';

    if(isset($_SESSION['rtIdioma']));
        $ln = (string)$_SESSION['rtIdioma'];

    return $ln;

}

function limpiarCampo($dirty,$link){
        if (get_magic_quotes_gpc()) {
        $liberate = $link->real_escape_string(stripslashes($dirty));
        }else{
        $liberate = $link->real_escape_string($dirty);
        }
        return $liberate;
}

function getNombreUsuario($link){

    $id = (string)$_SESSION["rtLogin"];
    $nombre = "";

    $ssql = "SELECT nombre as nombre FROM cusuario WHERE usuario='".$id."' AND sta='1'";

    $resultado = $link->query($ssql);

            if($resultado->num_rows>0){

                $resultado->data_seek(0);
                $dt = $resultado->fetch_assoc();

                 $nombre = $dt['nombre'];

            }

    

    return $nombre;

}

function getFolioUltimo($tabla,$link){

    $id = 0;    

    $ssql = "SELECT IFNULL(MAX(folio),1) AS folio FROM $tabla ";

    $res = mysql_query($ssql,$link);

    if(mysql_num_rows($res)>0){
        $dt = mysql_fetch_assoc($res);

        $id = $dt['folio'];
    }

    return $id;

}





function getFecha($fecha){

    $dia = substr($fecha,6,2);
    $mes = substr($fecha,4,2);
    $anio = substr($fecha,0,4);

    return $dia."/".$mes."/".$anio;

}

function getFechaBd($fecha){

    list($dia,$mes,$anio) = explode("/", $fecha);
    

    return $anio.$mes.$dia;

}

function getUrl($cadena){
    $tofind = "À,Á,Â,Ã,Ä,Å,à,á,â,ã,ä,å,Ò,Ó,Ô,Õ,Ö,Ø,ò,ó,ô,õ,ö,ø,È,É,Ê,Ë,è,é,ê,ë,Ç,ç,Ì,Í,Î,Ï,ì,í,î,ï,Ù,Ú,Û,Ü,ù,ú,û,ü,ÿ,Ñ,ñ";
    $replac = "A,A,A,A,A,A,a,a,a,a,a,a,O,O,O,O,O,O,o,o,o,o,o,o,E,E,E,E,e,e,e,e,C,c,I,I,I,I,i,i,i,i,U,U,U,U,u,u,u,u,y,N,n";

    $vectorfind = explode(",",$tofind);
    $vectorreplac = explode(",",$replac);

    for ($i=0;$i<count($vectorfind);$i++)
        $cadena = str_replace($vectorfind[$i],$vectorreplac[$i],$cadena);

    return $cadena;
}

?>