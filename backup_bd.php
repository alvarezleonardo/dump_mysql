#!/usr/bin/php -q
<?php

$hstori = 'direccion host';

$usrori = 'usuario admin bd';

$pwdori = 'clave ';

$conori = mysqli_connect($hstori, $usrori, $pwdori);

if ($conori){
    $cadbin = "/usr/bin/mysqldump ";
    $cadopt = "--triggers --routines -f --opt --skip-quote-names --skip-add-drop-table --skip-add-drop-database --default-character-set=latin1  --quick -B -u".$usrori." -p".$pwdori." --max-allowed-packet=1G -h ".$hstori. " ";
    
    $qrydbs = "select schema_name from information_schema.schemata where right(schema_name, 6) <> 'schema'";
    echo "Obteniendo lista de bases a resguardar...";
    $resdbs = mysqli_query($conori, $qrydbs);
    if ($resdbs){
	system("rm /home/grive/backupdb/*.sql");
	echo "Ok (".mysqli_num_rows($resdbs)." bases)\r\n";
	for ($i = 0; $i < mysqli_num_rows($resdbs); $i++){
	    echo date('d-m-Y H:i:s')." - backup ".trim(mysqli_result($resdbs, $i, 0))." -----------------------------------------------------\r\n";
	    echo "Exportando...";
	    system($cadbin . $cadopt . trim(mysqli_result($resdbs, $i, 0)) . " > /home/grive/backupdb/".trim(mysqli_result($resdbs, $i, 0)).".sql");
	}

	system("rm /home/grive/web.zip");
	system("zip /home/grive/web.zip -r /var/www/html");
	system("rm /home/backup_".date("w").".zip");
	system("zip /home/backup_".date("w").".zip -r /home/grive");

		echo " - Finalizado.\r\n";
    }else{
		echo "Error -> ".mysql_error()."\r\n";
    }
}else{
    echo "No conecta".$conori;
}


function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}


?>
