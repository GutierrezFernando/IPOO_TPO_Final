<?php
include_once "BaseDatos.php";
 
// el autoincrement hace que tengamos que poner el id_empresa en update y delete, pero no cuando lo insertamos,
//  porque lo hace automaticamente el motor de mysql
class Empresa {
    private $idEmpresa;
    private $eNombre;
    private $eDireccion;
    private $mensajeoperacion;

    public function __construct(){
        $this->idEmpresa = "";
        $this->eNombre = "";
        $this->eDireccion = "";
    }

    public function cargar($idEmpresa,$eNombre,$eDireccion){		
		$this->setidEmpresa($idEmpresa);
		$this->seteNombre($eNombre);
		$this->seteDireccion($eDireccion);
    }

    // SETTERS
    public function setidEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }
    public function seteNombre($eNombre){
        $this->eNombre = $eNombre;
    }
    public function seteDireccion($eDireccion){
        $this->eDireccion = $eDireccion;
    }
    public function setmensajeoperacion($mensajeoperacion){
	$this->mensajeoperacion=$mensajeoperacion;
	}

    // GETTERS
    public function getidEmpresa(){
        return $this->idEmpresa;
    }
    public function geteNombre(){
        return $this->eNombre;
    }
    public function geteDireccion(){
        return $this->eDireccion;
    }
        public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

 	/**
	 * Recupera los datos de un Empresa por idEmpresa
	 * @param int $idEmpresa
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idEmpresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idEmpresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){					
					
					$this->cargar($idEmpresa, $row2['enombre'], $row2['edireccion']);
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
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		$consultaEmpresa.=" order by idempresa ";
		//echo $consultaEmpresa;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){				
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
					
					$idEmpresa=$row2['idempresa'];
					$eNombre=$row2['enombre'];
					$eDireccion=$row2['edireccion'];
				
					$empre=new Empresa();
					$empre->cargar($idEmpresa,$eNombre,$eDireccion);
					array_push($arregloEmpresa,$empre);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloEmpresa;
	}	


	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion)
				VALUES ('".$this->geteNombre()."','".$this->geteDireccion()."')";
		
		if($base->Iniciar()){
            $id = $base->devuelveIDInsercion($consultaInsertar);
			if($id != null){
				$resp=  true;
				$this->setidEmpresa($id);
			}	else {
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
		$consultaModifica="UPDATE empresa SET enombre='".$this->geteNombre()."', edireccion='".$this->geteDireccion()."' WHERE idempresa=". $this->getidEmpresa();
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
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getidEmpresa();
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
        return  "\nID empresa: ".$this->getidEmpresa().
                "\nNombre: ".$this->geteNombre().
                "\nDireccion: ".$this->geteDireccion()."\n";
    }
}
?>