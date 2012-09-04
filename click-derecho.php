<?php
/*
Plugin Name: Menu contextual personalizado
Plugin URI: http://blog.superjd10.com.ar/menu-contextual-personalizado/
Description: Con este plugin desactivas el click derecho de tu sitio y muestras en su lugar un menu personalizado.
Version: 1.2
Author: Superjd10
Author URI: http://superjd10.com.ar
*/

function menu_contextual_instala(){
    global $wpdb;
    $table_name= $wpdb->prefix . "click_derecho";
        $sql = " CREATE TABLE $table_name(
        id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
        menu_anterior text NOT NULL ,
        menu_recargar text NOT NULL ,
        css_estilos text NOT NULL ,
        javascript_scripts text NOT NULL ,
        PRIMARY KEY ( `id` )   
    ) ;";
    $wpdb->query($sql);

    $estilos_para_la_tabla = '<style type="text/css">

/* ----------------------------
simple reset
---------------------------- */

html, body, ul, ol, li, form, fieldset, legend
{
	margin: 0;
	padding: 0;
}

h1, h2, h3, h4, h5, h6, p { margin-top: 0; }

fieldset,img { border: 0; }

legend { color: #000; }

li { list-style: none; }

sup { vertical-align: text-top; }

sub { vertical-align: text-bottom; }

table
{
	border-collapse: collapse;
	border-spacing: 0;
}

caption, th, td
{
	text-align: left;
	vertical-align: top;
	font-weight: normal;
}

input, textarea, select
{
	font-size: 110%;
	line-height: 1.1;
}

abbr, acronym
{
	border-bottom: .1em dotted;
	cursor: help;
}

ul, li {
border:0pt none;
font-family:inherit;
font-size:100%;
font-style:inherit;
font-weight:inherit;
margin:0pt;
padding:0pt;
vertical-align:baseline;
}
#menuclickderecho {
	display: none;
	width: 330px;
	padding: 1px;
	background: #171717;
	border: 1px solid #3e3e3e;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	position: absolute;
	z-index: 9999;
}
#menuclickderecho ul{
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	color: #6d6d6d;
	background: #fff;
	border: 1px solid #171717;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	list-style: none;
	list-style-type: none;
	list-style-position: outside;
}
#menuclickderecho ul li{
	line-height: 1.5em;
	padding: 6px 60px 6px 8px;
	font-size: 11px;
	border-bottom: 1px solid #fff;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
}
#menuclickderecho ul li:hover{
	background-color: #fff8cc;
	border-bottom: 1px solid #ffe222;
	color: #000;
	cursor: pointer;
}
#menuclickderecho ul li a{
	color: #296ba5;
	text-decoration: none;
	outline: none !Important;
	color: #6d6d6d;
	display: block;
}
#menuclickderecho ul li{
	background-position: 3px 6px;
	background-repeat: no-repeat;
}

</style>';
	
	$javascript_para_la_tabla = '<script type="text/javascript">

//Cuando el documento esta listo...
$(document).ready(function(){
	
//variables de control
var menuId = "menuclickderecho"; //Este es el div del menu declarado en el html y en el css
var menu = $("#"+menuId); //Aca por medio de un selector de jQuery se dice con cual div trabajaremos, es el que dijimos arriba

//EVITAMOS que se muestre el MENU CONTEXTUAL del sistema operativo o del navegador al hacer CLICK con el BOTON DERECHO del RATON
$(document).bind("contextmenu", function(e){
	menu.css({"display":"block", "left":e.pageX, "top":e.pageY});
	return false;
});
	
	//controlamos ocultado de menu cuando esta activo
	//click boton principal raton
	$(document).click(function(e){
		if(e.button == 0 && e.target.parentNode.parentNode.id != menuId){
			menu.css("display", "none");
		}
	});
	//pulsacion tecla escape
	$(document).keydown(function(e){
		if(e.keyCode == 27){
			menu.css("display", "none");
		}
	});
	
	//Control sobre las opciones del menu contextual
	menu.click(function(e){
		//si la opcion esta desactivado, no pasa nada
		if(e.target.className == "disabled"){
			return false;
		}
		//si esta activada, gestionamos cada una y sus acciones
		else{
			switch(e.target.id){
				//Para ir a la pagina anterior
				case "menu_anterior":
					history.back(-1);
					break;
				//Para recargar la pagina
				case "menu_recargar":
					document.location.reload();
					break;
			}
			menu.css("display", "none");
		}
	});

});	

