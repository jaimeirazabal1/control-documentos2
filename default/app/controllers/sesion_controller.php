<?php 
Load::models("user","sesion");
class SesionController extends AppController{
	public function login(){
		if (Input::haspost("email","pass","repass")) {
			$user = new User();
			$pass = $user->encriptarClave(Input::post("pass"));
			$email = Input::post("email");
			$auth = new Auth("model", "class: user", "email: $email", "pass: $pass");
            if ($auth->authenticate()) {
            	$nueva_sesion = new Sesion();
            	if(!$nueva_sesion->registrarNuevaEntrada($email)){
            		FLash::info("No se esta registrando la entrada");
            	}
            	Input::delete();
                Flash::valid("Bienvenid@");
                Router::redirect("user/miperfil");
            } else {
                Flash::error("Email o Contraseña erroneos");
            }
		}
	}
	public function logout(){
		if (Auth::is_valid()) {
			$usuario_saliendo = new User();
			$usuario_saliendo_ = $usuario_saliendo->getByEmail(Auth::get("email"));
			//Util::pre($usuario_saliendo_);
			$saliendo = new Sesion();
			if (!$saliendo->marcarSalidaByUsuarioId($usuario_saliendo_->id)) {
				Flash::info("No se esta marcando la salida del usuario");
			}
			Auth::destroy_identity();
		}
		Flash::info("Sesión finalizada");
		Router::redirect("sesion/login");
	}
	public function index(){
		$sesion = new Sesion();
		$this->sesiones = $sesion->getSesiones();
	}
}


 ?>