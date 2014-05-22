<?php

$query = $pdo->prepare("TRUNCATE {$tablePrepend}val");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}attr");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}object");
$result = $query->execute();

$query = $pdo->prepare("TRUNCATE {$tablePrepend}page");
$result = $query->execute();
