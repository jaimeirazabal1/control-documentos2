<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller
{

    final protected function initialize()
    {
    	$roles = array("admin" =>'*',
						"" => array(
									array("user"=>array("miperfil")),
								    array("sesion"=>array("login","logout"))
								    ),

						"publico" => array(
										   array("sesion"=>array("login"))
										  ),
						"comun" => array(
										 array("user"=>array("miperfil")),
										 array("sesion"=>array("login","logout"))
										),
						"reclutador" => array(
											  array("user"=>array("nuevo","miperfil")),
											  array("sesion"=>array("login","logout","index"))
											  )
						);
    	$myacl = new Myacl($roles);
    	$myacl->hacerGuardia();
    }

    final protected function finalize()
    {
        
    }

}
