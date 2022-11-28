<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary = $username = $password ="";
$name_err = $address_err = $salary_err = $username_err =$password_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } else{
        $salary = $input_salary;
    }

    // Validate username
if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else{
    // Prepare a select statement
    $sql = "SELECT id FROM employees WHERE username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Validate confirm password
if(empty(trim($_POST["password"]))){
    $password_err = "Please confirm password.";     
} else{
    $password = trim($_POST["password"]);
    if(empty($password_err) && ($password != $password)){
        $password_err = "Password did not match.";
    }
}
 
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)&& empty($username_err)&& empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, salary, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: orderonline.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta, title -->
		<meta charset="utf-8">		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Bootstrap, a sleek, intuitive, and powerful mobile first front-end framework for faster and easier web development.">
		<meta name="keywords" content="HTML, CSS, JS, JavaScript, framework, bootstrap, front-end, frontend, web development">
		<meta name="author" content="WowThemesNet">	
		<title>Zolix - MultiPurpose Theme</title>
		
		<!-- Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,400italic,500,600,700,700,800,900italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css'/>
		<link href='http://fonts.googleapis.com/css?family=ABeeZee:100,200,300,400,400italic,500,600,700,700,800,900italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css'/>
		
		<!-- CSS -->
		<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/shortcodes.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/animate.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='style.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/skins/orange.css' type='text/css' media='all'/>
		
		<!-- Jquery -->
		<script type='text/javascript' src='js/jquery.js'></script>
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="js/html5shiv.min.js"></script>
		  <script src="js/respond.min.js"></script>
		<![endif]-->

		<!-- Favicons -->
		<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png">
		<link rel="icon" href="img/favicon.ico">
	</head>
<body>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>


<link href="style2.css" rel="stylesheet" type="text/css">

<link rel="stylesheet"
    href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<!-- BEGIN MENU
================================================== -->	
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index1.html">
			<img src="img/mbf.png" alt="" class="lg1">
			</a>
		</div>
		<div class="navbar-collapse collapse pull-right" id="mainMenu">
			<ul id="menu-main-menu" class="nav navbar-nav">
				<li><a href="index1.html">Home</a></li>
				<li><a href="aboutus.html">About us</a></li>
				<li><a href="careers.html">Careers</a></li>
				<li><a href="orderonline.php">Order online</a></li>
				<li><a href="contactus.html">Contact us</a></li>
				<li><a href="register2.php">register2</a></li>
				
				
				</ul>
				</li>
			</ul>
		</div>
	</div>
</div>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
		<!-- CSS -->
		<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/shortcodes.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/animate.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='style.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/skins/orange.css' type='text/css' media='all'/>

</head>
<body>
<!-- daohang -->
<!-- daohang -->




<!-- jieshu   -->

<!--SECTION home (intro)
	================================================== -->	
	<section id="home" class="parallax parallax-image dark-bg" style="background-color: #000000; background-image:url(./img/bg1.jpg);">
		<div class="wrapsection" style="background-image:url(img/pattern.png); background-repeat:repeat;">
			<div class="overlay" style="background-color: #000000; opacity: 0.3;">
			</div>
			<div class="container">
			
				<div class="parallax-content">
					<div class="row">
						<div class="wowanimslider fullwidth flexslider clearfix">
						
							<ul class="slides">
							
								<li>
								<div class="caption text-center" style="padding-top:120px;padding-bottom:120px; color:#ffffff;">
									<h1 class="home-slide-content wow zoomIn" data-wow-delay="0s" data-wow-duration="1s">Welcome to <span class="stresscolor">Luca's Loaves</span></h1>
									<h6 class="wow zoomIn" data-wow-delay="0.9s" data-wow-duration="2s"></h6>
								</div>
								</li>
                            </h3>
                            <p></p>
								
							</ul>
							
						</div><!-- /.wowanimslider -->
					</div><!-- /.row -->
				</div><!-- /.parallax-content -->
				
			</div><!-- /.container -->
		</div><!-- /.wrapsection -->
	</section>



 <!-- jieshu   -->

 <div class="wrapper2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="title">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="fname">Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Address</label>
                            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Password</label>
                            <input type="text" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="submit" class="open-button" value="Submit">
                    </form>
                    </div>   
                </div>
            </div>        
        </div>
    </div>


   <!-- BEGIN FOOTER
================================================== -->
<section class="nowidgetbottom">
    <p id="back-top">
        <a href="#top"><span><i class="fa fa-chevron-up"></i></span></a>
    </p>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <img src="img/logowhite.png" alt="">
                <ul class="social-icons" style="margin-top:7px;margin-bottom:5px;">
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                </ul>
                 <a target="_blank" href="http://www.mobanwang.com" title="Michael hebin">Michael hebin</a>
            </div>
        </div>
    </div>
    </section>
    <!-- END FOOTER
    ================================================== -->

	


</body>
</html>
