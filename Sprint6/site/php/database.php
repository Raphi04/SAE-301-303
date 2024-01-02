<?php
try{
    $db = new PDO(
        'mysql:host=localhost;dbname=acf2l;charset=utf8','root'
    );
    $db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e){
    die(print_r($e));
}



/*$DBMail=array();
$getMailSql = 'SELECT mail FROM user';
$getMail = $db->prepare($getMailSql);
$getMail->execute() or die($db->errorInfo());
$results = $getMail->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $value){
    $DBMail[]=$value["mail"];
}*/

?>