<?php
ini_set('display_errors', 1);

if (isset($_GET['registeredUsers'])) {
	require_once('../database/authenticate.class.php');
	$DB = new Authenticate();
	$DB->type = 'GetUsers';
	$DB->param = array();
	$result = $DB->Fetch();
	if ($result) {
		$row = '';
		$num_user = 0;
		foreach ($result as $user) {
			$num_user++;
			$row .= <<<HTML
            <tr>
              <th scope="row">$num_user</th>
              <td>$user[name]</td>
              <td>$user[birthdate]</td>
              <td>$user[kanoe_friend]</td>
            </tr>
HTML;
		}
		$html = <<<HTML
          <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nick</th>
              <th scope="col">Rok narození</th>
              <th scope="col">Kamarád na kánoj</th>
            </tr>
          </thead>
          <tbody>
            $row 
          </tbody>
        </table>
HTML;
		echo $html;
		die();
	} else {
		http_response_code(500);
		die();
	}

} else if (isset($_GET['check_nickname'], $_GET['nick'])) {
	require_once('../database/authenticate.class.php');
	$nick = trim(strip_tags($_GET['nick']));
	$DB = new Authenticate();
	if ($DB->isDuplicateOrInvalidNick($nick)) {
		echo json_encode(array("ErrorCode" => "10000", 'Message' => 'Uzivatel uz existuje.'));
		http_response_code(400);
		die();
	}
} else if (isset($_GET['getWebhook'], $_GET['id'])) {
	require_once('../database/authenticate.class.php');
	$DB = new Authenticate();
	$DB->type = "GetWebhook";
	$DB->param = array(trim(strip_tags($_GET['id']))) ;
	$result = $DB->Fetch();

	if($result) {
		echo json_encode(array("webhookLink"=>$result[0]['webhookLink']));
		die();
	} else { 
		http_response_code(400);
		die();
	}
}
?>