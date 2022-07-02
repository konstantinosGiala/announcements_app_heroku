<?php
    require dirname(__FILE__,2).'/vendor/autoload.php';
    include dirname(__FILE__,2).'/connect.php';
    
    // Uncomment for localhost running
    // $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
    // $dotenv->load();

    $MDB_USER = $_ENV['MDB_USER'];
    $MDB_PASS = $_ENV['MDB_PASS'];
    $ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

    $connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);
    $collection = $connection->connect_to_department();
    $data = $collection->find()->toArray();
?>