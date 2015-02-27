<?php


class Myacl{
	/*
		array('role' => *) <- pueda ingresar a todo
		array('empleado' => array(array('precio'=>array("ver","agregar")))) <- rol de 'empleado' puede ingresar a controlador  'precio', vista ver y agregar
	*/
	private $roles;
	private $ruta_de_rebote;
	private $role_publico;

	public function __construct($roles_){
		$this->setRoles($roles_);
		$this->setRutaDeRebote();
		$this->setRolePublico();
	}
	public function setRoles($roles){
		if (is_array($roles)) {
			$this->roles = $roles;
		}
	}
	public function getRoles(){

		return $this->roles;
	}
	public function hacerGuardia(){
		
		$roles_ = $this->getRoles();
		if (Auth::is_valid()) {
			$role_ = $this->getRoleActual();
			if (isset($roles_[$role_])) {
				$permisos_de_rol = $roles_[$role_];
				if ($permisos_de_rol == '*') {
					/*es admin*/
				}else{
					if(!$this->buscarEnRoles($permisos_de_rol) and !$this->buscarEnRoles($roles_[$this->getRolePublico()])){
						Flash::error("Permiso Denegado");
						$this->rebotar();
					}
				}
			}else{
				Flash::error("No existe un permiso para el rol que tienes establecido");
				$this->rebotar();
			}
		}else{
			if(!$this->buscarEnRoles($roles_[$this->getRolePublico()])){
				Flash::error("Permiso Denegado");
				$this->rebotar();
			}
		}
	}
	public function rebotar(){
		$actual = $this->getRutaActual();
		if ($actual['controller']."/".$actual['view'] != $this->getRutaDeRebote()) {
			Router::redirect($this->getRutaDeRebote());
		}
	}
	public function getRoleActual(){
		return Auth::get("role");
	}
	public function getRutaActual(){
		return array("controller"=>Router::get("controller"),"view"=>Router::get("action"));
	}
	public function setRutaDeRebote($ruta = "sesion/login"){
		$this->ruta_de_rebote = $ruta;
	}
	public function getRutaDeRebote(){
		return $this->ruta_de_rebote;
	}
	public function buscarEnRoles($permisos){
		$ruta_actual = $this->getRutaActual();

		for ($i=0; $i < count($permisos) ; $i++) { 
			foreach ($permisos[$i] as $key => $value) {
				/*coincide el controlador con la ruta actual*/
				if ($key == $ruta_actual['controller']) {
					$consiguio_controlador = 1;
					for ($j=0; $j < count($value) ; $j++) { 
						/*coincide la vista actual tambien*/
						if ($value[$j] == $ruta_actual['view']) {
							$consiguio_vista = 1;
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	public function setRolePublico($role_publico = "publico"){
		$roles = $this->getRoles();
		if (isset($roles[$role_publico])) {
			$this->role_publico = $role_publico;
		}else{
			Flash::error("No ha definido un role publico con el nombre de: $role_publico");
		}
	}
	public function getRolePublico(){
		return $this->role_publico;
	}
}

?>