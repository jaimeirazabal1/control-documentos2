<?php 
Load::models("documento");
class DocumentoController extends AppController{

	public function crear(){
		if (Input::haspost("documento")) {
			$nuevoDoc = new Documento(Input::post("documento"));
			if ($nuevoDoc->save()) {
				Flash::valid("Documento Guardado");
			}else{
				Flash::error("No se Guardó el documento");
			}
		}
	}

}


 ?>