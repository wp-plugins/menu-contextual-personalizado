<?php
/*
Plugin Name: Menu contextual personalizado
Plugin URI: http://blog.superjd10.com.ar/2012/07/31/menu-click-derecho-personalizado/
Description: Con este plugin desactivas el click derecho de tu sitio y muestras en su lugar un menu personalizado || BETA SIN PANEL DE ADMINISTRACION
Version: 0.1
Author: Superjd10
Author URI: http://superjd10.com.ar
*/

$texto_pagina_anterior = "Ir a la p&aacute;gina anterior";
$texto_recargar_pagina = "Recargar p&aacute;gina";

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
	add_utility_page('Opciones del Menu Contexual', 'Menu Contextual', 'manage_options', 'menu_contextual', 'menu_contextual_admin', plugins_url() . '/click-derecho/mouse-select.png');
	}
}

function menu_contextual_admin() {

echo "<div id='setting-error-settings_updated' class='updated settings-error'> <strong><p> Muy pronto.... </strong></p></div>";
}

function llamar_a_jquery() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'llamar_a_jquery');

function menu_contextual_estilos_y_javascript() {

?>

<style type="text/css">

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

</style>

<script type="text/javascript">

//Cuando el documento esta listo...
$(document).ready(function(){
	
//variables de control
var menuId = "menuclickderecho"; //Este es el div del menu declarado en el html y en el css
var menu = $("#"+menuId); //Aca por medio de un selector de jQuery se dice con cual div trabajaremos, es el que dijimos arriba

//EVITAMOS que se muestre el MENU CONTEXTUAL del sistema operativo o del navegador al hacer CLICK con el BOTON DERECHO del RATON
$(document).bind("contextmenu", function(e){
	menu.css({'display':'block', 'left':e.pageX, 'top':e.pageY});
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

</script>

<?php
}

function menu_contextual_html() { 
global $texto_pagina_anterior, $texto_recargar_pagina;
?>
<!-- Aqui esta el menu contextual, oculto por defecto -->
	<div id="menuclickderecho">
		<ul>
			<li id="menu_anterior"> <?php echo $texto_pagina_anterior; ?></li>
			<li id="menu_recargar"> <?php echo $texto_recargar_pagina; ?></li>
			<li id="menu_web"><a href="<?php echo home_url(); ?>">Volver al inicio</a></li>
		</ul>
	</div>
<!-- Aqui termina el menu contextual -->


<?php }
add_action('wp_head', 'menu_contextual_estilos_y_javascript');
add_action('wp_footer', 'menu_contextual_html');