<?php
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "foodweb";

// session_start();
$con = new mysqli($db_host, $db_username, $db_password, $db_name);


if (!$con->connect_error) 
{
    // echo "connect";
}
else{
    echo "error";
}

function filteration($data)
{
    foreach ($data as $key => $value) {
        // trim() remove extra spaces
        // stripslashes() remove backword slashes
        // htmlspecialchars() convert special character to html entities
        // strip_tags() remove html tags
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = strip_tags($value);
        
        $data[$key] = $value;
    }
    return $data;
}
function select($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatype,...$values);//spread operator
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }  
        else {
            mysqli_stmt_close($stmt);
            die("query cannot be executed -Select");
        }
    }
    else {
        die("query cannot be prepared -Select");
    }
}
function selectt($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Only bind parameters if there are values and a datatype
        if (!empty($values) && !empty($datatype)) {
            mysqli_stmt_bind_param($stmt, $datatype, ...$values); // Spread operator
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }  
        else {
            mysqli_stmt_close($stmt);
            die("query cannot be executed -Select");
        }
    }
    else {
        die("query cannot be prepared -Select");
    }
}
function selectt1($sql, $values = [], $datatype = '')
{
    $con = $GLOBALS['con']; // Use global connection
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Only bind parameters if values and datatype are provided
        if (!empty($values) && !empty($datatype)) {
            mysqli_stmt_bind_param($stmt, $datatype, ...$values);
        }

        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            $error = mysqli_error($con); // Capture the MySQL error
            mysqli_stmt_close($stmt);
            error_log("Query execution failed: $error");
            return false; // Return false for failure
        }
    } else {
        $error = mysqli_error($con); // Capture the MySQL error
        error_log("Query preparation failed: $error");
        return false; // Return false for failure
    }
}


// select items
function selecttt($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Only bind parameters if there are values and a datatype
        if (!empty($values) && !empty($datatype)) {
            mysqli_stmt_bind_param($stmt, $datatype, ...$values); // Spread operator
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);

            // Fetch all results as an associative array
            $rows = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }

            mysqli_stmt_close($stmt);
            return $rows; // Return results as an array
        }  
        else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Select");
        }
    }
    else {
        die("Query cannot be prepared - Select");
    }
}


function update($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatype,...$values);//spread operator
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }  
        else {
            mysqli_stmt_close($stmt);
            die("query cannot be executed -Update");
        }
    }
    else {
        die("query cannot be prepared -Update");
    }
}
function updatee($sql, $values, $datatype) {
    $con = $GLOBALS['con'];
    
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Debugging: Show the prepared SQL and values
        echo "Prepared SQL: " . $sql . "<br>";
        echo "Values: ";
        var_dump($values);
        
        mysqli_stmt_bind_param($stmt, $datatype, ...$values);
        
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Update");
        }
    } else {
        die("Query cannot be prepared - Update");
    }
}


function insert($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatype,...$values);//spread operator
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }  
        else {
            mysqli_stmt_close($stmt);
            die("query cannot be executed -Insert");
        }
    }
    else {
        die("query cannot be prepared -Insert");
    }
}
function insertt($sql, $values, $datatype)
{
    $con = $GLOBALS['con'];
    
    // Prepare the SQL statement
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Bind the parameters to the prepared statement
        if (!mysqli_stmt_bind_param($stmt, $datatype, ...$values)) {
            die("Error binding parameters: " . mysqli_error($con));
        }
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Return the number of affected rows
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            // Error executing the statement
            mysqli_stmt_close($stmt);
            die("Error executing query: " . mysqli_error($con));
        }
    } else {
        // Error preparing the statement
        die("Error preparing query: " . mysqli_error($con));
    }
}

        ?>