<?php 
Load::models("user");
class Sesion extends ActiveRecord{
	
	public function registrarNuevaEntrada($email = null){
		if ($email) {
			$usuario = new User();
			$usuario_buscado = $usuario->find_first("conditions: email = '$email'");
			$nueva_entrada = new Sesion();
			$nueva_entrada->user_id = $usuario_buscado->id;
			$nueva_entrada->fecha = date("Y-m-d H:i:s");
			return $nueva_entrada->save();
		}
	}
	public function marcarSalidaByUsuarioId($usuario_id){
		$ultima_entrada = $this->find("conditions: user_id = '$usuario_id'","limit: 1","order: fecha desc");
		$ultima_entrada[0]->hora_salida = date("H:i:s");
		return $ultima_entrada[0]->update();
	}
	public function getSesiones(){
		return $this->find("columns: user.email as usuario,sesion.fecha,sesion.hora_salida",
							"join: inner join user on user.id = sesion.user_id");
	}
}

 ?>