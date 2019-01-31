#!/usr/bin/php -q
<?php
class BackupDatos {
    var $bd_nom;
        var $link;
        var $link2;

    function __construct(){
        //$this->conectar();
       
		$r = $this->obtenerListadoDeArchivos("grive/backupdb/");
		$this->recorrer($r);
	}
  
    function recorrer($r){
		foreach($r as $posicion=>$archivo){
			echo "Recuperando " . $posicion . " base " . $archivo['Nombre'].PHP_EOL;
			$this->backup($archivo['Nombre']);
		}
    }
    function backup($nombre){
        echo "recuparando";
         
        $cadena = "mysql -hhost -uusuario -pclave < $nombre -f";
        system($cadena);
    }
	function obtenerListadoDeArchivos($directorio){
 
	  // Array en el que obtendremos los resultados
	  $res = array();
	 
	  // Agregamos la barra invertida al final en caso de que no exista
	  if(substr($directorio, -1) != "/") $directorio .= "/";
	 
	  // Creamos un puntero al directorio y obtenemos el listado de archivos
	  $dir = @dir($directorio) or die("getFileList: Error abriendo el directorio $directorio para leerlo");
	  while(($archivo = $dir->read()) !== false) {
		  // Obviamos los archivos ocultos
		  if($archivo[0] == ".") continue;
		  if(is_dir($directorio . $archivo)) {
			  $res[] = array(
				"Nombre" => $directorio . $archivo . "/",
				"Tamaño" => 0,
				"Modificado" => filemtime($directorio . $archivo)
			  );
		  } else if (is_readable($directorio . $archivo)) {
			  $res[] = array(
				"Nombre" => $directorio . $archivo,
				"Tamaño" => filesize($directorio . $archivo),
				"Modificado" => filemtime($directorio . $archivo)
			  );
		  }
	  }
	  $dir->close();
	  return $res;
	}
	
}
$x= new BackupDatos();
?>

