<?php
require 'PHPMailer/PHPMailerAutoload.php';

function sendemailAlert(
	$server_mail_user,
	$server_mail_password,
	$list_mail_recipients,
	$list_data_alert,
	$alert_subject,
	$client_name,
	$template
) {

	// Configuración servidor SMTP
	$mail = new PHPMailer;
	$mail->isSMTP();                            // Set mailer to use SMTP
	$mail->SMTPDebug = false;
	$mail->Host = 'tele-metry.net';             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = $server_mail_user;        // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail->Password = $server_mail_password;    // Tu contraseña de gmail
	$mail->SMTPSecure = 'ssl';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = 587;							// Puerto TCP  para conectarse  


	/**Introduzca la dirección de la que debe aparecer el correo electrónico.
	 * Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. 
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente
	 * en lugar de la dirección de correo electrónico en sí.
	**/
    $mail->setFrom("notifications@tele-metry.net", $client_name);
	/**Introduzca la dirección de la que debe responder.
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
	**/
	 $mail->addReplyTo("notifications@tele-metry.net"); 

	// $sql="select email from emails ";
	// foreach ($dbo->query($sql) as $row) {
	// 	$email->AddAddress($row[email]);
	// }


	foreach ($list_mail_recipients as $email => $name) {
		$mail->AddCC($email, $name); // Agregar quien recibe el e-mail enviado
	}

	// Quien lo recibe
	$mail->addAddress('support@tele-metry.net', 'Support');   // Agregar quien recibe el e-mail enviado

	// Contenido

	$message = file_get_contents($template);
	$message = str_replace('{{client_name}}', $list_data_alert['client_name'], $message);
	$message = str_replace('{{tmp_value_sensor}}', $list_data_alert['tmp_value_sensor'], $message);
	$message = str_replace('{{tmp_value_accepted}}', $list_data_alert['tmp_value_accepted'], $message);
	$message = str_replace('{{lpa_value_sensor}}', $list_data_alert['lpa_value_sensor'], $message);
	$message = str_replace('{{lpa_value_accepted}}', $list_data_alert['lpa_value_accepted'], $message);


	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML
	// Activa la condificacción utf-8
	$mail->CharSet = 'UTF-8';

	$mail->Subject = $alert_subject;
	$mail->msgHTML($message);

	if ($mail->send()) {
		$json = array(
			'status' => 200,
			'message' => "Correo enviado correctamente"
		);
	} else {
		$json = array(
			'status' => 409,
			'message' => "Ocurrió un error al enviar el correo."
		);
	}
 
    echo json_encode($json, http_response_code($json['status']));

}

function sendemailContact(
	$server_mail_user,
	$server_mail_password,
	$list_mail_recipients,
	$prospect_name,
	$prospect_contact,
	$prospect_message,
	$prospect_subject,
	$template
) {
	// Configuración servidor SMTP
	$mail = new PHPMailer;
	$mail->isSMTP();                            // Set mailer to use SMTP
	$mail->SMTPDebug = false;
	$mail->Host = 'tele-metry.net';             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = $server_mail_user;        // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail->Password = $server_mail_password;    // Tu contraseña de gmail
	$mail->SMTPSecure = 'ssl';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = 587;							// Puerto TCP  para conectarse 

	/**Introduzca la dirección de la que debe aparecer el correo electrónico.
	 * Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. 
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente
	 * en lugar de la dirección de correo electrónico en sí.
	**/
    $mail->setFrom("support@tele-metry.net", "Nuevo Prospecto");
	/**Introduzca la dirección de la que debe responder.
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
	**/
	 $mail->addReplyTo("support@tele-metry.net"); 

	// $sql="select email from emails ";
	// foreach ($dbo->query($sql) as $row) {
	// 	$email->AddAddress($row[email]);
	// }

	foreach ($list_mail_recipients as $email => $name) {
		$mail->AddCC($email, $name); // Agregar quien recibe el e-mail enviado
	}

	// Quien lo recibe
	$mail->addAddress('support@tele-metry.net', 'Support');   // Agregar quien recibe el e-mail enviado
	//$mail->AddAddress('test-w7jmo4q07@srv1.mail-tester.com','Test');

	$mail->addCustomHeader('MIME-Version: 1.0');
 	

 
	// Contenido
	$message = file_get_contents($template);
	$message = str_replace('{{name_value}}', $prospect_name, $message);
	$message = str_replace('{{contact_value}}', $prospect_contact, $message);
	$message = str_replace('{{message_value}}', $prospect_message, $message);
	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML

	$mail->Subject = $prospect_subject;
	$mail->msgHTML($message);
	// Activa la condificacción utf-8
	$mail->CharSet = 'UTF-8';

	if ($mail->send()) {
		$json = array(
			'status' => 200,
			'message' => "Correo enviado correctamente "
		);
	} else {
		$json = array(
			'status' => 409,
			'message' => "Ocurrió un error al enviar el correo."
		);
	}
 
    echo json_encode($json, http_response_code($json['status']));

}

function sendEmailAlertEmail(
	$server_mail_user,
	$server_mail_password,
	$list_mail_recipients,
	$list_data_alert,
	$alert_subject,
	$client_name,
	$template
) {

	// Configuración servidor SMTP
	$mail = new PHPMailer;
	$mail->isSMTP();                            // Set mailer to use SMTP
	$mail->SMTPDebug = false;
	$mail->Host = 'tele-metry.net';             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = $server_mail_user;        // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail->Password = $server_mail_password;    // Tu contraseña de gmail
	$mail->SMTPSecure = 'ssl';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = 587;							// Puerto TCP  para conectarse  


	/**Introduzca la dirección de la que debe aparecer el correo electrónico.
	 * Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. 
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente
	 * en lugar de la dirección de correo electrónico en sí.
	**/
    $mail->setFrom("notifications@tele-metry.net", $client_name);
	/**Introduzca la dirección de la que debe responder.
	 * El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
	**/
	 $mail->addReplyTo("notifications@tele-metry.net"); 

	// $sql="select email from emails ";
	// foreach ($dbo->query($sql) as $row) {
	// 	$email->AddAddress($row[email]);
	// }


	foreach ($list_mail_recipients as $email => $name) {
		$mail->AddCC($email, $name); // Agregar quien recibe el e-mail enviado
	}

	// Quien lo recibe
	$mail->addAddress('support@tele-metry.net', 'Support');   // Agregar quien recibe el e-mail enviado

	// Contenido

	$message = file_get_contents($template);
	$message = str_replace('{{client}}', $list_data_alert['client'], $message);
	$message = str_replace('{{device}}', $list_data_alert['device'], $message);
	$message = str_replace('{{last_value}}', $list_data_alert['last_value'], $message);


	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML
	// Activa la condificacción utf-8
	$mail->CharSet = 'UTF-8';

	$mail->Subject = $alert_subject;
	$mail->msgHTML($message);

	if ($mail->send()) {
		$json = array(
			'status' => 200,
			'message' => "Correo enviado correctamente"
		);
	} else {
		$json = array(
			'status' => 409,
			'message' => "Ocurrió un error al enviar el correo."
		);
	}
 
    echo json_encode($json, http_response_code($json['status']));

}