<?php include 'header-scripts.php'; ?>
<?php
    include dirname(__FILE__,2).'/model/User.php';
    include dirname(__FILE__,2).'/model/UserCategory.php';

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function saveUser($data){
        global $user;
        
        $data = json_decode(json_encode($data));
        $result = $user->createUser($data);
        return $result;
    }
    
    $user = new User($connection);
    $usercategory = new UserCategory($connection);
    header('Content-Type: text/html; charset=utf-8');

    // // define variables and set to empty values
    $usernameErr = $passwordErr = $nameErr = $surnameErr = $emailErr = $identifierErr = "";
    $username = $password = $name = $surname = $email = $identifier = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace or Greek letters
            if (!preg_match("/^[a-zA-Z\p{Greek}]+$/u",$name)) {
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
                'identifier' => $identifier,
                'name' => $name
            );
            $result = saveUser($data);
        }
    }

    $data = json_decode($user->showUsers(), true);
    $dataUsersCategory = json_decode($usercategory->showUsercategories(), true);
?>

<?php include 'header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="align-self-center">
            <div class="card card-body"> 

                <h2>Εισαγωγή νέου Department</h2>
                <!-- <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
                <p><span class="text-danger">* required field</span></p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
                    Username: <input type="text" name="username" value="<?php echo $username;?>">
                    <span class="text-danger">* <?php echo $usernameErr;?></span>
                    <br><br>
                    Password: <input type="text" name="password" value="<?php echo $password;?>">
                    <span class="text-danger">* <?php echo $passwordErr;?></span>
                    <br><br>
                    <select name="identifier" id="identifier">
                        <option value="" default>Επιλέξτε Κατηγορία Χρήστη</option>
                        <?php 
                            foreach($dataUsersCategory as $value) {
                                echo '<option value="'.$value['identifier'].'">'.$value['name']."</option>";
                            } 
                        ?>
                    </select>
                    <span class="text-danger">* <?php echo $identifierErr;?></span>
                    <br><br>
                    Name: <input type="text" name="name" value="<?php echo $name;?>">
                    <span class="text-danger">* <?php echo $nameErr;?></span>
                    <br><br>
                    Surname: <input type="text" name="surname" value="<?php echo $surname;?>">
                    <span class="text-danger">* <?php echo $surnameErr;?></span>
                    <br><br>
                    Email: <input type="text" name="email" value="<?php echo $email;?>">
                    <span class="text-danger">* <?php echo $emailErr;?></span>
                    <br><br>
                    <input type="submit" name="submit" value="Submit">  
                </form>
            
                <hr>
            
                <table class="table">
                    <tr>
                        <th>Username</th>
                        <th>Όνομα</th>
                        <th>Επίθετο</th>
                        <th>Κατηγορία</th>
                        <th>Email</th>
                    </tr>
                    <?php
                        foreach($data as $value) {
                            echo '<tr>';
                                echo '<td>'.$value['username'].'</td>';
                                echo '<td>'.$value['name'].'</td>';
                                echo '<td>'.$value['surname'].'</td>';
                                echo '<td>'.$value['user_category']['name'].'</td>';
                                echo '<td>'.$value['email'].'</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>