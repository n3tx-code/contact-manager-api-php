<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-type');

require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");

if(isset($_POST['email']) AND isset($_POST['pwd'])) {
    $user = new User();
    $login_status = $user->logIn($_POST['email'], $_POST['pwd']);
    if($login_status === true)
    {
        echo $user->getToken();
    }
    else
    {
        echo json_encode(array('error' => htmlentities($login_status)));
    }
}
else
{
    http_response_code(404);
}
