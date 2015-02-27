<?php 
class Formato extends ActiveRecord{
	public function verCarpeta(){

	}
	public function listarFormatos(){
		$path = $this->validarCarpeta();
		$directorio = opendir($path); //ruta actual
		$archivos = array();
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
		    if (is_dir($archivo))//verificamos si es o no un directorio
		    {
		    	if ($archivo != "." and $archivo != "..") {
		        	echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
		    	}
		    }
		    else
		    {
		    	$string = explode("_",$archivo);
		    	$archivos[]=$archivo;
		        //echo $archivo . "<br />";
		    }
		}
		return $archivos;
	}
	public function validarCarpeta(){
		$path = getcwd()."/files/uploads/formatos/";
		if (!is_dir($path)) {
			mkdir($path,0777,true);
			chmod($path,0777);
			Flash::info("No estaba creada la carpeta para subir los formatos, ya se creó");
		}
		return $path;
	}
	public function validarNombre($nombre){

	}
	public function parseWord($userDoc) 
	{
	    $fileHandle = fopen($userDoc, "r");
	    $line = @fread($fileHandle, filesize($userDoc));   
	    $lines = explode(chr(0x0D),$line);
	    $outtext = "";
	    foreach($lines as $thisline)
	      {
	        $pos = strpos($thisline, chr(0x00));
	        if (($pos !== FALSE)||(strlen($thisline)==0))
	          {
	          } else {
	            $outtext .= $thisline." ";
	          }
	      }
	     $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
	    return $outtext;
		
	}
	function parseWord2($userDoc) 
	{
	    $fileHandle = fopen($userDoc, "r");
	    $word_text = @fread($fileHandle, filesize($userDoc));
	    $line = "";
	    $tam = filesize($userDoc);
	    $nulos = 0;
	    $caracteres = 0;
	    for($i=1536; $i<$tam; $i++)
	    {
	        $line .= $word_text[$i];

	        if( $word_text[$i] == 0)
	        {
	            $nulos++;
	        }
	        else
	        {
	            $nulos=0;
	            $caracteres++;
	        }

	        if( $nulos>1996)
	        {   
	            break;  
	        }
	    }

	    //echo $caracteres;

	    $lines = explode(chr(0x0D),$line);
	    //$outtext = "<pre>";

	    $outtext = "";
	    foreach($lines as $thisline)
	    {
	        $tam = strlen($thisline);
	        if( !$tam )
	        {
	            continue;
	        }

	        $new_line = ""; 
	        for($i=0; $i<$tam; $i++)
	        {
	            $onechar = $thisline[$i];
	            if( $onechar > chr(240) )
	            {
	                continue;
	            }

	            if( $onechar >= chr(0x20) )
	            {
	                $caracteres++;
	                $new_line .= $onechar;
	            }

	            if( $onechar == chr(0x14) )
	            {
	                $new_line .= "</a>";
	            }

	            if( $onechar == chr(0x07) )
	            {
	                $new_line .= "\t";
	                if( isset($thisline[$i+1]) )
	                {
	                    if( $thisline[$i+1] == chr(0x07) )
	                    {
	                        $new_line .= "\n";
	                    }
	                }
	            }
	        }
	        //troca por hiperlink
	        $new_line = str_replace("HYPERLINK" ,"<a href=",$new_line); 
	        $new_line = str_replace("\o" ,">",$new_line); 
	        $new_line .= "\n";

	        //link de imagens
	        $new_line = str_replace("INCLUDEPICTURE" ,"<br><img src=",$new_line); 
	        $new_line = str_replace("\*" ,"><br>",$new_line); 
	        $new_line = str_replace("MERGEFORMATINET" ,"",$new_line); 


	        $outtext .= nl2br($new_line);
	    }

	 return $outtext;
	} 

}

 ?>