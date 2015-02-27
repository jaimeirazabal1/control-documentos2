
<?php 
class User extends ActiveRecord{
	protected function initialize(){
		$this->validates_uniqueness_of("email");
	}
	public function encriptarClave($string = null){
		if ($string) {
			return md5($string);
		}
		$this->pass = md5($this->pass);
	}
	public function validarPassIguales(){
		$user = Input::post("user");
		return $user["pass"] == $user["repass"];
	}
	public function getByEmail($email){
		return $this->find_first("conditions: email='$email'");
	}
	public function cambiarPass($id_user,$newPass){
		if (!$newPass) {
			Flash::error("No se ingreso una nueva contraseña");
			return false;
		}
		$usuario = $this->find($id_user);
		$usuario->pass = $this->encriptarClave($newPass);
		return $usuario->update();
	}
	public function verificarPassByUserId($id,$pass){
		if (!$pass) {
			Flash::error("No se ingreso una contraseña");
			return false;
		}
		$pass = $this->encriptarClave($pass);
		$usuario = $this->find($id);
		return $pass == $usuario->pass;
	}
}

 ?>