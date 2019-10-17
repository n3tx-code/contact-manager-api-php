<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/_includes/bdd.php");

class User
{
    private $ID;
    private $email;
    private $token;

    function signIn($email1, $email2, $pwd1, $pwd2)
    {
        if(!empty($email1) AND !empty($email2))
        {
            if($email1 == $email2)
            {
                $email = htmlspecialchars($email1);
                if(emailIsUnique($email))
                {
                    if(!empty($pwd1) AND !empty($pwd2)) {
                        if ($pwd1 == $pwd2) {
                            $pwd = hash('sha256', $pwd1);
                            $create_user = $GLOBALS['bdd']->prepare("INSERT INTO user (email, pwd, token) VALUES (?, ?, ?) ");
                            $create_user->execute(array($email, $pwd, generateToken()));
                            if ($create_user->errorCode() == "00000") {
                                return true;
                            }
                            return "Erreur lors de la création de l'utilisateur";
                        }
                        else
                        {
                            return "Mot de passe différent";
                        }
                    }
                    else
                    {
                        return "Merci de remplir tous les champs";
                    }

                }
                else
                {
                    return "Adresse mail déjà utilisée";
                }
            }
        }
        else
        {
            return "Merci de remplir tous les champs";
        }
    }

    function logIn($email, $pwd)
    {
        // if is not unique means we've a account with this email
        if(!emailIsUnique($email))
        {
            $email = htmlspecialchars($email);
            $pwd = $pwd = hash('sha256', $pwd);
            $req_user = $GLOBALS['bdd']->prepare("SELECT * FROM user WHERE email = ? AND pwd = ?");
            $req_user->execute(array($email, $pwd));
            $user = $req_user->fetch();
            if($user)
            {
                $this->ID = $user['ID'];
                $this->email = $user['email'];
                $this->token = $user['token'];

                return true;
            }
            else
            {
                return "Mot de passe incorrect";
            }
        }
        else
        {
            return "Email inconnue";
        }
    }

    function getToken()
    {
        return $this->token;
    }
}

function emailIsUnique($email)
{
    $req_email = $GLOBALS['bdd']->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
    $req_email->execute(array($email));

    if($req_email->fetchColumn() == 0)
    {
        return true;
    }

    return false;
}

function generateToken()
{
    $token_is_unique = false;
    $token = "";
    while(!$token_is_unique)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 17; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $req_token = $GLOBALS['bdd']->prepare("SELECT COUNT(*) FROM user WHERE token = ?");
        $req_token->execute(array($randomString));
        if($req_token->fetchColumn() == 0)
        {
            $token = $randomString;
            $token_is_unique = true;
        }
    }
    return $token;

}