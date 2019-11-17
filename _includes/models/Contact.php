<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/bdd.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/models/User.php");

class Contact
{
    public $ID;
    public $ID_owner;
    public $forname;
    public $name;
    public $phonePro;
    public $phonePerso;
    public $emailPro;
    public $emailPerso;
    public $linkendin;
    public $facebook;
    public $twitter;
    public $website;
    public $lastModification;

    function create($id, $ID_owner, $forname, $name, $phonePro, $phonePerso, $emailPro, $emailPerso, $linkendin, $facebook, $twitter,
                    $website, $last_modification)
    {
        $this->ID = $id;
        $this->ID_owner = $ID_owner;
        $this->forname = $forname;
        $this->name = $name;
        $this->phonePro = $phonePro;
        $this->phonePerso = $phonePerso;
        $this->emailPro = $emailPro;
        $this->emailPerso = $emailPerso;
        $this->linkendin = $linkendin;
        $this->facebook = $facebook;
        $this->twitter = $twitter;
        $this->website =  $website;
        $this->lastModification = $last_modification;
    }
    
    function add($token, $forname, $name = null, $phonePro = null, $phonePerso = null, $emailPro = null, $emailPerso = null,
        $linkendin = null, $facebook = null, $twitter = null, $website = null)
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
        if($user->getUserByToken($token))
        {
            $update_contact = $GLOBALS['bdd']->prepare("INSERT INTO contact (ID_owner, name, forname, phone_pro, phone_perso, 
            email_pro, email_perso, linkendin, facebook, twitter, website)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $update_contact->execute(array(
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
            ));
            if($update_contact->errorCode() == '00000')
            {
                return "Contact added";
            }
            else
            {
                return json_encode(array('error' => 'Erreur lors de l\'ajout du contact. Merci d\'essayer à nouveau.'));
            }
        }
        else
        {

            return json_encode(array('error' => 'Utilisateur inconnue'));
        }
    }

    function update($token, $contact_ID, $ID_owner, $forname, $name = null, $phonePro = null, $phonePerso = null, $emailPro = null, $emailPerso = null,
                 $linkendin = null, $facebook = null, $twitter = null, $website = null)
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
        if($user->getUserByToken($token))
        {
            if($user->getID() == $ID_owner)
            {
                $update_contact = $GLOBALS['bdd']->prepare("
                UPDATE contact
                set name = ?,
                forname = ?,
                phone_pro = ?,
                phone_perso = ?, 
                email_pro = ?,
                email_perso = ?,
                linkendin = ?,
                facebook = ?,
                twitter = ?,
                website = ?,
                last_modification_date = ?
                WHERE ID = ? AND ID_owner = ?");
                $update_contact->execute(array(
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
                    date("Y-m-d H:i:s"),
                    $contact_ID,
                    $user->getID(),
                ));
                if($update_contact->errorCode() == '00000')
                {
                    return "Contact modified";
                }
                else
                {
                    return json_encode(array('error' => 'Erreur lors de la mise à jour du contact. Merci d\'essayer à nouveau.'));
                }
            }
            else
            {
                return json_encode(array('error' => 'Contact inconnue'));
            }
        }
        else
        {

            return json_encode(array('error' => 'Utilisateur inconnue'));
        }
    }

    function delete($token, $ID_contact, $ID_owner)
    {
        $user = new User();
        $user->getUserByToken($token);
        if($user->getID() == $ID_owner)
        {
            $delete_contact = $GLOBALS['bdd']->prepare("DELETE FROM contact WHERE ID = ?");
            $delete_contact->execute(array($ID_contact));

            if($delete_contact->errorCode() == '00000')
            {
                return "Contact deleted";
            }
            else
            {
                return json_encode(array('error' => 'Erreur lors du contact. Merci d\'essayer à nouveau.'));
            }
        }
        else
        {
            return json_encode(array('error' => 'Utilisateur inconnue'));
        }
    }
}

function getAllContacts($userID)
{
    $contacts = array();
    $req_contacts = $GLOBALS['bdd']->prepare('SELECT * FROM contact WHERE ID_owner = ? ORDER BY forname');
    $req_contacts->execute(array($userID));
    $sql_contacts = $req_contacts->fetchAll();
    foreach ($sql_contacts as &$contact)
    {
        $c = new Contact();
        $c->create($contact['ID'], $userID, $contact['forname'], $contact['name'], $contact['phone_pro'],
            $contact['phone_perso'],$contact['email_pro'], $contact['email_perso'],  $contact['linkendin'],
            $contact['facebook'], $contact['twitter'], $contact['website'], $contact['last_modification_date']);
        array_push($contacts, $c);
    }
    return $contacts;

}