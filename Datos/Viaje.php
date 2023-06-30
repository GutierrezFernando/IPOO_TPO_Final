<?php 

class Viaje {
    private $idViaje;
    private $vDestino;
    private $vCantMaxPasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $vImporte;
    private $mensajeoperacion;

    public function __construct(){
        $this->idViaje = "";
        $this->vDestino = "";
        $this->vCantMaxPasajeros = "";
        $this->objEmpresa = new Empresa();
        $this->objResponsable = new Responsable();
        $this->vImporte = "";
    }

    public function cargar($idViaje, $vDestino, $vCantMaxPasajeros, $objEmpresa, $objResponsable, $vImporte){		
		$this->setidViaje($idViaje);
		$this->setvDestino($vDestino);
		$this->setvCantMaxPasajeros($vCantMaxPasajeros);
		$this->setobjEmpresa($objEmpresa);
        $this->setobjResponsable($objResponsable);
        $this->setvImporte($vImporte);
    }

    // SETTERS
    public function setidViaje($idViaje){
        $this->idViaje = $idViaje;
    }
    public function setvDestino($vDestino){
        $this->vDestino = $vDestino;
    }
    public function setvCantMaxPasajeros($vCantMaxPasajeros){
        $this->vCantMaxPasajeros = $vCantMaxPasajeros;
    }
    public function setobjEmpresa($objEmpresa){
        $this->objEmpresa = $objEmpresa;
    }
    public function setobjResponsable($objResponsable){
        $this->objResponsable = $objResponsable;
    }
    public function setvImporte($vImporte){
        $this->vImporte = $vImporte;
    }
    public function setmensajeoperacion($mensajeoperacion){
	$this->mensajeoperacion=$mensajeoperacion;
	}

    // GETTERS
    public function getidViaje(){
        return $this->idViaje;
    }
    public function getvDestino(){
        return $this->vDestino;
    }
    public function getvCantMaxPasajeros(){
        return $this->vCantMaxPasajeros;
    }
    public function getobjEmpresa(){
        return $this->objEmpresa;
    }
    public function getobjResponsable(){
        return $this->objResponsable;
    }
    public function getvImporte(){
        return $this->vImporte;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion;
	}

    /**
	 * Recupera los datos de un viaje por idViaje
	 * @param int $idViaje
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idViaje){
		$base=new BaseDatos();
		$consultaViaje="SELECT * FROM viaje WHERE idviaje =".$idViaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){					

					$objEmpresa = new Empresa();
					$objEmpresa->buscar($row2['idempresa']);

					$objResponsable = new Responsable();
					$objResponsable->buscar($row2['rnumeroempleado']);

					$this->cargar($idViaje, $row2['vdestino'], $row2['vcantmaxpasajeros'], $objEmpresa, $objResponsable, $row2['vimporte'] );

					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}

    public static function listar($condicion=""){
	    $arregloViaje = null;
		$base=new BaseDatos();
		$consultaViaje="Select * from viaje ";
		if ($condicion!=""){
		    $consultaViaje=$consultaViaje.' where '.$condicion;
		}
		$consultaViaje.=" order by idviaje ";
		//echo $consultaViaje;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){				
				$arregloViaje= array();
				while($row2=$base->Registro()){
					
					$objViaje= new Viaje();
					$objViaje->Buscar($row2['idviaje']);

					array_push($arregloViaje,$objViaje);
				}
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloViaje;
	}	
	
	public function insertar(){
        $base = new BaseDatos();
        $resp = false; 
        $consultaInsertar = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
        VALUES ('".$this->getvDestino()."',".$this->getvCantMaxPasajeros().",". $this->getobjEmpresa()->getidEmpresa(). ",".$this->getobjResponsable()->getrNumeroEmpleado().",".$this->getvImporte().");";
        if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
            if($id != null){
                $resp = true;
                $this->setidViaje($id);
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
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getvDestino()."', vcantmaxpasajeros='".$this->getvCantMaxPasajeros()."'
                           , idempresa='".$this->getobjEmpresa()->getidEmpresa()."', rnumeroempleado='".$this->getobjResponsable()->getrNumeroEmpleado()."', vimporte='".$this->getvImporte()."' WHERE idviaje=". $this->getidViaje();
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
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getidViaje();
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
        return  "ID Viaje:".$this->getidViaje()."\n".
                "Destino: ".$this->getvDestino()."\n".
                "Cantidad Maxima de Pasajeros: ".$this->getvCantMaxPasajeros()."\n".
                "Empresa: ".$this->getobjEmpresa()->geteNombre()."\n".
                "Responsable: ".$this->getobjResponsable()->getrNombre()." ".$this->getobjResponsable()->getrApellido()."\n".
                "Importe: ".$this->getvImporte()."\n";
    }
}
?>