<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-type');

if(isset($_POST['token']))
{
    if(isset($_POST['forName']))
    {
        require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/Contact.php");

        $token = htmlspecialchars($_POST['token']);
        $forname = htmlspecialchars($_POST['forName']);
        $name = null;
        $phonePro = null;
        $phonePerso = null;
        $emailPro = null;
        $emailPerso = null;
        $linkendin = null;
        $facebook = null;
        $twitter = null;
        $website = null;

        if(isset($_POST['name']))
        {
            $name = htmlspecialchars($_POST['name']);
        }
        if(isset($_POST['phonePro']))
        {
            $phonePro = htmlspecialchars($_POST['phonePro']);
        }
        if(isset($_POST['phonePerso']))
        {
            $phonePerso = htmlspecialchars($_POST['phonePerso']);
        }
        if(isset($_POST['emailPro']))
        {
            $emailPro = htmlspecialchars($_POST['emailPro']);
        }
        if(isset($_POST['emailPerso']))
        {
            $emailPerso = htmlspecialchars($_POST['emailPerso']);
        }
        if(isset($_POST['linkendin']))
        {
            $linkendin = htmlspecialchars($_POST['linkendin']);
        }
        if(isset($_POST['facebook']))
        {
            $facebook = htmlspecialchars($_POST['facebook']);
        }
        if(isset($_POST['twitter']))
        {
            $twitter = htmlspecialchars($_POST['twitter']);
        }
        if(isset($_POST['website']))
        {
            $website = htmlspecialchars($_POST['website']);
        }
        $contact = new Contact();
        $add_contact_status = $contact->add($token, $forname, $name, $phonePro, $phonePerso, $emailPro, $emailPerso, $linkendin,
            $facebook, $twitter, $website);
        echo $add_contact_status;

    }
    else
    {
        echo json_encode(array('error' => 'Merci de renseigner le prénom du contact.'));
    }
}
else
{
    echo json_encode(array('error' => 'Erreur de connexion'));
}