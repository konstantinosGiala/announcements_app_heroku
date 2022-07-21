<?php
    require dirname(__FILE__,2).'/vendor/autoload.php';

    include dirname(__FILE__,2).'/connect.php';
    include dirname(__FILE__,2).'/helper_files/GeneralFunctions.php';
    include dirname(__FILE__,2).'/model/Department.php';
    include dirname(__FILE__,2).'/model/Categories.php';
    
    // Uncomment for localhost running
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
    $dotenv->load();

    $MDB_USER = $_ENV['MDB_USER'];
    $MDB_PASS = $_ENV['MDB_PASS'];
    $ATLAS_CLUSTER_SRV = $_ENV['ATLAS_CLUSTER_SRV'];

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function saveCategories($data){
        global $categories;
        
        $data = json_decode(json_encode($data));
        $result = $categories->createCategories($data);
        return $result;
    }
    
    $connection = new Connection($MDB_USER, $MDB_PASS, $ATLAS_CLUSTER_SRV);
    $department = new Department($connection);
    $categories = new Categories($connection);
    header('Content-Type: text/html; charset=utf-8');

    // // define variables and set to empty values
    $departmentErr = $subdepartmentErr = $categoriesErr = "";
    $frmDepartment = $frmSubdepartment = $frmCategories = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $category_name = $_POST["frmCategories"];
        $department_identifier = explode("-", $_POST["frmDepartment"]);
        $department_identifier = $department_identifier[1];
        $subdepartment_id = $_POST["frmSubdepartment"];
         
        if (empty($category_name)) {
            $categoriesErr = "Name is required";
        } else {
            $category_name = test_input($category_name);
            // check if name only contains letters and whitespace or Greek letters
            if (!preg_match("/^[a-zA-Z\p{Greek}\s]+$/u",$category_name)) {
            $categoriesErr = "Only letters and white space allowed";
            }
        }
        
        if (empty($department_identifier)) {
            $departmentErr = "Department is required";
        } else {
            $department_identifier = test_input($department_identifier);
            // check if identifier is number
            if (!is_numeric($department_identifier)) {
            $departmentErr = "Invalid Department format";
            }
        }

        if (empty($subdepartment_id)) {
            $subdepartmentErr = "Subdepartment is required";
        } else {
            $subdepartment_id = test_input($subdepartment_id);
            // check if subdepartment is string
            if (!is_string($subdepartment_id)) {
            $subdepartmentErr = "Invalid Subdepartment format";
            }
        }

        if (empty($departmentErr) && empty($subdepartmentErr) && empty($categoriesErr)){
            $data = array(
                'identifier' => $department_identifier,
                'subdepartment_id' => $subdepartment_id,
                'name' => $category_name
            );
            // print_r($data);
            $result = saveCategories($data);
        }
    }

    $allDepartments = json_decode($department->showDepartments(),true);
    $allDepartments = json_decode($allDepartments['data'],true);

    // print_r($allDepartments);
?>

<!DOCTYPE HTML>  
<html>
    <head>
        <title>Category</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
            .error {color: #FF0000;}
        </style>
    </head>
    <body>  

        <h2>Εισαγωγή νέου Category</h2>
        <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
        <p><span class="error">* required field</span></p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
            
            <select name="frmDepartment" onchange="findSubdepartment(this)">
                <option value="" default>Επιλέξτε Διεύθυνση</option>
                <?php 
                    foreach($allDepartments as $value) {
                        echo '<option value="'.$value['_id']['$oid']."-".$value['identifier'].'">'.$value['name']."</option>";
                    } 
                ?>
            </select>
            <span class="error">* <?php echo $departmentErr;?></span>
            <br><br>
            
            <select name="frmSubdepartment" id="frmSubdepartment">
                <option value="" default>Επιλέξτε Τμήμα</option>
            </select>
            <span class="error">* <?php echo $subdepartmentErr;?></span>
            <br><br>
            
            Name: <input type="text" name="frmCategories" value="<?php echo $frmCategories;?>">
            <span class="error">* <?php echo $categoriesErr;?></span>
            <br><br>
            <input type="submit" name="submit" value="Submit">  
        </form>
        
        <hr>
        
        <table border="1px">
            <tr>
                <th>Διεύθυνση</th>
                <th>Αναγνωριστικό</th>
                <th>Τμήματα</th>
                <th>Κατηγορίες</th>
            </tr>
            <?php
                foreach($allDepartments as $value) {
                    echo '<tr>';
                        echo '<td>'.$value['name'].'</td>';
                        echo '<td>'.$value['identifier'].'</td>';
                        echo '<td>';
                            foreach ($value['subdepartment'] as $svalue){
                                echo $svalue['name']."<br>";
                            }
                        echo '</td>';
                        echo '<td>';
                            foreach ($value['categories'] as $cvalue){
                                echo $cvalue['name']."<br>";
                            }
                        echo '</td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </body>
    <script>
        function findSubdepartment(selectObject){
            var value = selectObject.value;
            value = value.split("-");  
            url = `/subdepartment/${value[0]}/list`;
            // console.log(url);
            
            $.getJSON(url, function(data){
                data = JSON.parse(data['data']);
                subdepartment = data['subdepartment']
                // console.log(subdepartment);
                
                $('#frmSubdepartment').empty();
                $('#frmSubdepartment').append($('<option>', { 
                    value: "",
                    text : "Επιλέξτε Τμήμα" 
                }));
                
                $.each(subdepartment, function (index, value) {
                    name = value['name'];
                    id = value['_id']['$oid'];
                    // console.log("1>>",id, name);
                    $('#frmSubdepartment').append($('<option>', { 
                        value: id,
                        text : name 
                    }));
                });
            });
        }
    </script>
</html>