<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-type');

if(isset($_POST['token']))
{
    require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");
    require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/Contact.php");

    $user = new User();

    if($user->getUserByToken($_POST['token']))
    {
        $contacts = getAllContacts($user->getID());
        echo json_encode($contacts);
    }
    else
    {
        echo json_encode(array('error' => 'Utilisateur inconnue'));
    }

}
else
{
    echo json_encode(array('error' => 'Erreur de connexion'));
}