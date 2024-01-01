<?php
function detectError($attendus) {
    $errors = [];
    if (array_key_exists("send", $_POST)) {
        foreach($attendus as $attendu) {
            if($_POST[$attendu] == "") {
                array_push($errors, $attendu);
            }
        }
        if(count($errors) > 0){
            echo "erreur dans $errors[0]";
        } else {
            echo "pas d'erreur";
        }
    }
}

function checkError() {
    if(array_key_exists("send",$_POST)) {
        global $allVariable;
        global $errors;
        foreach($allVariable as $variable) {
            if ($_POST[$variable] == "") {
                array_push($errors, $variable);
            }
        }
    }
}
?>