<?php

// Starting the session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


if (isset($_POST['submit'])) {
	if($_POST['submit'] == 'Log out')
	{
		// Destroy the session
		$_SESSION = array();
		session_destroy();
		
		header("Location: index.php");
		exit;
	}
	else if($_POST['submit'] == 'Log in')
	{
		// Checking whether the Login form has been submitted

		$err = array();
		// Will hold our errors

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password']
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		$responseObj = json_decode($response,true);

		if($responseObj['success'])
		{
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Wrong username and/or password!';	
		}
		
		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['login-err'] = implode('<br />',$err);
		}

		header("Location: index.php");
		exit;

	}
	else if($_POST['submit'] == 'Sign Up')
	{
		
		$err = array();
		
		// REGISTER USING REST API

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'join_mailing_list' => $_POST['join_mailing_list'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'telephone' => $_POST['telephone'],
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'zip_code' => $_POST['zip_code'],
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		$responseObj = json_decode($response,true);

		if($responseObj['success'])
		{
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Could not create account';	
		}

		if($err)
		{
			$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		}	
		
		header("Location: index.php");
		exit;
	}
}

if (isset($_SESSION['id']))
{
	// The user is logged in.

	// Redirect to the create order page
	header('Location: createOrder.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Custom Cupcakes</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1>Custom Cupcakes</h1>

		<div id="loginContainer" >

			<?php

			if(isset($_SESSION['msg']['login-err']))
			{
				echo '<div class="error">'.$_SESSION['msg']['login-err'].'</div>';
				unset($_SESSION['msg']['login-err']);
			}
			?> 

			<form method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email Address" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" required autocomplete="on" />
				<input type="submit" name="submit" value="Log in" />
			</form>
		</div>

	</header>


		<div id="leftHalf">
			<img id="logoImg" src="" alt="Custom Cupcakes Logo"/>
			<h3>Great Flavors!</h3>
			<h3>Awesome Cupcakes!</h3>
			<h3>Fast Delivery!</h3>
		</div>
	<div id="registerContainer">
	<h2>Create a Custom Cupcake Account</h2>
	
		<?php

		if(isset($_SESSION['msg']['reg-err']))
		{
			echo '<div class="error">'.$_SESSION['msg']['reg-err'].'</div>';
			unset($_SESSION['msg']['reg-err']);
		}
		?> 

		<div id="rightHalf">
		<form method="POST" action="index.php">

			<label for="join_mailing_list">Join Our Mailing List:</label>
			<input type="radio" name="join_mailing_list" value="true" /> Yes
			<input type="radio" name="join_mailing_list" value="false" /> No
<fieldset><legend>Create Your CustomCupcakes Account</legend>
			<input type="text" name="first_name" placeholder="First Name" required autocomplete="on" />
			<input type="text" name="last_name" placeholder="Last Name" required autocomplete="on" />

			<input type="email" name="email" placeholder="Email Address" required autocomplete="on" />
			<input type="password" name="password" placeholder="Password" required autocomplete="on" />

			<input type="telephone" name="telephone" placeholder="Telephone Number" required autocomplete="on" />

			<input type="text" name="address" placeholder="Address" required autocomplete="on" />

			<input type="text" name="city" placeholder="City" required autocomplete="on" />

			<label for="state">State:</label>
<select id="state" name="state" placeholder="Select State" required>
<option value="AL">Alabama</option>
<option value="AK">Alaska</option>
<option value="AZ">Arizona</option>
<option value="AR">Arkansas</option>
<option value="CA">California</option>
<option value="CO">Colorado</option>
<option value="CT">Connecticut</option>
<option value="DE">Delaware</option>
<option value="DC">District of Columbia</option>
<option value="FL">Florida</option>
<option value="GA">Georgia</option>
<option value="HI">Hawaii</option>
<option value="ID">Idaho</option>
<option value="IL">Illinois</option>
<option value="IN">Indiana</option>
<option value="IA">Iowa</option>
<option value="KS">Kansas</option>
<option value="KY">Kentucky</option>
<option value="LA">Louisiana</option>
<option value="ME">Maine</option>
<option value="MD">Maryland</option>
<option value="MA">Massachusetts</option>
<option value="MI">Michigan</option>
<option value="MN">Minnesota</option>
<option value="MS">Mississippi</option>
<option value="MO">Missouri</option>
<option value="MT">Montana</option>
<option value="NE">Nebraska</option>
<option value="NV">Nevada</option>
<option value="NH">New Hampshire</option>
<option value="NJ">New Jersey</option>
<option value="NM">New Mexico</option>
<option value="NY">New York</option>
<option value="NC">North Carolina</option>
<option value="ND">North Dakota</option>
<option value="OH">Ohio</option>
<option value="OK">Oklahoma</option>
<option value="OR">Oregon</option>
<option value="PA">Pennsylvania</option>
<option value="RI">Rhode Island</option>
<option value="SC">South Carolina</option>
<option value="SD">South Dakota</option>
<option value="TN">Tennessee</option>
<option value="TX">Texas</option>
<option value="UT">Utah</option>
<option value="VT">Vermont</option>
<option value="VA">Virginia</option>
<option value="WA">Washington</option>
<option value="WV">West Virginia</option>
<option value="WI">Wisconsin</option>
<option value="WY">Wyoming</option>
</select>

			<input type="number" name="zip_code" placeholder="Zip Code" required autocomplete="on" />
			<input type="submit" name="submit" value="Sign Up" />
		</fieldset>
		</form>
	</div>

	<footer>

	</footer>

</body>
</html>
