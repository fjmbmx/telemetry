 
<?php
include("sendemail.php"); //Mando a llamar la funcion que se encarga de enviar el correo electronico


/*===============================================
Cuando se envian datos de contacto desde la pagina de telemetry
===============================================*/


if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$data = json_decode(file_get_contents('php://input'), true);
	/*Configuracion de variables para enviar el correo*/
	$server_mail_user = "support@tele-metry.net"; //Correo electronico saliente ejemplo: tucorreo@gmail.com
	$server_mail_password = "5upp0rt-telemetry"; //Tu contraseña de gmail
	$template = "email_template.html"; //Ruta de la plantilla HTML para enviar nuestro mensaje

	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$prospect_name = $data['prospect_name'];
	$prospect_contact =  $data['prospect_contact'];
	$prospect_subject =  $data['prospect_subject'];
	$prospect_message =  $data['prospect_message'];

	$list_mail_recipients = array('fjmb.mx@gmail.com' => 'Javier Martinez Bautista',
	'fjmb.mx@hotmail.com' => 'Javier Martinez Bautista',
	 'test-kahrdxthl@srv1.mail-tester.com' => 'Soporte',);


	sendemailContact(
		$server_mail_user,
		$server_mail_password,
		$list_mail_recipients,
		$prospect_name,
		$prospect_contact,
		$prospect_message,
		$prospect_subject,
		$template
	);  
}

?>