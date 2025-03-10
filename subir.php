<?php

$carpetaSubir = "imagenes/";

function mostrarMensaje($mensaje)
{
	echo "<script type='text/javascript'>
			alert('$mensaje')
			window.location = 'https://localhost/subir/'
		  </script>";
}
  
// Si se ha pulsado el botón "Subir"
if (isset($_POST["subir_fichero"]))
{
	// Comprobamos que se haya elegido un fichero
	if (!isset($_FILES["fichero"]["name"]))
	{
		mostrarMensaje ("No ha elegido un fichero a subir.");
	} else
	{
		$ficheroSubir = $carpetaSubir . basename($_FILES["fichero"]["name"]);		
		// Si el tamaño del fichero elegido es mayor del permitido en MAX_FILE_SIZE, la siguiente variable estará vacía
		if (!isset($_FILES["fichero"]["tmp_name"]) || $_FILES["fichero"]["tmp_name"] == null)
		{
			mostrarMensaje ("El fichero elegido no cumple los requisitos de tamaño. Elija otro.");
		}
		else
		{
			$ficheroTmp = $_FILES["fichero"]["tmp_name"];
			$extensionFichero = strtolower(pathinfo($ficheroSubir, PATHINFO_EXTENSION));
			// Comprobamos si ya existe el fichero en la carpeta de subida del servidor
			if (file_exists($ficheroSubir))
			{
				mostrarMensaje ("Ya existe un fichero con el mismo nombre subido. Elija otro o cámbiele el nombre.");
			} else
			{
				// Comprobamos que el fichero elegido sea una imagen
				//$correcta = getimagesize($_FILES["fichero"]["tmp_name"]);
				$infoFichero = getimagesize($ficheroTmp);
				if (!$infoFichero)
				{
					mostrarMensaje ("El fichero elegido no es una imagen. No se subirá.");
				} else 
				{
					// Para depurar
					//mostrarMensaje ("El fichero es una imagen de tipo " . $infoFichero["mime"] . ".");

					// Comprobamos el tamaño del fichero a subir
					// No debe exceder los 2MB
					if ($_FILES["fichero"]["size"] > 2097152)	
					{
					  mostrarMensaje ("El fichero elegido excede los 2MB. Elija un fichero más pequeño.");
					} else
					{
						// Permitimos ficheros jpg, jpeg, png y bmp
						if($extensionFichero != "jpg" && $extensionFichero != "png" 
							&& $extensionFichero != "jpeg" && $extensionFichero != "bmp")
						{
						  mostrarMensaje ("Sólo se admiten ficheros de tipo jpg, jpeg, png o bmp.");
						}
						else
						{
							// Puesto que el fichero cumple los requisitos, lo subimos al servidor
							if (move_uploaded_file($_FILES["fichero"]["tmp_name"], $ficheroSubir))
							{
								mostrarMensaje ("El fichero " . htmlspecialchars(basename($_FILES["fichero"]["name"])) . 
									" ha sido subido correctamente a la web.");
							} else
							{
								mostrarMensaje ("Ha habido algún error y el fichero NO se ha subido.");
							}
						}
					}
				}
			}
		}
	}
} else
{
	//Puesto que no se ha pulsado el botón "Subir", redireccionamos a index.html
	header("Location: index.html");
}
?>