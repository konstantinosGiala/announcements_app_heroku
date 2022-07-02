<?php include 'header-scripts.php'; ?>
<?php
    include dirname(__FILE__,2).'/model/Department.php';
    include dirname(__FILE__,2).'/model/Subdepartment.php';

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function saveSubdepartment($data){
        global $subdepartment;
        
        $data = json_decode(json_encode($data));
        $result = $subdepartment->createSubdepartment($data);
        return $result;
    }
    
    $department = new Department($connection);
    $subdepartment = new Subdepartment($connection);
    header('Content-Type: text/html; charset=utf-8');

    // // define variables and set to empty values
    $nameErr = $identifierErr = "";
    $name = $identifier = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace or Greek letters
            if (!preg_match("/^[a-zA-Z\p{Greek}\s]+$/u",$name)) {
            $nameErr = "Only letters and white space allowed";
            }
        }
         
        if (empty($_POST["identifier"])) {
            $identifierErr = "Identifier is required";
        } else {
            $identifier = test_input($_POST["identifier"]);
            // check if identifier is number
            if (!is_numeric($identifier)) {
            $identifierErr = "Invalid identifier format";
            }
        }

        if (empty($identifierErr) && empty($nameErr)){
            $data = array(
                'identifier' => intval($identifier),
                'name' => $name
            );
            $result = saveSubdepartment($data);
        }
    }

    $allDepartments = json_decode($department->showDepartments(), true);
?>

<?php include 'header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="align-self-center">
            <div class="card card-body"> 

                <h2>Εισαγωγή νέου Τμήματος</h2>
                <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
                <p><span class="text-danger">* required field</span></p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
                    <select name="identifier" id="identifier">
                        <option value="" default>Επιλέξτε Διεύθυνση</option>
                        <?php 
                            foreach($allDepartments as $value) {
                                echo '<option value="'.$value['identifier'].'">'.$value['name']."</option>";
                            } 
                        ?>
                    </select>
                    <span class="text-danger">* <?php echo $identifierErr;?></span>
                    <br><br>
                    Name: <input type="text" name="name" value="<?php echo $name;?>">
                    <span class="text-danger">* <?php echo $nameErr;?></span>
                    <br><br>
                    <input type="submit" name="submit" value="Submit">  
                </form>
                
                <hr>
                
                <table class="table">
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
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>