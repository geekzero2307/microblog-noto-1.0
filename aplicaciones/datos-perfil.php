<?

     if (isset( $_SESSION['ingreso'] ) ){

                    echo "</br>";
                    echo"<table border ='0' width='80%' align= 'center'>";
                    echo"<tr>";
                    echo  "<td width='25%' align='center'>";
                    if (@$ver_miembro) {
                         include "./aplicaciones/foto_perfil_otromiembro.php";
                    }else{
                         include "./aplicaciones/foto_perfil.php";
                    }
                    #echo      "</br>";
                    echo "</br>";
                    if ($reside == " ") {
                    #hacer nada 
                    }else{
                      echo "<div class='alert panel_datos_residencia'>";
                      echo  "<span class='glyphicon glyphicon-map-marker'></span> de ".$reside;
                      echo "</div>";
                    }                    
                    echo    "</td>";
                    echo    "<td>";
                    echo     "<div class='alert panel_datos'>";
                    if ($carrera == "ninguno") {
                    #hacer nada  
                    }else{
                      echo       "<span class='glyphicon glyphicon-briefcase'></span> $carrera";
                    echo "</br>";
                    echo "</br>";
                    }

                    if ($estado == "administrador") {

                      echo       "<span class='glyphicon glyphicon-flag'></span> ".$estado." del blog desde ".date("d F 'y", strtotime("$fecha_estado"));

                      echo "</br>";
                      echo "</br>";
                    }else{
                      if ($estado == "egresado") {
                      echo        "<span class='glyphicon glyphicon-star'></span> ".$estado." desde ".date("d F 'y", strtotime("$fecha_estado"));
                      echo "</br>";
                      echo "</br>";
                      }else{

                      echo        "<span class='glyphicon glyphicon-star-empty'></span> ".$estado." desde ".date("d F 'y", strtotime("$fecha_estado"));
                      echo "</br>";
                      echo "</br>";
                      }

                    }
                      echo        "<span class='glyphicon glyphicon-gift'></span> nací el ".date("d F 'y", strtotime("$fecha_nacimiento"));
                      echo "<br>";
                        echo "</div>";
                      echo  "</td>";
                      echo "</tr>";
                      echo "<tr>";             
                    $frase = mostrar_comilla_simple_frase ($frase);
                    if ($frase == " ") {
                      echo  "<td colspan='2'>";   
                    }else{
                      echo  "<td colspan='2'>"; 
                      
                      #echo  "</td>";
                      #echo  "<td>";  
                          echo  "<div class='alert panel_datos_frase'>";
                          echo   "<span class='separador' title='frase'><span class='glyphicon glyphicon-bookmark'></span></span>".$frase;
                          echo  "</div>";

                         
                    }
                      echo  "</td>";
                      echo "</tr>";
                    echo "</table>";
#################Publicaciones de tipo libre: hechas por el usuario dueño de perfil########################

          if (@$ver_miembro) {
               echo "<hr>";
               $vector_de_publicaciones = consultar("select * from vista_publicacion where idtipo_publi = 1 and miembro = $ver_miembro
                                                           order by  fecha desc, tiempo desc;");
                     $cantidad_de_publicaciones = count( $vector_de_publicaciones );
                     if ( $cantidad_de_publicaciones > 0 ) {
                       echo "<span class='libro_pushpin' title='publicaciones libres'><span class='glyphicon glyphicon-fullscreen'></span></span>";
                       echo "<table  border='0' width='55%' align='center'>";
                       for ( $contador = 0 ; $contador < $cantidad_de_publicaciones ; $contador ++ ) {

                           ( $contador + 1 );
                           $idpubli        =$vector_de_publicaciones[$contador]['idpublicacion'];
                           $dueño_publi    = $vector_de_publicaciones[$contador]['miembro'];
                           #se crea esta variable $iddueño_perfil para enviar el identificador del miembro solicitando ver su perfil
                           $iddueño_perfil = $dueño_publi + 6;
                           $idtipo_publi   = $vector_de_publicaciones[$contador]['idtipo_publi'];
                           $idimagen       = $vector_de_publicaciones[$contador]['idimagen'];
                           $nombres        = $vector_de_publicaciones[$contador]['nombres'];
                           $nombres_decode = mostrar_comilla_simple_nombres ($nombres);
                           $limpionombres  = @ ereg_replace("[^A-Za-z0-9]", "", $nombres);
                           $titulo         = $vector_de_publicaciones[$contador]['titulo']; 
                           echo "<tr>";
                                echo "<td>";
                                echo     "<a class='' title='ver perfil' href='./index.php?perfil=$limpionombres&noto=$iddueño_perfil' >".mini_avatar_perfil($dueño_publi)." $nombres_decode</a>:"."<span class='titulo'> $titulo</span>";
                                echo "</td>";
                                #en este archivo esta declarado la variable correo y variable id de miembro
                                include "./configuraciones/correo-idmiembro.php";
                                if ($dueño_publi == $miembroid) {
                                  # hacer nada jeje. No mostrar el formulario de reporte, por que no querra reportar su propia publicacion
                                }else{
                                 #el formulario para reportar anomalias de publicaciones o miembros
                                        echo "<form action='./index.php?contenido=reportar_publicacion' target='_blank' method='post'>";
                                         echo "<input type='hidden' name='idmiembro_reporta' value='$miembroid'>";
                                         echo "<input type='hidden' name='idimagen' value='$idimagen'>";
                                         echo "<input type='hidden' name='idtipo_publi' value='$idtipo_publi'>";
                                         echo "<input type='hidden' name='dueño_publi' value='$dueño_publi'>";
                                         echo "<input type='hidden' name='idpubli' value='$idpubli'>";
                                echo "<td width='01%'>";
                                   echo"<button class='report report-default' title= 'quiero denunciar algo acerca de esto'><span class='glyphicon glyphicon-bullhorn'></span></button>";
                                echo "</td>";
                                       echo"</form>";
                                }
                          echo "</tr>";
                          echo "<tr>";
                                echo "<td>";
                                $fecha = $vector_de_publicaciones[$contador]['fecha'];
                                $hora = $vector_de_publicaciones[$contador]['tiempo'];
                                echo  "<p class = ayuda >".date("d/m/y", strtotime("$fecha"))."  hora ".date('h:i a', strtotime("$hora"))."</p>";
                                $ruta = $vector_de_publicaciones[$contador]['imagen'];
                                
                                ######
                                /*con esta funcion se establecera las dimenciones de la imagen (si es que existe $ruta)
                                a mostrar*/
                                dimenciones_imagenes_publicaciones ($ruta, @$width, @$height, $titulo, $fecha, $hora, $nombres);                 
                                #######

                                echo "</td>";
                          echo "</tr>";
                          echo "<tr>";
                                $limpiotitulo = @ ereg_replace("[^A-Za-z0-9]", "", $titulo);
                                echo "<form action='./index.php?contenido=";echo $limpionombres."-".$limpiotitulo."-".$idpubli; echo "' target='_blank' method='post'>";
                                echo "<input type='hidden' name='id' value='$idpubli'>";
                                echo "<input type='hidden' name='nombreurl' value='$limpionombres'>";
                                echo "<input type='hidden' name='titulourl' value='$limpiotitulo'>";

                                echo "<td width='60%' colspan='2'>";
                                   $textolargo      = $vector_de_publicaciones[$contador]['texto_publicacion'];
                                   $textocortado    = recortar($textolargo, 230); // recortamos 350 caracteres
                                   $textocortado    = str_replace("\r","<br>",$textocortado);
                                   // mostramos el texto cortado
                                   echo "<span class='texto_publicacion'>".$textocortado."</span>";
                                   #estas variables servira para mostrar mensaje de "que dices sobre esto?" en caso de que nadie
                                   #aya echo un comentario o reaccion "me gusta" o "no me gusta"
                                   $comentarios=@$vector_de_publicaciones[$contador]['comentarios'];
                                   $chivos=@$vector_de_publicaciones[$contador]['chivos'];
                                   $neles=@$vector_de_publicaciones[$contador]['neles'];
                                   if (!$comentarios && !$chivos && !$neles ){
                                   echo "<center><span class='ayuda'>Que dices sobre esto?</span>";
                                   #aquí el boton para ver la publicacion completa, y ahi se podra comentar o aplicar alguna reacion de "me gusta" o "no me gusta"
                                  echo "<center><button class='ver b-default' title='ver publicacion' > <span class='glyphicon glyphicon-thumbs-up'></span> te gusta? <span class='glyphicon glyphicon-comment'></span> comentar? <span class='glyphicon glyphicon-eye-open'></span> ver?</button></center>";
                                  echo"</form>";
                                  }else{
                                    #aquí el boton para ver la publicacion completa, y ahi se podra comentar o aplicar alguna reacion de "me gusta" o "no me gusta"
                                  echo "<center><button class='ver b-default' title='ver publicacion' >te gusta? | comentar? | ver?</button></center>";
                                  }
                                echo"</form>";
                                echo "</td>";
                          echo "</tr>";


               /***********************eventos****************************/

               /*******************AQUI VAN LOS EVENTOS-ME GUSTA-***************************************/
                          echo "<tr>";
                                echo "<td>";
                                 echo "<span class='ayuda'>score <span class='glyphicon glyphicon-stats'></span></span>";
                                 include "./aplicaciones/plus.php";

                                   if (@$cantidad_like_miembro) {
                                     echo "<p class='ayuda'>me gusta</p>";
                                   }
                                   if (@$cantidad_nel_miembro) {
                                     echo "<p class='ayuda'>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp no me gusta</p>";
                                   }
                                 echo "<hr>
                                     ";
                                echo "</td>";
                        echo "</tr>";
                       }
                       echo "</table>";
                     }

          }
      
     }

?>
