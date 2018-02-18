<?php

    session_start();

    if ($_GET["logout"]==1 AND $_SESSION['id']){ session_destroy();
            
        $message="You have been logged out. Have a nice day!";
        
        echo $_SESSION['id'];
                                      
            }

    include("connection.php");

    if ($_POST['submit']=="Sign Up") {
        
        if (!$_POST['email']) $error.="<br />Please enter your email";
            else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $error.="<br />Please enter valid email";
                
        if (!$_POST['password']) $error.="<br />Please enter your password";
            else {
                
                if (strlen($_POST['password'])<8) $error.="<br />Enter a password with at least 8 characters";
                if (!preg_match('`[A-Z]`', $_POST['password'])) $error.="<br />Please include at least 1 capital letter in your password";
                
            }
        
        if ($error) $error = "There were errors in your signup details:".$error;
        
        else {
            
            
            $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'"; //The mysqli_real_escape_string() function escapes special characters in a string for use in an SQL statement.



            $result = mysqli_query($link, $query); // The mysqli_query() function performs a query against the database.
            
            $results = mysqli_num_rows($result);    //The mysqli_num_rows() function returns the number of rows in a result set.
            
            if ($results) $error = "That email address id already registered. Do you want to log in?";
            else {
                
              
               $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."','".md5(md5($_POST['email']).$_POST['password'])."')";
                
                mysqli_query($link, $query);
                
               echo "You have been signed up!";
                
                $_SESSION['id']=mysqli_insert_id($link); 
                // The mysqli_insert_id() function returns the id (generated with AUTO_INCREMENT) used in the last query.
                
                //print_r($_SESSION);
                
                 header("Location:mainpage.php");
                
                
                
            }
            
        }
        
    }

    if ($_POST['submit']=="Log In") {
        
        $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link, $_POST['loginemail'])."' AND password='" .md5(md5($_POST['loginemail']) .$_POST['loginpassword']). "' LIMIT 1";
        
          
        $result = mysqli_query($link, $query);
        
        $row = mysqli_fetch_array($result);
        
        //The mysqli_fetch_array() function fetches a result row as an associative array, a numeric array, or both.
        
        if ($row) {
            
            $_SESSION['id']=$row['id'];
            // getting an ID of the user just found in the database
            
            //print_r($_SESSION);
            //Redirect to logged in page
            header("Location:mainpage.php");
            
        } else {
            
             $error =  "We could not find user with that email and pasword. Please again.";
            
        }
        
    }


?>


