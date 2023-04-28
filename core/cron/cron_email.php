 
<?php
require_once("../email/sendemail.php"); //Mando a llamar la funcion que se encarga de enviar el correo electronico

# Nuestra base de datos
require_once "config/bd.php";
/*===============================================
Cuando se envian datos de contacto desde la pagina de telemetry
===============================================*/
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

date_default_timezone_set("America/Mexico_City");

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	/*Configuracion de variables para enviar el correo*/
	$server_mail_user = "notifications@tele-metry.net"; //Correo electronico saliente ejemplo: tucorreo@gmail.com
	$server_mail_password = "5upp0rt-telemetry"; //Tu contraseña de gmail
	$template = "index.html"; //Ruta de la plantilla HTML para enviar nuestro mensaje
	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$alert_subject =  '⚡⚠' . 'Dispositivo desconectado durante un largo periodo de tiempo.' . '⚠⚡' ;
	//correo electronico que recibira el mensaje
	$list_mail_recipients = array('fjmb.mx@gmail.com' => 'Javier Martinez Bautista');
 	$list_data_alert = "";
 	$client_name = "";

	# Obtener base de datos
	$bd = obtenerBD();
	# Obtener clientes de BD
	$query1_devices = "select c.id_client, c.name ,cd.id_device, d.code,  d.serial from client c 
	inner join client_device cd  on c.id_client = cd.id_client
	inner join device d on d.id_device =cd.id_device 
	where active =1";

	$query2_data = "SELECT max(created_at) as last_date  FROM data_report dr WHERE id_device = ?";

	$sentencia = $bd->prepare($query1_devices, [
		PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
	]);
	$sentencia->execute();

	$datetime = new DateTime();
	# Obtener los datos de la base de datos

	while ($clientMail = $sentencia->fetchObject()) {
		$client = new stdClass();
		$client->id_client = $clientMail->id_client;
		$client->id_device = $clientMail->id_device;
		$client->name = $clientMail->name;
		$client->code_device = $clientMail->code;
		$client->serial = $clientMail->serial;

		$sentencia2 = $bd->prepare($query2_data, [
			PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
		]);
		$sentencia2->bindValue(1, $client->id_device);
		$sentencia2->execute();
		$data = $sentencia2->fetchObject();
		$isSend= false;
		if ($data->last_date == null) {
			echo "El dispositivo del cliente : " .$client->name. " reporto hace mas de dos horas \n";
			$isSend= true;

		} else if ($data->last_date != null) {
			$fechaTelemetry = new DateTime($data->last_date);
			$fechaDiff = $datetime->diff($fechaTelemetry);
			if($fechaDiff->h >2 && $fechaDiff->i > 0){
				echo "El dispositivo del cliente : " .$client->name. " reporto hace mas de dos horas ". 
				 $fechaTelemetry->format('Y-m-d H:i:s') ;
				 $isSend= true;

			}
			
		}
		$client_name = $client->name;
		$list_data_alert = array(
			'client' =>  $client->name,
			'device' =>  $client->code_device .'-'. $client->serial,
			'last_value' =>  $fechaTelemetry->format('Y-m-d H:i:s') 
		);

		if($isSend){
			sendEmailAlertEmail(
				$server_mail_user,
				$server_mail_password,
				$list_mail_recipients,
				$list_data_alert,
				$alert_subject,
				$client_name,
				$template);
		}
	}
}

?>