<?php

$servername = "festivalgastronomico.com.mx";
$username = "festiv11_wp746";
$password = "apB86.S)1a";
$dbname = "festiv11_wp746";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


require_once ('./lib/nusoap.php');
$clave = 'LS5590';
$cliente = new nusoap_client('http://srv-sap.dyndns.info/wsExistencias/Service.asmx?WSDL', 'wsdl');
$error = $cliente->getError();
if ($error) {
    echo 'Error' . $error;
}
$parametros = array('Key' => 'czqO2oHBXqdrOVIdiKkyxA==');

$resultado = $cliente->call('GetExistenciaAll', $parametros);

if ($error->fault) {
    echo 'Fallo';
    return 0;
} else {
    $error = $cliente->getError();
    if ($error) {
        echo 'Error' . $error;
        exit();
        return 0;
    } else {
        $rs = explode('{', str_replace('"', "", substr($resultado['GetExistenciaAllResult'], 1, -1)));
        $i = 0;

        $datos = array();
        foreach ($rs as $valor) {

            if ($i != 0) {
                $valor = str_replace("}", "", $valor);
                $valor = explode(",", $valor);
                $existencias = number_format(substr($valor[3], 18), 0);
                $info = substr($valor[1], 13);
                $clave_doblevela = substr($valor[0], 12);
                $precio = number_format(substr($valor[10], 12), 2);
                $estatus = substr($valor[12], 22);
                $info_arr = (explode(" ", $info));
                $count_info = 0;

                foreach ($info_arr as $valor) {

                    switch ($valor) {
                        case'BLANCO';

                            unset($info_arr[$count_info]);

                            break;
                        case'NEGRO';
                            unset($info_arr[$count_info]);
                            break;
                        case'AZUL';
                            unset($info_arr[$count_info]);
                            break;
                        case'ROJO';

                            unset($info_arr[$count_info]);
                            break;
                        case'AMARILLO';
                            unset($info_arr[$count_info]);
                            break;
                        case'VERDE';
                            unset($info_arr[$count_info]);
                            break;
                        case'MARINO';
                            unset($info_arr[$count_info]);
                            break;
                        case'NARANJA';
                            unset($info_arr[$count_info]);
                            break;
                        case'ROSA';
                            unset($info_arr[$count_info]);
                            break;
                        case'CLARO';
                            unset($info_arr[$count_info]);
                            break;
                        case'FOSFORESCENTE';
                            unset($info_arr[$count_info]);
                            break;
                        case'CAFE';
                            unset($info_arr[$count_info]);
                            break;
                        case'NEGRO/BLANCO';
                            unset($info_arr[$count_info]);
                            break;
                        case'(SUSP.)';
                            unset($info_arr[$count_info]);
                            break;
                        case'UNICO';
                            unset($info_arr[$count_info]);
                            break;
                        case'GRIS';
                            unset($info_arr[$count_info]);
                            break;
                        case'ORO';
                            unset($info_arr[$count_info]);
                            break;
                         case'LIMON';
                            unset($info_arr[$count_info]);
                            break;
                        case'PLATA';
                            unset($info_arr[$count_info]);
                            break;
                        case'BEIGE';
                            unset($info_arr[$count_info]);
                            break;
                        case'VINO';
                            unset($info_arr[$count_info]);
                            break;
                        case'COBALTO';
                            unset($info_arr[$count_info]);
                            break;
                        case'LILA';
                            unset($info_arr[$count_info]);
                            break;
                        case'MATE';
                            unset($info_arr[$count_info]);
                            break;
                        case'(BASICA)';
                            unset($info_arr[$count_info]);
                            break;
                        case'(N)';
                            unset($info_arr[$count_info]);
                            break;
                        case'(POLIESTER)';
                            unset($info_arr[$count_info]);
                            break;
                        case'C/RUEDAS';
                            unset($info_arr[$count_info]);
                            break;
                        case'FUERTE';
                            unset($info_arr[$count_info]);
                            break;
                        case'PLATEADO';
                            unset($info_arr[$count_info]);
                            break;
                        case'PLAST';
                            unset($info_arr[$count_info]);
                            break;
                        case'CIELO';
                            unset($info_arr[$count_info]);
                            break;
                        case'PASTEL';
                            unset($info_arr[$count_info]);
                            break;
                        case'PALO';
                            unset($info_arr[$count_info]);
                            break;
                        case'DE';
                            unset($info_arr[$count_info]);
                            break;
                        case'TRANSPARENTE';
                            unset($info_arr[$count_info]);
                            break;
                        case'VIDRIO';
                            unset($info_arr[$count_info]);
                            break;
                        case'MORADO';
                            unset($info_arr[$count_info]);
                            break;
                        case'(PUNTA METALICA)';
                            unset($info_arr[$count_info]);
                            break;

                        case'OSCURO';
                            unset($info_arr[$count_info]);
                            break;
                        case'(CERAMICA)';
                            unset($info_arr[$count_info]);
                            break;
                        case'SOLIDO';
                            unset($info_arr[$count_info]);
                            break;
                        case'(PLANILLA 20 PZAS)';
                            unset($info_arr[$count_info]);
                            break;
                        case'CRAFT';
                            unset($info_arr[$count_info]);
                            break;
                        case'HUMO';
                            unset($info_arr[$count_info]);
                            break;
                    }
                    $count_info++;
                }
                $clave_printec = end($info_arr);
                $info_arr = implode(' ', $info_arr);

                $data = array(
                    'clave_dv' => $clave_doblevela,
                    'precio' => $precio,
                    'info' => $info_arr,
                    'estatus' => $estatus,
                    'existencias' => $existencias
                );

                $sql_where = "SELECT clave_dv FROM cproducto_colores WHERE clave_dv = '" . $clave_doblevela . "';";
                $cotejar = $conn->query($sql_where);
                if ($cotejar->num_rows == 0) {

                    $sql_insert = "INSERT INTO cproducto_colores (clave_dv,info,precio,existencias,hora_actualizacion,estatus,clave_printec) "
                            . "VALUES ('" . $clave_doblevela . "','" . $info . "','" . $precio . "','" . $existencias . "','" . date('Y-m-d h:i:s') . "','" . $estatus . "','" . $clave_printec . "')";
                    if ($conn->query($sql_insert) === TRUE) {
//                     
                    } else {
                        echo "Error:" . $conn->error . $i . '<br><br>';
                    }
                } else {
                    $sql_insert = "UPDATE cproducto_colores "
                            . "SET clave_dv='" . $clave_doblevela . "',info='" . $info . "',precio='" . $precio . "',existencias='" . $existencias . "',hora_actualizacion='" . date('Y-m-d h:i:s') . "',estatus='" . $estatus . "',clave_printec='" . $clave_printec . "'" .
                            " WHERE clave_dv ='" . $clave_doblevela . "';";
                    if ($conn->query($sql_insert) === TRUE) {
                        // echo " update ";
                    } else {
                        echo "Error:" . $conn->error . $i . '<br><br>';
                    }
                }
            }

            $i++;
        }
        exit();
    }
}


