<?php
header("Access-Control-Allow-Origin: *");
require 'api_config.php';
//966316cc3b1cfe80bota796046391dff

if(isset($_GET['password'])){

    if(checKey($_GET['password'],$site) == true) {

       if(isset($_GET['hexa']) AND isset($_GET['discordid'])) {
            $array = Array();

            if (preg_match('/steam:[a-zA-Z0-9]{15}/', $_GET['hexa']))
            {
                $reponse = $site->query('SELECT * FROM user WHERE hexa="' . $_GET['hexa'] . '"');
                $userdata = $reponse->fetch();
                $reponse->closeCursor();

                //print_r($userdata);

                if ($userdata==null) {
                    $data = $array;
                    $msg = "Vous devez vous vous connecter sur notre site au moins une fois";
                    $success = 0;

                }else {
                    if ($userdata['ban'] == 0) {

                        $stmt = $serveur->prepare("SELECT * FROM user_whitelist WHERE identifier = ?");
                        $bddwhitelist = $stmt->execute(array($userdata['hexa']));
                        $bddwhitelist = $stmt->fetch();

                        if($bddwhitelist['identifier'] == null) {
                            /*$stmt = $serveur->prepare("INSERT INTO user_whitelist(nom_rp, identifier) VALUES (?, ?)");
                            $bddsteamid = $stmt->execute(array($userdata['name'], $userdata['hexa']));
*/
                            $query = $site->prepare('UPDATE user SET discordid = ? WHERE id = ?');
                            $query->execute(array($_GET['discordid'], $userdata['id']));



                                $stmt = $site->prepare("INSERT INTO freeaccess(userid) VALUES (?)");
                                $bddsteamid = $stmt->execute(array($userdata['id']));


                            $data = $array;
                            $msg = "Bienvenue !";
                            $success = 1;

                        }else{
                            $data = $array;
                            $msg = "Vous êtes actuellement déjà whitelist";
                            $success = 0;
                        }




                    } else {
                        $data = $array;
                        $msg = "Vous êtes actuellement ban";
                        $success = 2;
                    }
                }

            }
            else
            {
                $data = $array;
                $msg = "Merci de bien vouloir rentrer un Steam Hexa valide du type steam:AAAAAAAAAAAAAAA";
                $success = 0;
            }

        }


    }else {
        $msg = "API - Le mot de passe est incorrect";
        $success = 0;
    }
} else {
    $msg = "API - Il manque des informations";
    $success = 0;
}

basic_reponse_json($success, $data, $msg);

//https://gta-fivelife.fr/api/players.php?password=966316cc3b1cfe80a78a796046391dff

