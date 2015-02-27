<?php 
Load::models("user");
Class UserController extends AppController{
	public function nuevo(){
		if (Input::haspost("user")) {
			$nuevo_usuario = new User(Input::post("user"));
			if ($nuevo_usuario->validarPassIguales()) {
				$nuevo_usuario->encriptarClave();
				if ($nuevo_usuario->save()) {
					Flash::valid("Usuario Creado");
            		Input::delete();
				}else{
					Flash::error("No se Creó el usuario");
				}
			}else{
				Flash::info("Las contraaseñas no coinciden");
			}
		}
	}
	public function miperfil(){
		if (Auth::is_valid()) {
			$yo = new User();
			$this->user = $yo->find(Auth::get("id"));
			if (Input::haspost("passanterior","passnueva")) {
				if(!$yo->verificarPassByUserId(Auth::get("id"),Input::post("passanterior"))){
					Flash::error("Contraseña anterior no coincide");
				}else{
					if ($yo->cambiarPass(Auth::get("id"),Input::post("passnueva"))) {
						Flash::valid("Contraseña Cambiada con éxito");
					}else{
						Flash::error("No se Cambio la Contraseña");
					}
				}
			}
		}
	}

}

 ?>