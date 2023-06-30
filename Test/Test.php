<?php 
include_once '../datos/BaseDatos.php';
include_once '../datos/Empresa.php';
include_once '../datos/Responsable.php';
include_once '../datos/Viaje.php';
include_once '../datos/Pasajero.php';

function cargaPrevia(){
	$Empresa = new Empresa();
	$Responsable = new Responsable();
	$Pasajero = new Pasajero();
	$Viaje = new Viaje();
	
	//empresa (idempresa, enombre, edireccion)
	$Empresa->cargar(1, "Zanellato", "Avenida Olascoaga 200");
	$Empresa->insertar();
	//empresa (idempresa, enombre, edireccion)
	$Empresa->cargar(2, "Allaria", "Belgrano 555");
	$Empresa->insertar();

	// responsable (rnumeroempleado, rnumerolicencia, rnombre, rapellido)
	$Responsable->cargar(1, 1, "Marcos", "Maroni");
	$Responsable->insertar();

	// viaje (idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
	$Viaje->cargar(1, "Playa del Carmen", 3, $Empresa, $Responsable, 1999.99);
	$Viaje->insertar();

	// $Pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje);
	$Pasajero->cargar(33123123, "Guadalupe", "Valdez", 299123456, $Viaje);
	$Pasajero->insertar();
	$Pasajero->cargar(44123123, "Juan", "Peralta", 011123123, $Viaje);
	$Pasajero->insertar();
}
    
// funcion que toma un arreglo y lo convierte en un listado con formato string
function mostrarArreglo($arreglo){
	$cant = count($arreglo);
	$cadena ="";
	for($i=0; $i<$cant; $i++){
		$cadena = $cadena."\n".$arreglo[$i]->__toString();
	}
	return $cadena;
}


function primerMenu(){
	cargaPrevia();
	$seguir = true;
	while($seguir){
		lineaRayada();
		echo " MENU PRINCIPAL
		Ingrese opcion: 
		*1* - Menu Empresa 
		*2* - Menu Responsable 
		*3* - Menu Viaje 
		*4* - Menu Pasajero
		*5* - Salir del Programa\n";
		$opcion= trim(fgets(STDIN));

		switch($opcion){
			case 1;
				echo "Opcion seleccionada: *1* - Menu Empresa \n";
				MenuEmpresa();
				break;
			case 2;
				echo "Opcion seleccionada: *2* - Menu Responsable \n";
				menuResponsable();
				break;
			case 3;
				echo "Opcion seleccionada: *3* - Menu Viaje \n";
				menuViaje();
				break;
			case 4;
				echo "Opcion seleccionada: *4* - Menu Pasajero \n";
				menuPasajero();
				break;
			case 5:
				$seguir = false;
				break;
			default:
				echo " Opcion no valida \n";
				break;
		}
	}
	
}

function lineaRayada(){
	echo "\n----------------------------------\n";
}

