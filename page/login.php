<?php
	
	//AB ühendus
	require_once("../../config.php");
	$database = "if15_toomloo_3";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	//echo $_POST["email"];
	$email_error = "";
	$password_error = "";
	$cname_error = "";
	$cemail_error = "";
	$cpassword_error = "";
	
	// muutujad ab väärtuste jaoks
	$cname = ""; 
	$cemail = ""; 
	$cpassword = "";
	$email = ""; 
	$password = "";
	
	
	//kontrollime, et keegi vajutas input nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		//echo "keegi vajutas nuppu";
		
		if (isset($_POST["login"])) {
			
			//kontrollin, et e-post ei ole tühi
			
			if ( empty($_POST["email"])){
				$email_error = "See väli on kohustuslik";
					
			} else {
				
				$email = test_input($_POST["email"]);
			}
			
			//kontrollin, et password ei ole tühi
			
			if ( empty($_POST["password"])){
				$password_error = "See väli on kohustuslik";
				
			} else {
				
				//kui oleme siia jõudnud, siis parool ei ole tühi
				
				$password = test_input($_POST["password"]);
			}
			
			//vaatame errorid üle
			if($email_error == "" && $password_error ==""){
				echo "Võib sisse logida! Kasutajanimi on ".$email." ja parool on ".$password;
				$hash = hash("sha512", $password);
				
				$stmt = $mysqli->prepare("SELECT email, password FROM 2login WHERE email=? AND password=?");
				$stmt->bind_param("ss",$email, $hash);
				
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				
				if($stmt->fetch()){
					echo "Email ja parool õiged, kasutaja id=".$id_from_db;
					
				}else{
					
					echo "Wrong credentials!";
				}
				$stmt->close();
				
			}
			
		} 
		if(isset($_POST["create"])){
			
			if ( empty($_POST["cname"])){
				$cname_error = "See väli on kohustuslik";
			} else {
				// test_input eemaldab kõik pahatahtlikud osad
				$cname = test_input($_POST["cname"]);
			}
			
			if ( empty($_POST["cemail"])){
				$cemail_error = "See väli on kohustuslik";
					
			} else {
				$cemail = test_input($_POST["cemail"]);
			}
			
			if ( empty($_POST["cpassword"])){
				$cpassword_error = "See väli on kohustuslik";
				
			} else {
				
				//kui oleme siia jõudnud, siis parool ei ole tühi
				//kontrollin, et oleks vähemalt 8 sümbolit pikk
				if(strlen($_POST["cpassword"]) < 8) {
					
					$cpassword_error = "Peab olema vähemalt 8 tähemärki pikk";
					
				} else {
					$cpassword = test_input($_POST["cpassword"]);
				}
				echo "Tere";
				
			}
			if ($cemail_error == "" && $cpassword_error == ""){
				
				$hash = hash("sha512", $cpassword);
				echo "Tere2";
				$stmt = $mysqli->prepare("INSERT INTO 2login (name, email, password) VALUES (?,?,?)");
				$stmt->bind_param("sss", $cname, $cemail, $hash);
				$stmt->execute();
				$stmt->close();
				
			}
			
		}
	}
	
	function test_input($data) {
	  $data = trim($data); // võtab ära tühikud,enterid,tabid
	  $data = stripslashes($data); // võtab ära tagurpidi kaldkriipsud
	  $data = htmlspecialchars($data); // teeb html'i tekstiks < läheb &lt;
	  return $data;
	}
	
	$mysqli->close();
?>
<?php
	$page_title = "Sisselogimise leht";
	$page_file_name = "login.php";

?>
<?php require_once("../header.php"); ?>
	<h2>Log in</h2>
	
		<form action="login.php" method="post" >
			<input name="email" type="email" placeholder="E-post">  <?php echo $email_error; ?><br><br>
			<input name="password" type="password" placeholder="parool">  <?php echo $password_error; ?><br><br>
			<input type="submit" name="login" value="Login">  <br><br>
		</form>
	
	<h2>Create user</h2>
	
		<form action="login.php" method="post"> 
			<input name="cname" type="text" placeholder="Eesnimi Perekonnanimi" value="<?php echo $cname; ?>"> <?php echo $cname_error; ?> <br><br>
			<input name="cemail" type="email" placeholder="E-post"> <?php echo $cemail_error; ?> <br><br>
			<input name="cpassword" type="password" placeholder="parool"> <?php echo $cpassword_error; ?> <br><br> 
			<input type="submit" name="create" value="Registreeru"> <br><br>
		</form>
	
	<h2>MVP idee</h2>
	<p>Internetilehekülg, kus näidatakse League of Legends'i turniire ja kus saab kihla vedada, milline tiim, millise
	turniiri võidab. </p>
<?php require_once("../footer.php"); ?>