</script>';
    $sql = "INSERT INTO $table_name (id, menu_anterior, menu_recargar, css_estilos, javascript_scripts) VALUES ('1', 'Ir a la p&aacute;gina anterior', 'Recargar p&aacute;gina', '$estilos_para_la_tabla', '$javascript_para_la_tabla');";
    $wpdb->query($sql);
}

function menu_contextual_desinstala(){
    global $wpdb;
    $table_name = $wpdb->prefix . "click_derecho";
    $sql = "DROP TABLE $table_name";
    $wpdb->query($sql);
}

add_action('activate_menu-contextual-personalizado/click-derecho.php','menu_contextual_instala');
add_action('deactivate_menu-contextual-personalizado/click-derecho.php', 'menu_contextual_desinstala');

add_action('plugin_action_links','plugin_action', 10, 2);

    function plugin_action($links, $file) {
      if ($file == plugin_basename(dirname(__FILE__).'/click-derecho.php')) {
      $settings_link = "<a href='admin.php?page=menu_contextual'>" .
        __('Opciones', 'menu_contextual') . "</a>";
      array_unshift( $links, $settings_link );
      }
      return $links;
    }

add_action('admin_menu', 'menu_contextual_panel');

function menu_contextual_panel() {
	if ( is_super_admin() ) {
	add_utility_page('Opciones del Menu Contextual', 'Menu Contextual', 'manage_options', 'menu_contextual', 'menu_contextual_admin', plugins_url() . '/menu-contextual-personalizado/mouse-select.png');
	}
}

