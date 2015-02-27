<?php 
Load::models("formato");
class FormatoController extends AppController{
	public function index(){
		
	}
	public function ver(){
		$formato = new Formato();
		$this->path = "files/uploads/formatos/";
		$this->archivos = $formato->listarFormatos();
	}
	public function nuevo(){
		if (Input::haspost("oculto")) {
			$formato = new Formato();
			$path = $formato->validarCarpeta();
			$archivo = Upload::factory('archivo');//llamamos a la libreria y le pasamos el nombre del campo file del formulario
			$archivo->setPath($path);
	        $archivo->setExtensions(array('doc','docx')); //le asignamos las extensiones a permitir
	        if ($archivo->isUploaded()) { 
	            if ($archivo->save()) {
	                Flash::valid('Archivo subido correctamente.!');
	            }
	        }else{
	                Flash::warning('No se ha Podido Subir el Archivo.!');
	        }
			Router::toAction("ver");
		}

	}
	public function eliminar($nombre_archivo){
		$formato = new Formato();
		$path = $formato->validarCarpeta();
		if (file_exists($path.$nombre_archivo)) {
			if (unlink($path.$nombre_archivo)) {
				Flash::valid("Archivo eliminado");
			}else{
				Flash::error("No se pudo eliminar el Archivo");
			}
		}else{	
			Flash::error("El archivo no existe, no se puede borrar");
		}
		Router::toAction("ver");
	}
	public function leer($nombre_documento){
		$formato = new Formato();
		$path = $formato->validarCarpeta();
		$texto = new Docs($path.$nombre_documento);
		$this->texto = $texto->convertToText();
		
		//$this->texto = $formato->parseWord2($path.$nombre_documento);
		$this->nombre = $nombre_documento;
	}
}

 ?>