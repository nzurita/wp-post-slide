<?php
	/* ================================================================================================================================================
		POST'S SLIDE FOR WORDPRESS
		
		* Function to show Wordpress posts in various formats, including a slide with optional autoplay.
		* It's required to link slide-nz.js file from page or template where using this function
		* It is not recommended to load many posts (maybe a maximum of 5 or 10 depending on how many slides your showing) as this function loads all 
		  posts in memory at once
		  
		Author: Norberto Zurita / http://nzurita.es / info@nzurita.es
		
		Licence: http://creativecommons.org/licenses/by/2.0/
		
	/* ================================================================================================================================================ */
    // ===================================================================================
    //nz_mostrar_categoria
    //Lista los N ultimos articulos de una categoria

    //$antiguedad_maxima: numero de dias maximos de antiguedad (0 => no limitar antiguedad)
    
    //layout: array:
    //              'id_wrapper': id. elemento contenedor de post
    //              'mostrar_foto': nombre del thumbnail
	//				'foto_alternativa': ruta de la foto alternativa si no hay thumbnail
    //              'mostrar_titulo'
    //              'mostrar_fecha'
    //              'mostrar_autor'
    //              'mostrar_resumen'
    //              'mostrar_leermas': true o rótulo a mostrar
    //              'todo_es_link': true | false (indica si el link al contenido de la noticia es todo o solo el titulo)
    //              'formato': '' | slide
	//					* parametros slide:
    //              		'direccion_slide': horizontal | vertical (default horizontal)
    //              		'dimensiones_slide': {ancho, alto}
	//						'enlaces_slide': true | false (si true muestra un enlace directo a cada entrada)
	//						'link_anterior': texto del link para slide anterior
	//						'link_siguiente': texto del link para slide siguiente
	//						'autoplay_slider': valor numérico indicando los milisegundos para el cambio automático de slide
    if ( ! function_exists( 'nz_mostrar_categoria' ) ) :

    function nz_mostrar_categoria($id_categoria, $layout=false, $numero_posts = 5, $antiguedad_maxima=0)
    {
        global $post;
        $tmp_post = $post;
    
		// ======== SUSTITUIR POR QUERY NOTICIAS ======== //
        $args = array(
                	'numberposts' => $numero_posts,
                	'category' => $id_categoria
                	); 
        
        $articulos = get_posts($args);
		// ======== /SUSTITUIR POR QUERY NOTICIAS ======== //
        
        if ($articulos)
        {
			$NArticulos = count($articulos);
			$clase_para_slide = '';
			$enlaces_del_slide = '';
			
			//Si el formato es slide, pone los estilos, los enlaces anterior/siguiente y el wrappers
			if ($layout["formato"] == slide)
			{
				$clase_para_slide = ' post-' . $layout["id_wrapper"];
				
				$layout["link_anterior"] = $layout["link_anterior"] ? $layout["link_anterior"] : "&lt;";
				$layout["link_siguiente"] = $layout["link_siguiente"] ? $layout["link_siguiente"] : "&gt;";

				$layout["direccion_slide"] = $layout["direccion_slide"] ? $layout["direccion_slide"] : "horizontal";
				
				if ($layout["direccion_slide"] == "vertical" )
					$alto_contenedor = ($layout["dimensiones_slide"][1] * $NArticulos) + 100;
				else
					$ancho_contenedor = ($layout["dimensiones_slide"][0] * $NArticulos) + 100;
				
				$desplazamiento_js =  $layout["direccion_slide"]=="horizontal" ? $layout["dimensiones_slide"][0] : $layout["dimensiones_slide"][1];

				if ($layout["autoplay_slider"])
				{
					echo
					'<script language="javascript">'
					.'$(document).ready(function()'
					.'{'
					.'		setTimeout("autoCambioSlide(\'' . $layout["id_wrapper"] . '\', \'' . $layout["direccion_slide"] . '\', ' . $desplazamiento_js . ', '. $layout["autoplay_slider"] . ')", ' . $layout["autoplay_slider"] . ');'
					.'});'
					.'</script>';
				}
				echo
				'<style>'
				.'	#ventanuco-' . $layout["id_wrapper"] . ' * {margin: 0;}' 
				.'	#ventanuco-' . $layout["id_wrapper"] 
				.'	{'
				.'		position: relative;'
				.'		width: ' . $layout["dimensiones_slide"][0] . 'px;'
				.'		height: ' . $layout["dimensiones_slide"][1] . 'px;'
				.'		padding: 0;'
				.'		overflow: hidden;'
				.'		/*border: 1px solid red;*/'
				.'	} ';
							
				echo
				'	#ventanuco-' . $layout["id_wrapper"] . ' .post-' . $layout["id_wrapper"] 
				.'	{'
				.'		position: absolute;'
				.'		overflow: hidden;'
				.'	} ';
				
				if ($layout["direccion_slide"] == "vertical" )
				{
					echo
					'	#ventanuco-' . $layout["id_wrapper"] . ' .post-' . $layout["id_wrapper"] 
					.'	{'
					.'		height: ' . $layout["dimensiones_slide"][1] . 'px;'
					.'		top: ' . $layout["dimensiones_slide"][1] . 'px;'
					.'		left: 0;'
					.'	} '
					.'	#ventanuco-' . $layout["id_wrapper"] . ' #post-' . $layout["id_wrapper"] . '-1{top: 0;}'
					.'	#contenedor-' . $layout["id_wrapper"] . '{height: ' . $alto_contenedor . 'px; width: ' . $layout["dimensiones_slide"][0] . 'px;}';
				}
				else
				{
					echo
					'	#ventanuco-' . $layout["id_wrapper"] . ' .post-' . $layout["id_wrapper"] 
					.'	{'
					.'		width: ' . $layout["dimensiones_slide"][0] . 'px;'
					.'		left: ' . $layout["dimensiones_slide"][0] . 'px;'
					.'		top: 0;'
					.'	} '
					.'	#ventanuco-' . $layout["id_wrapper"] . ' #post-' . $layout["id_wrapper"] . '-1{left: 0;}'
					.'	#contenedor-' . $layout["id_wrapper"] . '{width: ' . $ancho_contenedor . 'px; height: ' . $layout["dimensiones_slide"][1] . 'px;}';
				}
				echo
				'</style>';
				
				echo 
					'<a href="javascript:void(0)" '
						.' onclick="slideAnterior(\'' . $layout["id_wrapper"] . '\', \'' . $layout["direccion_slide"] . '\', ' . $desplazamiento_js . ')" id="link-anterior-' . $layout["id_wrapper"] . '"  class="link-anterior-slide" style="visibility:hidden">'
					. $layout["link_anterior"] .
					'</a>'
					.'<a href="javascript:void(0)" '
						.' onclick="slideSiguiente(\'' . $layout["id_wrapper"] . '\', \'' . $layout["direccion_slide"] . '\', ' . $desplazamiento_js . ')" id="link-siguiente-' . $layout["id_wrapper"] . '" class="link-siguiente-slide">'
					. $layout["link_siguiente"] .
					'</a>';
					
				echo '<div id="ventanuco-' . $layout["id_wrapper"] . '" class="ventanuco">';
				echo '<div id="contenedor-' . $layout["id_wrapper"] . '" class="contenedor-slide">';

				if ($layout["enlaces_slide"])
						$enlaces_del_slide = '<ul id="enlaces-slide-' . $layout["id_wrapper"] . '" class="enlaces-slide">';
			}
			
            if ($antiguedad_maxima)
        		$fecha_maxima = date("Y-m-d", strtotime("$fecha_actual - $antiguedad_maxima days"));
            else
        		$fecha_maxima = date("Y-m-d", "1900-01-01");

            $i = 0;
			$id_post = $layout["id_wrapper"] ? "post-" . $layout["id_wrapper"] : "post-$id_categoria";
			
        	foreach ($articulos as $post)
            {
                $i++;

				echo '<div class="post-en-categoria ' . $clase_para_slide . '" id="' . $id_post . "-" . $i .'">';
				
        		setup_postdata($post); //Añade a $post el resto de campos no dado por get_posts()
        		
                $fecha_post = get_the_date('Y-m-d');
                                
        		if (strtotime($fecha_post) > strtotime($fecha_maxima))
        		{
					if ($layout["todo_es_link"])
						echo '<a href="'. get_permalink($post) .'" title="'. get_the_excerpt() .'">';
        		    
        		    if ($i == $numero_posts)
        		      $clase_item = " item-noticia$i ultima ";
        		   else
        		      $clase_item = " item-noticia$i ";

             		echo '<div class="item-noticia '. $clase_item . '">';
                    if ($layout["mostrar_foto"])
                    {
						if (has_post_thumbnail())
							the_post_thumbnail($layout["mostrar_foto"], 
											   array('title'	=> trim(strip_tags($post->post_title ))));
						else
							echo '<img src="'. $layout["foto_alternativa"] . '" title="'. $post->post_title .'"/>';
                    }

             		echo '<div class="item-noticia-intro">';
					if ($layout["mostrar_titulo"])
					{
						if (!$layout["todo_es_link"])
						{
							echo '<a href="'. get_permalink($post) .'" title="'. get_the_excerpt() .'" class="titulo-post">';
							the_title();
							echo '</a>';
						}
						else the_title();
					}
                    
            		echo '<p class="meta-info-post">';
                    nz_posted_on($layout["mostrar_fecha"], $layout["mostrar_autor"]); //the_date();
                    echo '</p>';
                                        
                    if ($layout["mostrar_resumen"])
                    {
            		  echo '<div class="extracto-post entradilla">';
            		  the_excerpt();
            		  echo '</div> <!-- extracto-post -->';
                    }
             		echo '</div> <!-- class="item-noticia-intro" -->';
                    
					if ($layout["mostrar_leermas"])
					{
						$rotulo = $layout["mostrar_leermas"] === true ? 'Leer más' : $layout["mostrar_leermas"];
						echo '<a href="'. get_permalink($post) . '" class="link-mas-info">'
					   . $rotulo
					   .'</a>';
					}
					
             		echo '</div> <!-- class="item-noticia" -->';
					if ($layout["todo_es_link"])
						echo '</a>';
						
					if ($layout["formato"] == slide && $layout["enlaces_slide"])
					{
						$clase='';
						if ($i==1)
							$clase = ' on';

						$enlaces_del_slide .= 
							'<li>'
							.'<a id="link-directo-slide-' . $layout["id_wrapper"] . '-'. $i . '" href="javascript:void(0)" '
							.' class="link-directo-slide ' . $clase .'"'
							.' onclick="saltoASlide(\'' . $layout["id_wrapper"] . '\', \'' . $layout["direccion_slide"] . '\', ' . $desplazamiento_js . ', '. $i .')">'
							. get_the_title()
							. '</a></li>';
					}
                }
				
				echo '</div><!-- .post-en-categoria -->';
        	} //foreach
			if ($layout["formato"] == slide)
			{
				if ($layout["enlaces_slide"])
						$enlaces_del_slide .= '</ul><!-- #enlaces-slide-' . $layout["id_wrapper"] . ' -->';
						
				echo 
				'</div> <!-- #contenedor-' . $layout["id_wrapper"] . ' -->'
				.'</div> <!-- #ventanuco-' . $layout["id_wrapper"] . ' -->'
				. $enlaces_del_slide 
				.'<input type="hidden" id="SLIDE_POST_ACTUAL-' . $layout["id_wrapper"] . '" value="1" />'
				.'<input type="hidden" id="SLIDE_POST_TOTALES-' . $layout["id_wrapper"] . '" value="' . $i .'" />';
			}

        } //($articulos)
        $post = $tmp_post;
    }
?>