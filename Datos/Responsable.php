<?php 
include_once "BaseDatos.php";
class Responsable {
    private $rNumeroEmpleado;
    private $rNumeroLicencia;
    private $rNombre;
    private $rApellido;
    private $mensajeoperacion;
  
    public function __construct(){
        $this->rNumeroEmpleado = "";
        $this->rNumeroLicencia = "";
        $this->rNombre = "";
        $this->rApellido = "";
    }

    public function cargar($rNumeroEmpleado,$rNumeroLicencia,$rNombre,$rApellido){		
		$this->setrNumeroEmpleado($rNumeroEmpleado);
		$this->setrNumeroLicencia($rNumeroLicencia);
		$this->setrNombre($rNombre);
		$this->setrApellido($rApellido);
    }

    // SETTERS
    public function setrNumeroEmpleado($rNumeroEmpleado){
        $this->rNumeroEmpleado = $rNumeroEmpleado;
    }
    public function setrNumeroLicencia($rNumeroLicencia){
        $this->rNumeroLicencia = $rNumeroLicencia;
    }
    public function setrNombre($rNombre){
        $this->rNombre = $rNombre;
    }
    public function setrApellido($rApellido){
        $this->rApellido = $rApellido;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}

    // GETTERS
    public function getrNumeroEmpleado(){
        return $this->rNumeroEmpleado;
    }
    public function getrNumeroLicencia(){
        return $this->rNumeroLicencia;
    }
    public function getrNombre(){
        return $this->rNombre;
    }
    public function getrApellido(){
        return $this->rApellido;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    /**
	 * Recupera los datos de un RESPONSABLE por NUMERO DE EMPLEADO
	 * @param int $rNumeroEmpleado
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($rNumeroEmpleado){
		$base=new BaseDatos();
		$consultaResponsable="Select * from responsable where rnumeroempleado=".$rNumeroEmpleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){
				if($row2=$base->Registro()){		
					
					$this->cargar( $rNumeroEmpleado, $row2['rnumerolicencia'], $row2['rnombre'], $row2['rapellido'] );

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
	    $arregloResponsable = null;
		$base=new BaseDatos();
		$consultaResponsable="Select * from responsable ";
		if ($condicion!=""){
		    $consultaResponsable=$consultaResponsable.' where '.$condicion;
		}
		$consultaResponsable.=" order by rnumeroempleado ";
		//echo $consultaResponsable;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){				
				$arregloResponsable= array();
				while($row2=$base->Registro()){
					
					$respo=new Responsable();
					$respo->Buscar($row2['rnumeroempleado']);
					array_push($arregloResponsable,$respo);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloResponsable;
	}	


	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsable(rnumerolicencia, rnombre,  rapellido) 
				VALUES ('".$this->getrNumeroLicencia()."','".$this->getrNombre()."','".$this->getrApellido()."')";
		
		if($base->Iniciar()){

	
            $id = $base->devuelveIDInsercion($consultaInsertar);
            if($id != null){
                $resp = true;
                $this->setrNumeroEmpleado($id);
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
		}


	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE responsable SET rnumerolicencia='".$this->getrNumeroLicencia()."', rnombre='".$this->getrNombre()."'
                           , rapellido='".$this->getrApellido()."' WHERE rnumeroempleado=". $this->getrNumeroEmpleado();
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
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getrNumeroEmpleado();
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
	    return   "\n Numero Empleado: ".$this->getrNumeroEmpleado(). 
                 "\n Numero Licencia:".$this->getrNumeroLicencia().
                 "\n Nombre:".$this->getrNombre().
                 "\n Apellido: ".$this->getrApellido()."\n";
	}
}
?>

