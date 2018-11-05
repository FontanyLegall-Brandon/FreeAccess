<?php
header('Content-Type: application/json');
$success = false;
$data = array();
$duration = 0;
require '../bdd.php';
date_default_timezone_set('Europe/Paris'); //nous sommes en france ;)


function reponse_json($success, $data,$duration,$msgErreur=NULL) {
	$array['success'] = $success;
	$array['msg'] = $msgErreur;
	$array['duration']= $duration;
	$array['result'] = $data;

	echo json_encode($array);
}

function basic_reponse_json($success, $data,$msgErreur=NULL) {
    $array['success'] = $success;
    $array['msg'] = $msgErreur;
    $array['result'] = $data;

    echo json_encode($array);
}

function connected_reponse_json($success, $dataIG,$dataRoccade,$msgErreur=NULL) {
    $array['success'] = $success;
    $array['msg'] = $msgErreur;
    $array['in_game'] = $dataIG;
    $array['roccade'] = $dataRoccade;


    echo json_encode($array);
}

/**
 * La methode CallAPI permet de faire un appel à une API
 *
 * @param $method La methode (GET,POST,...)
 * @param $url L'url de l'API
 * @param bool $data les options de l'API : array("param" => "value") ==> index.php?param=value
 * @return le resultat de l'appel API
 */
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

/** La fonction checKey permet de check si la clef est valide
 *
 * @param $key string une clef
 * @param $database PDO ici $site
 * @return bool la reponse
 */
function checKey($key,$database){
    $resultat = false;

    $reponse = $database->query('SELECT * FROM api');
    while ($data = $reponse->fetch()) {
        if($key == $data['clef']){
            $resultat = true;
            break;
        }
    }
    $reponse->closeCursor();
    return $resultat;
}