// menu
function menuEmpresa(){
	$seguir=true;
while($seguir){
	echo 
	"\n Menu Empresa
	\n Ingrese opcion: 
	\n *1* - Cargar  
	\n *2* - Eliminar
	\n *3* - Modificar 
	\n *4* - Ver listado 
	\n *5* - Salir al menu principal \n";
	$rta= trim(fgets(STDIN));
	
	switch($rta){
		case 1: //opcion que crea una nueva empresa
			echo "\nIngrese el nombre de la empresa: \n";
			$nombreEmpresa = trim(fgets(STDIN));

			echo "\nIngrese la direccion: \n";
			$direccionEmpresa = trim(fgets(STDIN));

			$Empresa = new Empresa();
			$cantidad = count($Empresa->listar());
			if($cantidad==0){
				$id = 1;
			} else{
				$id = $cantidad+1;
			}

			$Empresa->cargar($id, $nombreEmpresa, $direccionEmpresa);
			$resultado = $Empresa->insertar();

			if ($resultado){
				lineaRayada();
				echo "La empresa fue cargada con exito\n";
			} else{
				echo "\nLa carga no fue realizada\n";
			}
			echo mostrarArreglo($Empresa->listar());
		break;

		case 2: // opcion que elimina una empresa
			$Empresa = new Empresa();
			echo mostrarArreglo($Empresa->listar());
			lineaRayada();
			echo "Ingrese el ID de la empresa: \n";
			$id = trim(fgets(STDIN));

			if($Empresa->buscar($id)){
				try{
					if ($Empresa->eliminar()) {
						echo "La Empresa fue borrada de la base de datos\n";
					}
				} catch(Exception){
					echo "\n La empresa se encuentra asignada a un viaje, no es posible borrarla.\n";
				}
			} else{
				echo "El id no se encuentra en el listado\n";
			}
		break;

		case 3: //modificar empresa
			$Empresa = new Empresa();
			echo mostrarArreglo($Empresa->listar());
			lineaRayada();
			echo "\nIngrese el id de la empresa a modificar: \n";
			$id = trim(fgets(STDIN));

			if($Empresa->buscar($id)){	
				echo "\n Desea cambiar el nombre ?(S/N):\n";
			$rtaNombre  = trim(fgets(STDIN));
			echo "\n Desea cambiar la direcion ?(S/N):\n";
			$rtaDireccion  = trim(fgets(STDIN));

			if($rtaNombre=="S"){
				echo "Ingrese el nuevo nombre de la empresa\n";
				$nuevoNombre = trim(fgets(STDIN));
				$Empresa->seteNombre($nuevoNombre);
			}
			if($rtaDireccion=="S"){
				echo "Ingrese la nueva direcion de la empresa\n";
				$nuevaDireccion = trim(fgets(STDIN));
				$Empresa->seteNombre($nuevaDireccion);
			}
			$cargada=false;
			// inserto los cambios en la base de datos
			$cargada = $Empresa->modificar();
			if($cargada){
				echo "El cambio fue realizado exitosamente\n";
			} else{
				echo "Los cambios no se pudieron realizar\n";
			}
			echo $Empresa;
			} else{
				echo "El id insertado no se encuentra en la base de datos\n";
			}
			
		break;

		case 4: // ver listado empresas
			$Empresa = new Empresa();
			echo mostrarArreglo($Empresa->listar());
		break;
		
		case 5:
			$seguir=false;
		break;
		default:
			echo "\nOpcion no valida\n";
		break;

	}
}
	
}


	// menu
