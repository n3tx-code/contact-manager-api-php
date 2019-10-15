<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");
if(isset($_POST['sigIn']))
{
    $user = new User();
    $sign_in_status = $user->signIn($_POST['email'], $_POST['pwd1'], $_POST['pwd2']);
    if($sign_in_status === true)
    {
        echo json_encode(array('success' => htmlentities("Inscription réussite")));
    }
    else
    {
        echo json_encode(array('error' => htmlentities($sign_in_status)));
    }
}
else
{
    http_response_code(404);
}