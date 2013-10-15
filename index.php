<?php

	define("ACTIVECAMPAIGN_URL", "https://staging-mthommes.api-us1.com");
	define("ACTIVECAMPAIGN_API_KEY", "3693354bb517da0392bd568c1cdcb2c5b2bd546d1dd1b373f82a7cda9850c04c2d126b9c");

	require_once("../../activecampaign-api-php/includes/ActiveCampaign.class.php");
	$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

	$form_embed_params = array(
		"id" => 1157,
		"action" => "",
		"ajax" => 0,
		"css" => 1,
	);

	// perform sync (or swim? ;)
	// if 0, it does an add/edit
	$sync = 0;

	function dbg($var, $continue = 0, $element = "pre") {
	  echo "<" . $element . ">";
	  echo "Vartype: " . gettype($var) . "\n";
	  if ( is_array($var) ) echo "Elements: " . count($var) . "\n\n";
	  elseif ( is_string($var) ) echo "Length: " . strlen($var) . "\n\n";
	  print_r($var);
	  echo "</" . $element . ">";
		if (!$continue) exit();
	}

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

	// check for subscriber visiting this page (to preload their data).
	$load_contact = false;
	if (isset($_GET["hash"])) {
		$load_contact = true;
		$hash = $_GET["hash"];
		$contact = $ac->api("contact/view?hash={$hash}");
//dbg($contact);
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

	<?php
	
		if ($load_contact) {
	
			?>

			<script>

				$(document).ready(function() {

					//$("#_form_<?php echo $form_embed_params["id"]; ?>")

				});

			</script>

			<?php

		}

	?>

</body>

</html>