function menuResponsable(){
	
	echo 
	"\n Menu Responsable
	\nIngrese opcion: 
	\n *1* - Cargar  
	\n *2* - Eliminar
	\n *3* - Modificar 
	\n *4* - Ver listado 
	\n *5* - Salir al menu principal \n";
	$rta= trim(fgets(STDIN));
	
	switch($rta){
		case 1: //opcion que crea un nuevo responsable
			echo "\nIngrese el numero de licencia: \n";
			$numLicencia = trim(fgets(STDIN));

			echo "\nIngrese nombre: \n";
			$nombre = trim(fgets(STDIN));

			echo "\nIngrese apellido: \n";
			$apellido = trim(fgets(STDIN));

			$Responsable = new Responsable();
			$cantidad = count($Responsable->listar());
			if($cantidad==0){
				$id = 1;
			} else{
				$id = $cantidad+1;
			}

			$Responsable->cargar($id, $numLicencia, $nombre, $apellido);
			$resultado = $Responsable->insertar();

			if ($resultado){
				lineaRayada();
				echo "La Responsable fue cargada con exito\n";
			} else{
				echo "\nLa carga no fue realizada\n";
			}
			echo mostrarArreglo($Responsable->listar());
		break;

		case 2: // opcion que elimina un Responsable
			$Responsable = new Responsable();
			echo mostrarArreglo($Responsable->listar());
			lineaRayada();
			echo "\nIngrese el numero de empleado a borrar: \n";
			$id = trim(fgets(STDIN));

			if($Responsable->buscar($id)){
				try{
					if ($Responsable->eliminar()) {
						echo "El Responsable fue borrado de la base de datos\n";
					}
				} catch(Exception){
					echo "\n El Responsable tiene viajes a cargo, no es posible borrarlo.\n";
				}
			} else{
				echo "El id no se encuentra en el listado\n";
			}
		break;

		case 3: //modificar Responsable
			$Responsable = new Responsable();
			echo mostrarArreglo($Responsable->listar());
			lineaRayada();

			echo "\nIngrese el id del Responsable a modificar: \n";
			$id = trim(fgets(STDIN));
			$Responsable->buscar($id);

			echo "\n Desea cambiar el numero de licencia ?(S/N):\n";
			$numLicencia  = trim(fgets(STDIN));
			echo "\n Desea cambiar el nombre ?(S/N):\n";
			$rtaNombre  = trim(fgets(STDIN));
			echo "\n Desea cambiar el apellido ?(S/N):\n";
			$rtaApellido  = trim(fgets(STDIN));

			if($numLicencia=="S"){
				echo "Ingrese el numero de licencia: \n";
				$nuevaLicencia = trim(fgets(STDIN));
				$Responsable->setrNumeroLicencia($nuevaLicencia);
			}
			if($rtaNombre=="S"){
				echo "Ingrese nombre: \n";
				$nuevoNombre = trim(fgets(STDIN));
				$Responsable->setrNombre($nuevoNombre);
			}
			if($rtaApellido=="S"){
				echo "Ingrese el apellido\n";
				$nuevoApellido = trim(fgets(STDIN));
				$Responsable->setrApellido($nuevoApellido);
			}
			$cargada=false;
			// inserto los cambios en la base de datos
			$cargada = $Responsable->modificar();
			if($cargada){
				echo "El cambio fue realizado exitosamente\n";
			} else{
				echo "Los cambios no se pudieron realizar\n";
			}
			echo $Responsable;
		break;

		case 4: // ver listado Responsables
			$Responsable = new Responsable();
			echo mostrarArreglo($Responsable->listar());
		break;

		case 5:
			$seguir=false;
		break;
		default:
			echo "\nOpcion no valida\n";
		break;

	}
}

	// menu
