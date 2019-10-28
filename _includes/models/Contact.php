<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/bdd.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");

class Contact
{
    private $forname;
    private $name;
    private $phonePro;
    private $phonePerso;
    private $emailPro;
    private $emailPerso;
    private $linkendin;
    private $facebook;
    private $twitter;
    private $website;
    private $img;

    function create($token, $forname, $name = null, $phonePro = null, $phonePerso = null, $emailPro = null, $emailPerso = null,
        $linkendin = null, $facebook = null, $twitter = null, $website = null, $img = null)
    {
        if($emailPerso)
        {
           if(!filter_var($emailPerso, FILTER_VALIDATE_EMAIL))
           {
               return json_encode(array('error' => 'Email personnel non valide'));
           }
        }
        if($emailPro)
        {
            if(!filter_var($emailPro, FILTER_VALIDATE_EMAIL))
            {
                return json_encode(array('error' => 'Email professionel non valide'));
            }
        }

        $user = new User();
        if(!$user->getUserByToken($token))
        {
            $add_contact = $GLOBALS['bdd']->prepare("INSERT INTO contact (ID_owner, name, forname, phone_pro, phone_perso, 
            email_pro, email_perso, linkendin, facebook, twitter, website, contact_img)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $add_contact->execute(array(
                $user->getID(),
                $name,
                $forname,
                $phonePro,
                $phonePerso,
                $emailPro,
                $emailPerso,
                $linkendin,
                $facebook,
                $twitter,
                $website,
                $img
            ));
            if($add_contact->errorCode() == '00000')
            {
                return "Contact added";
            }
            else
            {
                return json_encode(array('error' => 'Erreur lors de l\'ajout du contact. Merci d\'essayer à nouveau.'));
            }
        }

    }
}