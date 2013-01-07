<?php

	define("ACTIVECAMPAIGN_URL", "");
	define("ACTIVECAMPAIGN_API_KEY", "");

	require_once("../../activecampaign-api-php/includes/ActiveCampaign.class.php");
	$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

	$form_embed_params = array(
		"id" => 1026,
		"action" => "",
		"ajax" => 0,
		"css" => 1,
	);

	// perform sync (or swim? ;)
	// if 0, it does an add/edit
	$sync = 0;

	$api_params = array();
	foreach ($form_embed_params as $var => $val) {
		$api_params[] = $var . "=" . $val;
	}

	$form_process = $ac->api("form/process?sync={$sync}");

	if ($form_process && (int)$form_embed_params["ajax"]) {
		// form submitted via ajax
		echo $form_process;
		exit;
	}

?>

<html>

<head>

	<style type="text/css">

		#form_result_message {
			font-weight: bold;
			margin-bottom: 30px;
		}

	</style>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

</head>

<body>

	<div id="form_result_message">

		<?php

			if ($form_process) {
				// form submitted
				$form_process = json_decode($form_process);
				echo $form_process->message;
			}

		?>

	</div>

	<?php

		$form_html = $ac->api("form/embed?" . implode("&", $api_params));
		echo $form_html;

	?>

</body>

</html>