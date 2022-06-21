<html>
    <body>
        <strong>
        <?php
            $i=0;
            while ($i <= 5)
                { 
                    echo "Number : " . $i . "<br/>";
                    $i++;
            }
        ?>
        </strong>
        <br>
        <?php
            $i=1;
            do {
                $i++;
                echo "The number is " . $i . "<br/>";
            } while ($i <= 5);
        ?>
    </body>
</html>