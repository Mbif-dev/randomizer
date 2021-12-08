<?php

use Randomizer\Randomizer;

require __DIR__ .'/../vendor/autoload.php';
require __DIR__ .'/../src/autoload.php';
$db= new PDO('mysql:host=localhost:3308;dbname=test', 'root', '');
$randomizer= new Randomizer($db);
echo nl2br($randomizer->run(['ip','first_name','last_name',['first_name','gender=\'m\''],'phone_number','worker_info']));
?>