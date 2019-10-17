<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-type');

require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");

if(isset($_POST['email1']) AND isset($_POST['email2']) AND isset($_POST['pwd1']) AND isset($_POST['pwd2']))
{
    $user = new User();
    $sign_in_status = $user->signIn($_POST['email1'], $_POST['email2'], $_POST['pwd1'], $_POST['pwd2']);
    if($sign_in_status === true)
    {
        echo "Inscription réussite";
    }
    else
    {
        echo json_encode(array('error' => $sign_in_status));
    }
}
else
{
    http_response_code(404);
}