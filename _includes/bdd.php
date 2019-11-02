<?php
if( count(get_included_files()) <= 1)
{
    header("Location: /");
}
else
{
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=contact_manager;charset=utf8', 'root', '');
        $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch (Exception $e)
    {
        die('Erreur de connexion avec la base de données ');
        die('Erreur : ' . $e->getMessage());
    }
}
?>