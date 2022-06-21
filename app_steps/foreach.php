<html>
    <body>
        <strong>
        <?php
            for ( $i =1; $i <=5; $i ++) {
                echo "Hello World! <br/>";
            }
        ?>
        </strong>
        <br>
        <?php
            $x=array ("one", "two", "three");
            foreach ( $x as $value ) {
                echo $value . "<br/>";
            }
        ?>
    </body>
</html>