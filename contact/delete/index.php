<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-type');

if(isset($_POST['token']) AND isset($_POST['ID']) AND isset($_POST['ID_owner']))
{
    require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/Contact.php");
    $contact = new Contact();
    $update_contact_status = $contact->delete($_POST['token'], $_POST['ID'], $_POST['ID_owner']);
    echo $update_contact_status;
}
else
{
    echo json_encode(array('error' => 'Erreur de connexion'));
}