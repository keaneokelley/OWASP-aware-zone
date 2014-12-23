<!DOCTYPE HTML>
<html>
<?php
 $conn = mysqli_connect('localhost', 'username', 'password');
    if(!$conn)
	die("Could not connect to database. " . mysqli_error($conn));
    mysqli_select_db($conn, 'sqldemo');
if(isset($_COOKIE['session'])) {
    $result = mysqli_query($conn, "SELECT * FROM Sessions WHERE sessionID = {$_COOKIE['session']}");
    if(!$result)
	die("Error retrieving session. " . mysqli_error($conn));
    if(mysqli_num_rows($result) == 0)
	echo ("Invalid session. ");
    $data = mysqli_fetch_assoc($result);
    $username = $data['username'];
    $loggedinas = $username;  
} 
if ($loggedinas !== null)
    echo "Logged in as $loggedinas. <a href='index.php'>Home</a> <a href='profile.php'>View Profile</a> <a href='logout.php'>Logout</a>";
else 
    echo "Not logged in. <a href='index.php'>Home</a> <a href='login.php'>Log in</a>";
if($_GET['user'] !== null) 
    $username = $_GET['user'];
else 
    $username = $loggedinas;
$result = mysqli_query($conn, "SELECT * FROM Users WHERE Name = '$username'");
if(!$result)
    die("Error retrieving profile. " . mysqli_error($conn));
if(mysqli_num_rows($result) == 0)
    die ("The user you requested doesn't exist.");
$data = mysqli_fetch_assoc($result);  

echo "<head><title>$username's Profile</title>";
?>
<link rel="stylesheet" type="text/css" href="../css/demo.css" />
</head>
<body>
<?php
echo "<h1>$username's Profile</h1>";
echo "Date joined: {$data['datejoined']}<br/>";
echo "Total posts: {$data['numposts']}<br/><br/>";
if($loggedinas !== null) {
    echo "<h2>Account Management</h2>";
    if(isset($_POST['secret']) && strlen($_POST['secret']) > 0) {
	$result = mysqli_query($conn, "UPDATE Users SET secret = '{$_POST['secret']}' WHERE Name = '$username'");
	if(!$result)
	    echo "Error updating secret. " . mysqli_error($conn);
	else
	    echo "Successfully updated secret.<br/>";
    }

    if (isset($_POST['password']) && strlen($_POST['password']) > 0) {
	$result = mysqli_query($conn, "UPDATE Users SET password = '{$_POST['password']}' WHERE Name = '$username'");
	if(!$result)
	    echo "Error updating password. " . mysqli_error($conn);
	else
	    echo "Successfully updated password.<br/>";
    }    
    echo "<br/>Secret: {$data['secret']}";
?>
 <form action="profile.php" id="account" method="post">
    <br>Update Secret:<input type="text" placeholder="New secret" name="secret"><br>
    Change Password:
    <input type="password" name="password" placeholder="password"><br/>
    <input type="submit" value="Update Profile">
    </form>
    <br>
<?php
} 
?>
</body>
</html>