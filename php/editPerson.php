<?php
require_once "../config.php";
require_once "functions.php";
require_once "dbsFunctions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    $id = $_POST['_personId'];
    $result = [
        'name' => $_POST['name'],
        'surname' => $_POST['surname'],
        'sex' => $_POST['sex'],
        'org' => $_POST['org'],
        'birth' => $_POST['birth'],
        'death' => $_POST['death'],
        'country' => $_POST['country'],
        'year' => $_POST['year'],
        'category' => $_POST['category'],
        'contribution_en' => $_POST['contribution_en'],
        'contribution_sk' => $_POST['contribution_sk'],
        'language_en' => $_POST['language_en'],
        'language_sk' => $_POST['language_sk'],
        'genre_en' => $_POST['genre_en'],
        'genre_sk' => $_POST['genre_sk']
    ];

    if (!dbs\checkInfo($result)) {
        echo "fail";
    }
    else if ($method === "PATCH") {
        if(editPerson($id, $dbconfig))
            echo "success";
        else 
            echo "failed";
    }
}

function editPerson($id, $config) {
    
}