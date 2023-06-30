<?php 
class Pasajero {
    private $pDocumento;
    private $pNombre;
    private $pApellido;
    private $pTelefono;
    private $objViaje;
    private $mensajeoperacion;

    public function __construct(){
        $this->pDocumento = "";
        $this->pNombre = "";
        $this->pApellido = "";
        $this->pTelefono = "";
        $this->objViaje = new Viaje();
    }

    public function cargar($pDocumento,$pNombre,$pApellido,$pTelefono, $objViaje){		
		$this->setpDocumento($pDocumento);
		$this->setpNombre($pNombre);
		$this->setpApellido($pApellido);
		$this->setpTelefono($pTelefono);
        $this->setobjViaje($objViaje);
    }

    // SETTERS
    public function setpDocumento($pDocumento){
        $this->pDocumento = $pDocumento;
    }
    public function setpNombre($pNombre){
        $this->pNombre = $pNombre;
    }
    public function setpApellido($pApellido){
        $this->pApellido = $pApellido;
    }
    public function setpTelefono($pTelefono){
        $this->pTelefono = $pTelefono;
    }
    public function setobjViaje($objViaje){
        $this->objViaje = $objViaje;
    }
    public function setmensajeoperacion($mensajeoperacion){
	$this->mensajeoperacion=$mensajeoperacion;
	}

    // GETTERS
    public function getpDocumento(){
        return $this->pDocumento;
    }
    public function getpNombre(){
        return $this->pNombre;
    }
    public function getpApellido(){
        return $this->pApellido;
    }
    public function getpTelefono(){
        return $this->pTelefono;
    }
    public function getobjViaje(){
        return $this->objViaje;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

     /**
	 * Recupera los datos de un Pasajero por DNI
	 * @param int $pDocumento
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($pDocumento){
		$base=new BaseDatos();  
		$consultaPasajero="SELECT * FROM pasajero WHERE pdocumento=".$pDocumento;
		$resp= false;
		if($base->Iniciar()){ // conecta con el servidor y base de datos mysql, retorna true si la coneccion se pudo establecer, sino falso
			if($base->Ejecutar($consultaPasajero)){ // Ejecuta una consulta en la Base de Datos. recibe un parametro string, retorna boolean
				if($fila=$base->Registro()){ // Devuelve una fila de la tabla donde hacemos la consulta, y el puntero se desplaza a la sgte fila
				    
					$objViaje = new Viaje();
					$objViaje->Buscar($fila['idviaje']);
					
					$this->cargar( $pDocumento, $fila['pnombre'], $fila['papellido'], $fila['ptelefono'], $objViaje);
				
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		} else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		}		
	return $resp;
	}

    public static function listar($condicion=""){
	    $arregloPasajero = null;
		$base=new BaseDatos();
		$consultaPasajero="SELECT * FROM pasajero ";
		if ($condicion!=""){
		    $consultaPasajero=$consultaPasajero.' WHERE '.$condicion;
		}
		$consultaPasajero.=" ORDER BY pdocumento ";
		//echo $consultaPasajero;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){				
				$arregloPasajero= array();
				while($row2=$base->Registro()){

					$objPasajero= new Pasajero();
					$objPasajero->Buscar($row2['pdocumento']);
					array_push($arregloPasajero,$objPasajero);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPasajero;
	}	


	
	public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje)
        VALUES (".$this->getpDocumento().", '".$this->getpNombre()."', '".$this->getpApellido()."', '".$this->getpTelefono()."', '".$this->getobjViaje()->getidViaje()."')";
	
        if($base->Iniciar()){
            if($base->Ejecutar($consultaInsertar)){
                $resp = true;
            }else{
                $this->setMensajeOperacion($base->getError()); 
            }

        }else{
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

	
	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();

		$consultaModifica= "UPDATE pasajero 
							SET pnombre='".$this->getpNombre()."',
								papellido='".$this->getpApellido()."',
								ptelefono='".$this->getpTelefono()."',
								idviaje='". $this->getobjViaje()->getidViaje()."'
							WHERE pdocumento=". $this->getpDocumento();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra= "DELETE FROM pasajero WHERE pdocumento=".$this->getpDocumento();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

    public function __toString(){
        return  "Documento:".$this->getpDocumento()."\n".
                "Nombre: ".$this->getpNombre()."\n".
                "Apellido: ".$this->getpApellido()."\n".
                "Telefono: ".$this->getpTelefono()."\n".
                "ID Viaje ". $this->getobjViaje()->getidViaje() ."\n";
    }
}
?>
