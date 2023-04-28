 
<?php
include("sendemail.php"); //Mando a llamar la funcion que se encarga de enviar el correo electronico


/*===============================================
Cuando se realiza peticion POST a la API de sensores
===============================================*/


if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$data = json_decode(file_get_contents('php://input'), true);
	/*Configuracion de variables para enviar el correo*/
	$server_mail_user = "notifications@tele-metry.net"; //Correo electronico saliente ejemplo: tucorreo@gmail.com
	$server_mail_password = "5upp0rt-telemetry"; //Tu contraseña de gmail
	$template = "alert_sensors_template.html"; //Ruta de la plantilla HTML para enviar nuestro mensaje

	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$alert_subject =  "🚨 ".$data['alert_subject'];
	$client_name =  $data['client_name'];
	//correo electronico que recibira el mensaje
	$list_mail_recipients = array('fjmb.mx@gmail.com' => 'Javier Martinez Bautista', 'support@tele-metry.net' => 'Soporte',);

	$list_data_alert = array(
		'client_name' =>  $data['client_name'],
		'tmp_value_sensor' =>  $data['data_sensors']['tmp_value_sensor'],
		'tmp_value_accepted' =>  $data['data_sensors']['tmp_value_accepted'],
		'lpa_value_sensor' =>  $data['data_sensors']['lpa_value_sensor'],
		'lpa_value_accepted' =>  $data['data_sensors']['lpa_value_accepted']
	);


	sendemailAlert(
		$server_mail_user,
		$server_mail_password,
		$list_mail_recipients,
		$list_data_alert,
		$alert_subject,
		$client_name,
		$template
	);
}

?>