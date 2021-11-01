<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File IO Example in PHP</title>
</head>
<body><?php

function sanitizeString($field) {
    return filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
}

$submitPressed = sanitizeString('submit');

// Display the form if the form has NOT yet been submitted
if (!isset($submitPressed)) {

    // Display the form using a Here Document
    echo <<<MYFORM

  <form action="$_SERVER[PHP_SELF]" method="post">

    <label for="stateName">State Name:</label>
    <input type="text" name="stateName" id="stateName" value="">
    <br><br>

    <label for="stateBird">State Bird:</label>
    <input type="text" name="stateBird" id="stateBird">
    <br><br>
    
    <label for="stateAnimal">State Animal:</label>
    <input type="text" name="stateAnimal" id="stateAnimal">
    <br><br>
    
    <label for="stateMotto">State Motto:</label>
    <textarea name="stateMotto" id="stateMotto"></textarea>
    
    <br><br>

    <input type="submit" name="submit" value="Go">
 
</form>

MYFORM;

} else {   // form was submitted

    // process form's data
    //echo "<script>console.log(\"hello\")</script>";

    // Open a connection to the file to append data to it
    $fp = fopen("inputFolder/myStuff.txt", "a");

    $outputLine = "";

    //fwrite($fp, "Hello world" . PHP_EOL);

    //Step thru the $_POST array to get the form data and add it to output line
    foreach (filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING) as $value) {

        //Check if form field value is not the submit button
        if ($value != $submitPressed) {
            $outputLine .= $value . ":";
        }

    } //end foreach
    //echo "<p>Output line = $outputLine</p>";

    //Remove the trailing colon from the end of $outputline
    $outputLine = rtrim($outputLine, ":");
    //echo "<p>Output line = $outputLine</p>";

    //Output $outputLine to the end of our file
    fwrite($fp, $outputLine . PHP_EOL);

    //Close the connection to the file
    fclose($fp);


    //Next lets reopen the file in read only mode
    //line by line in a table
    ?>
    <table border="1">
        <tr>
            <th>State Name</th>
            <th>State Bird</th>
            <th>State Animal</th>
            <th>State Motto</th>
        </tr>
      <?php

    $fp = fopen("inputFolder/myStuff.txt", "r");

    //Read our output lines and output them
    //as cells in a table row, one table row per line of data
    //
    // while not EOF, read next line
    while (!feof($fp)) {

        //Read the next line pointed to by the file pointer
        $line = rtrim(fgets($fp));

        //echo "<h3>$line</h3>";

      // is the line of data valid?
      if ($line != "") {

        // Break up the line into its individual fields
        //and store the values from left to right into
        //descriptive variable names via a list literal
        list($stateName, $stateBird, $stateAnimal, $stateMotto) = explode(":", $line);

        //Put the fields into the table row
        $dataRow = <<<DATAROW
        <tr>
            <td>$stateName</td>
            <td>$stateBird</td>
            <td>$stateAnimal</td>
            <td>$stateMotto</td>        
        </tr>
DATAROW;
        echo $dataRow;

      }

    }//end of while
        ?>
    </table>

<?php

}

?>

</body>
</html>