function menuViaje(){
	$seguir=true;
	while($seguir){
		echo 
	"\n Menu Viaje
	\nIngrese opcion: 
	\n *1* - Cargar  
	\n *2* - Eliminar
	\n *3* - Modificar 
	\n *4* - Ver listado 
	\n *5* - Salir al menu principal \n";
	$rta= trim(fgets(STDIN));
	
	switch($rta){
		case 1: //opcion que crea un nuevo Viaje
			echo "\nIngrese el destino: \n";
			$destino = trim(fgets(STDIN));

			echo "\nIngrese cantidad maxima de pasajeros: \n";
			$cantidadMax = trim(fgets(STDIN));

			$Empresa = new Empresa();
			echo mostrarArreglo($Empresa->listar());

			echo "\nIngrese id de la empresa que realiza el viaje: \n";
			$idEmpresa = trim(fgets(STDIN));
			$Empresa->Buscar($idEmpresa);

			$Responsable = new Responsable();
			echo mostrarArreglo($Responsable->listar());

			echo "\nIngrese id del Responsable que realiza el viaje: \n";
			$idResponsable = trim(fgets(STDIN));
			$Responsable->Buscar($idResponsable);

			echo "\nIngrese importe del viaje: \n";
			$importe = trim(fgets(STDIN));

			$Viaje = new Viaje();
			$cantidad = count($Viaje->listar());
			if($cantidad==0){
				$id = 1;
			} else{
				$id = $cantidad+1;
			}

			$Viaje->cargar($id, $destino, $cantidadMax, $Empresa, $Responsable, $importe);
			$resultado = $Viaje->insertar();

			if ($resultado){
				lineaRayada();
				echo "Carga exitosa \n";
			} else{
				echo "\nLa carga no fue realizada\n";
			}
			echo mostrarArreglo($Viaje->listar());
		break;

		case 2: // opcion que elimina un Viaje
			$Viaje = new Viaje();
			// $Pasajero = new Pasajero();
			
			echo mostrarArreglo($Viaje->listar());
			lineaRayada();
			echo "\nIngrese el numero de viaje a borrar: \n";
			$id = trim(fgets(STDIN));
			
			if($Viaje->buscar($id)){
				try{
					if ($Viaje->eliminar()) {
						echo "El Viaje fue borrado de la base de datos\n";
					}
				} catch(Exception){
					echo "\n El Viaje tiene pasajeros cargados, no es posible borrarlo.\n";
				}
			} else{
				echo "El id no se encuentra en el listado\n";
			}
			
			// if($Viaje->Buscar($id)){
			// 	if($Viaje->eliminar()){
			// 		echo "El Viaje fue eliminado de la base de datos\n";
			// 	}else{
			// 		echo "No se pudo realizar la accion\n";
			// 	}
			// } else{
			// 	echo "El numero no se encuentra en el listado\n";
			// }
		break;

		case 3: //modificar Viaje
			$Viaje = new Viaje();
			echo mostrarArreglo($Viaje->listar());
			lineaRayada();
			echo "\nIngrese el id del Viaje a modificar: \n";
			$id = trim(fgets(STDIN));
			$Viaje->buscar($id);

			echo "\nDesea cambiar el destino: (S/N)\n";
			$rtadestino= trim(fgets(STDIN));
			echo "\nDesea cambiar cantidad maxima de pasajeros: (S/N)\n";
			$rtacantidadMax= trim(fgets(STDIN));
			echo "\nDesea cambiar la empresa que realiza el viaje: (S/N)\n";
			$rtaidEmpresa= trim(fgets(STDIN));
			echo "\nDesea cambiar Responsable que realiza el viaje: (S/N)\n";
			$rtaidResponsable= trim(fgets(STDIN));
			echo "\nDesea cambiar importe del viaje: (S/N)\n";
			$rtaimporte= trim(fgets(STDIN));


			if($rtadestino=="S"){
				echo "Ingrese el nuevo destino: \n";
				$newdestino = trim(fgets(STDIN));
				$Viaje->setvDestino($newdestino);
			}
			if($rtacantidadMax=="S"){
				echo "Ingrese cantidad maxima: \n";
				$newcantidadMax = trim(fgets(STDIN));
				$Viaje->setvCantMaxPasajeros($newcantidadMax);
			}
			if($rtaidEmpresa=="S"){
				$Empresa = new Empresa();
			echo mostrarArreglo($Empresa->listar());

			echo "\nIngrese id de la empresa que realiza el viaje: \n";
			$newidEmpresa = trim(fgets(STDIN));
			$Empresa->Buscar($newidEmpresa);
			}

			if($rtaidResponsable=="S"){
				$Responsable = new Responsable();
			echo mostrarArreglo($Responsable->listar());

			echo "\nIngrese id del Responsable que realiza el viaje: \n";
			$newidResponsable = trim(fgets(STDIN));
			$Responsable->Buscar($newidResponsable);
			}
			if($rtaimporte=="S"){
				echo "Ingrese el nuevo importe\n";
				$newimporte = trim(fgets(STDIN));
				$Viaje->setvImporte($newimporte);
			}

			$cargada=false;
			// inserto los cambios en la base de datos
			$cargada = $Viaje->modificar();
			if($cargada){
				echo "El cambio fue realizado exitosamente\n";
			} else{
				echo "Los cambios no se pudieron realizar\n";
			}
			echo $Viaje;
		break;

		case 4: // ver listado Viajes
			$Viaje = new Viaje();
			echo mostrarArreglo($Viaje->listar());
		break;

		case 5:
			$seguir=false;
		break;
		default:
			echo "\nOpcion no valida\n";
		break;

	}

	}
}


	// menu
	function menuPasajero(){
		$seguir=true;
		while($seguir){
			echo 
		"\n Menu Pasajero
	 	\nIngrese opcion: 
		\n *1* - Cargar  
		\n *2* - Eliminar
		\n *3* - Modificar 
		\n *4* - Ver listado 
		\n *5* - Salir al menu principal \n";
		$rta= trim(fgets(STDIN));
		
		switch($rta){
			case 1: //opcion que crea un nuevo Pasajero
				$Pasajero = new Pasajero();
				echo "\nIngrese el documento: \n";
				$documento = trim(fgets(STDIN));
				if($Pasajero->Buscar($documento)){
					echo "El numero de dni ya se encuentra cargado. ";
				} else{
					echo "\nIngrese nombre: \n";
				$nombre = trim(fgets(STDIN));

				echo "\nIngrese apellido: \n";
				$apellido = trim(fgets(STDIN));

				echo "\nIngrese telefono: \n";
				$telefono = trim(fgets(STDIN));
	
				
				echo mostrarArreglo($Pasajero->listar());
	
				echo "\nIngrese id del viaje que realizara el pasajero: \n";
				$idViaje = trim(fgets(STDIN));
				$Pasajero->Buscar($idViaje);
	
				$Pasajero->cargar($documento, $nombre, $apellido, $telefono, $idViaje);
				$resultado = $Pasajero->insertar();
	
				if ($resultado){
					lineaRayada();
					echo "Carga exitosa \n";
				} else{
					echo "\nLa carga no fue realizada\n";
				}
				echo mostrarArreglo($Pasajero->listar());
				}
				
			break;
	
			case 2: // opcion que elimina un Pasajero
				$Pasajero = new Pasajero();
				echo mostrarArreglo($Pasajero->listar());
				lineaRayada();
				echo "\nIngrese el dni del Pasajero a borrar: \n";
				$id = trim(fgets(STDIN));
	
				if($Pasajero->buscar($id)){
					if($Pasajero->eliminar()){
						echo "El Pasajero fue eliminado de la base de datos\n";
					}else{
						echo "No se pudo realizar la accion\n";
					}
				}else{
					echo "El numero no se encuentra en el listado\n";
				}
			break;
	
			case 3: //modificar Pasajero
				$Pasajero = new Pasajero();
				echo mostrarArreglo($Pasajero->listar());
				lineaRayada();
				echo "\nIngrese el dni del Pasajero a modificar: \n";
				$id = trim(fgets(STDIN));
				$Pasajero->buscar($id);
	
				echo "\nDesea cambiar el nombre: (S/N)\n";
				$rtaNombre= trim(fgets(STDIN));
				echo "\nDesea cambiar el apellido: (S/N)\n";
				$rtaApellido= trim(fgets(STDIN));
				echo "\nDesea cambiar telefono: (S/N)\n";
				$rtaTelefono= trim(fgets(STDIN));
				echo "\nDesea cambiar el viaje: (S/N)\n";
				$rtaViaje= trim(fgets(STDIN));

				if($rtaNombre=="S"){
					echo "Ingrese nombre: \n";
					$newNombre = trim(fgets(STDIN));
					$Pasajero->setpNombre($newNombre);
				}
				if($rtaApellido=="S"){
					echo "Ingrese apellido: \n";
					$newApellido = trim(fgets(STDIN));
					$Pasajero->setpApellido($newApellido);
				}
				if($rtaTelefono=="S"){
					echo "Ingrese telefono: \n";
					$newTelefono = trim(fgets(STDIN));
					$Pasajero->setpTelefono($newTelefono);
				}
				if($rtaViaje=="S"){
					$Viaje = new Viaje();
					echo mostrarArreglo($Viaje->listar());
					echo "\nIngrese id del Viaje que realizara el Pasajero: \n";
					$newViaje = trim(fgets(STDIN));
					$Viaje->Buscar($newViaje);
					$Pasajero->setobjViaje($Viaje);
				}
	
				$cargada=false;
				// guardo los datos e inserto los cambios en la base de datos
				$cargada = $Pasajero->modificar();
				if($cargada){
					echo "El cambio fue realizado exitosamente\n";
				} else{
					echo "Los cambios no se pudieron realizar\n";
				}
				echo $Pasajero;
			break;
	
			case 4: // ver listado Pasajeros
				$Pasajero = new Pasajero();
				echo mostrarArreglo($Pasajero->listar());
			break;
	
			case 5:
				$seguir=false;
			break;
			default:
				echo "\nOpcion no valida\n";
			break;
	
		}
	
		}
	}

//***********************************************************************************/
//***********************************************************************************/
//                               PROGRAMA PRINCIPAL 								 /
//***********************************************************************************/
//***********************************************************************************/
//***********************************************************************************/
primerMenu();




