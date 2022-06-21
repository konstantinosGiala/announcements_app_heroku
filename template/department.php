<?php
    // $data = json_decode(file_get_contents("http://localhost:8000/department/list"),true);

    require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

    include $_SERVER['DOCUMENT_ROOT'].'/connect.php';
    
    // Uncomment for localhost running
    $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();

    $MDB_USER = $_ENV['MDB_USER'];
    $MDB_PASS = $_ENV['MDB_PASS'];
    $ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

    $connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);
    $collection = $connection->connect_to_department();
    $data = $collection->find()->toArray();
    
    // echo gettype($data)."\n";
    
    // print_r ($data);
    // foreach($data as $value) {
    //     echo  $value['name']."\n";
    // }
?>

<html>
    <head>
        <title>Departments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    
    <body>
        <table border="1px">
            <tr>
                <th>Διεύθυνση</th>
                <th>Αναγνωριστικό</th>
            </tr>
            <?php
                foreach($data as $value) {
                    echo '<tr>';
                        echo '<td>'.$value['name'].'</td>';
                        echo '<td>'.$value['identifier'].'</td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </body>
</html>