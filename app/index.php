<?php

try{
    $mysqli = mysqli_connect("daw-mysql", "daw_dba", "1234", "daw_db");
    echo "Éxito: Se realizó una conexión apropiada a MySQL!<br />";
    echo "Información del host: " . mysqli_get_host_info($mysqli) . "<br />";

    /* Consultas de selección que devuelven un conjunto de resultados */
    if ($resultado = $mysqli->query("SELECT * FROM `animal`")) {
        echo "La select devolvió " .$resultado->num_rows . " animales:<br />";
        while($obj = $resultado->fetch_object()){
            echo "{$obj->id}: {$obj->nombre}  <br />";
        } 

        /* liberar el conjunto de resultados */
        $resultado->close();
    }
    mysqli_close($mysqli);
}
catch(Exception $exc){
    echo $exc->getMessage() ." linea: " . $exc->getLine() ."<br />";
    echo "Error: No se pudo conectar a MySQL. <br />";
    echo "errno de depuración: " . mysqli_connect_errno() . "<br />";
    echo "error de depuración: " . mysqli_connect_error() . "<br />";
}




phpinfo();