function menu_contextual_admin() {
    global $wpdb;
        $table_name = $wpdb->prefix . "click_derecho";
    if(isset($_POST['contenido_menu_anterior']) && isset($_POST['contenido_menu_recargar']) && isset($_POST['contenido_estilos_css']) && isset($_POST['contenido_scripts_javascripts'])){
        $contenido_menu_anterior = $_POST['contenido_menu_anterior'];
        $contenido_menu_recargar = $_POST['contenido_menu_recargar'];
        $contenido_estilos_css = $_POST['contenido_estilos_css'];
        $contenido_scripts_javascripts = $_POST['contenido_scripts_javascripts'];
        echo "<div id='setting-error-settings_updated' class='updated settings-error'> 
<p><strong>Opciones guardadas.</strong></p></div> ";
    }else{
        $contenido_menu_anterior = $wpdb->get_var("SELECT menu_anterior FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
        $contenido_menu_recargar = $wpdb->get_var("SELECT menu_recargar FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
        $contenido_estilos_css = $wpdb->get_var("SELECT css_estilos FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
        $contenido_scripts_javascripts = $wpdb->get_var("SELECT javascript_scripts FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
    }
    ?>
    <br>
    <div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div><h2>Opciones &raquo; Menu contextual personalizado</h2>
    <form method="post" action="" id="click_derecho">
    <h4 id="edittexts">Textos del menu contextual</h4><br>
		<div class="metabox-holder">
			<div class="postbox">
  					<h3><label for="contenido_menu_anterior">Texto de "menu anterior":</label></h3>
  					<p>Aqui puedes editar el texto de lo que dira en "ir a la pagina anterior".</p>
  					<p><textarea name="contenido_menu_anterior" style="resize: none;" cols="70" rows="2" id="contenido_menu_anterior" class="large-text code"><?php echo $contenido_menu_anterior; ?></textarea></p>
		    		<div class="fsclear"></div>
		    </div>
		</div>
	<br>
		<div class="metabox-holder">
			<div class="postbox">
	  				<h3><label for="contenido_menu_recargar">Texto de "recargar p&aacute;gina":</label></h3>
	  				<p>Aqui puedes editar el texto de lo que dice en "ir a la pagina siguiente".</p>
		    		<p><textarea name="contenido_menu_recargar" style="resize: none;" cols="70" rows="2" id="contenido_menu_recargar" class="large-text code"><?php echo $contenido_menu_recargar; ?></textarea></p>
		        	<div class="fsclear"></div>
		    </div>
		</div>

	<h4 id="editcodes">Estilos y javascript del menu contextual <small>(No edites nada de aqui si no estas 100% seguro de lo que haces)</small></h4><br>
		<div class="metabox-holder">
			<div class="postbox">
  				<h3><label for="contenido_estilos_css">Estilos del menu contextual:</label></h3>
  				<p>Aca puedes agregarles tus propios estilos al menu, debes tener cuidado de no borrar etiquetas importantes o el menu dejara de funcionar.</p>
				<p><textarea name="contenido_estilos_css" cols="70" rows="25" id="contenido_estilos_css" class="large-text code"><?php echo $contenido_estilos_css; ?></textarea></p>
				<div class="fsclear"></div>
		    </div>
		</div>
		<div class="metabox-holder">
			<div class="postbox">
  				<h3><label for="contenido_scripts_javascripts">Scripts del menu contextual:</label></h3>
  				<p>Aqui estan los javascripts fundamentales del menu, aconsejo no tocar esto a menos que sepas mucho de jQuery.</p>
		    	<p><textarea name="contenido_scripts_javascripts" cols="70" rows="25" id="contenido_scripts_javascripts" class="large-text code"><?php echo $contenido_scripts_javascripts; ?></textarea></p>
				<div class="fsclear"></div>
	   	    </div>
		</div>
	<br>

	<input type="submit" name="enviar" value="Guardar cambios" class="button-primary" id="submit" />'
    </form>
    <br>
    <br>
    <br>
    <small><b>Hecho por <a href="http://superjd10.com.ar">Superjd10</a></b></small>
    </div>
    <?php

    if(isset($_POST['contenido_menu_anterior']) && isset($_POST['contenido_menu_recargar']) && isset($_POST['contenido_estilos_css']) && isset($_POST['contenido_scripts_javascripts'])){  
    $sql = "UPDATE `".$table_name."` SET `menu_anterior` = '{$_POST['contenido_menu_anterior']}', `menu_recargar` = '{$_POST['contenido_menu_recargar']}', `css_estilos` = '{$_POST['contenido_estilos_css']}', `javascript_scripts` = '{$_POST['contenido_scripts_javascripts']}' WHERE `id`=1;";
         $wpdb->query($sql);
   }

}

function llamar_a_jquery() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'llamar_a_jquery');

function menu_contextual_estilos_y_javascript() {
            global $wpdb;
        $table_name = $wpdb->prefix . "click_derecho";
            $estilos_a_mostrar = $wpdb->get_var("SELECT css_estilos FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
            $scripts_a_mostrar = $wpdb->get_var("SELECT javascript_scripts FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
		echo $estilos_a_mostrar;
		echo $scripts_a_mostrar;

}

function menu_contextual_html() { 
            global $wpdb;
        $table_name = $wpdb->prefix . "click_derecho";
            $anterior = $wpdb->get_var("SELECT menu_anterior FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
            $recargar = $wpdb->get_var("SELECT menu_recargar FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
?>
<!-- Aqui esta el menu contextual, oculto por defecto -->
	<div id="menuclickderecho">
		<ul>
			<li id="menu_anterior"> <?php echo $anterior ?></li>
			<li id="menu_recargar"> <?php echo $recargar ?></li>
			<li id="menu_web"><a href="<?php echo home_url(); ?>">Volver al inicio</a></li>
		</ul>
	</div>
<!-- Aqui termina el menu contextual -->


<?php }
add_action('wp_head', 'menu_contextual_estilos_y_javascript');
add_action('wp_footer', 'menu_contextual_html');