<?php
	require_once("header.php");
	session_start();
	if (isset($_SESSION["form"]) )	
	{			
		$form=$_SESSION["form"];
		foreach ($form as $key => $value) {
			$name=$value[0];
			$lastname=$value[1];
			$mail=$value[2];
			$phone=$value[3];
			$address=$value[4];
			$number=$value[5];
			$postCode=$value[6];
			$info=$value[7];
		}
		
	}
	else{
		$name="";
		$lastname="";
		$mail="";
		$phone="";
		$address="";
		$number="";
		$postCode="";
		$info="";
	}
?>
<div id="wrap">
	<div id="content">
		<?php 
			echo '<div id="welcome">';	
			if (!isset($_GET["Message"])){
				echo '<p class="welcome">Καλώς ήρθατε στο Δήμο Πειραία. Συμπληρώστε τα στοιχεία σας για να μας ενημερώσετε για τυχόν προβλήματα που αντιμετωπίζει ο δήμος μας ή απορίες σας.</p>';
			}
			else{
				echo '<p class="welcome">Το μήνυμά σας καταχωρήθηκε επιτυχώς! Ευχαριστούμε που επικοινωνήσατε μαζί μας!</p>';
				session_destroy();
			}
			echo ' </div>';
			if (isset($_GET["error"])){
				echo '<div id="error">';
					switch($_GET["error"])
					{
						case "name":
							echo '<p class="error">Το όνομα σας είναι λανθασμένο. Παρακαλώ πληκτρολογήστε το όνομα σας χρησιμοποιώντας τουλάχιστον 3 ελληνικούς χαρακτήρες, χωρίς αριθμούς.</p>';
						break;	
						case "lastname":
							echo '<p class="error">Το επώνυμό σας είναι λανθασμένο. Παρακαλώ πληκτρολογήστε το επώνυμό σας χρησιμοποιώντας τουλάχιστον 3 ελληνικούς χαρακτήρες, χωρίς αριθμούς.</p>';
						break;	
						case "mail":
							echo '<p class="error">Το email σας είναι λανθασμένο. Παρακαλώ πληκτρολογήστε ένα έγκυρό email για να συνεχίσετε.<br>
									Υπενθύμιση: Το email πρέπει να είναι της μορφής: user@domain.com ή user@domain.gr"</p>';
						break;	
						case "phone":
							echo '<p class="error">Ο αριθμός του κινητού σας είναι λανθασμένος. Παρακαλώ πληκτρολογήστε έναν έγκυρό αριθμό τηλεφώνου για να συνεχίσετε.<br>
									Υπενθύμιση: Ο αριθμός πρέπει να ξεκινάει από 69 και να περιλαμβάνει 10 αριθμούς.</p>';
						break;
						case "address":
							echo '<p class="error">Η οδός είναι λανθασμένη. Παρακαλώ πληκτρολογήστε μία έγκυρη οδό για να συνεχίσετε.<br>
									Παρακαλώ πληκτρολογήστε την οδό χρησιμοποιώντας ελληνικούς χαρακτήρες, χωρίς αριθμούς.</p>';
						break;	
						case "number":
							echo '<p class="error">Ο αριθμός της διεύθυνσής σας είναι λανθασμένος. Παρακαλώ πληκτρολογήστε έναν έγκυρο αριθμό για να συνεχίσετε.<br>
									Παρακαλώ πληκτρολογήστε τον αριθμό χρησιμοποιώντας μόνο αριθμούς και μέχρι 3 ψήφία.</p>';
						break;	
						case "postCode":
							echo '<p class="error">Ο TK της διεύθυνσής σας είναι λανθασμένος. Παρακαλώ πληκτρολογήστε έναν έγκυρο TK για να συνεχίσετε.<br>
									Παρακαλώ πληκτρολογήστε τον πενταψήφιο TK χρησιμοποιώντας μόνο αριθμούς, χωρίς κενά. Ο ΤΚ για το δήμο μας ξεκινάει με τον αριθμό 18xxx</p>';
						break;	
						case "info":
							echo '<p class="error">Το μήνυμα σας δεν πρέπει να είναι κενό και θα πρέπει να είναι μεγαλύτερο από 10 χαρακτήρες.</p>';
						break;	
						case "captcha":
							echo '<p class="error">Παρακαλώ ελέγξτε τη φόρμα του Captcha και ξαναπροσπαθήστε.</p>';
						break;	
						case "db":
							echo '<p class="error">Υπήρξε πρόβλημα στην καταχώρηση του μηνύματός σας. Παρακαλώ επικοινωνήστε με τον διαχειριστή της σελίδας.</p>';
						break;
						case "blank":
							echo '<p class="error">Παρακαλώ συμπληρώστε όλα τα πεδία για να συνεχίσετε.</p>';
						break;
						case "method":
							echo '<p class="error">Λάθος στην μέθοδο της αίτησης. Προσπαθήσατε να αποστείλετε τα στοιχεία χρησιμοποιώντας λάθος μέθοδο.</p>';
						break;
						case "strings":
							echo '<p class="error">Τα πεδία Όνομα, Επώνυμο, Τηλέφωνο, Οδός καθώς και το πεδίο κείμενου δεν πρέπει να είναι κενά.</p>';
						break;
						case "int":
							echo '<p class="error">Οι τιμές στα πεδία Αριθμός και ΤΚ πρέπει να είναι ακέραιοι μεγαλύτεροι του 0.</p>';
						break;	
						case "wrong":
							echo '<p class="error">Οι τιμές στα πεδία δεν είναι αποδεκτές.</p>';
						break;	
					}
				echo '</div>';
			}?>
			<div id="save">	
				<form method="post" action="save.php" class="saveform">
					<p class="formTitle">Τα στοιχεία σας:</p>	
					<div id="first">
						<div id="name">Όνομα: <input id="username" class="<?php if($_GET['error'] == 'name' ){ echo 'ErrorField'; }else{echo 'name';} ?>" type="text" name="name" value="<?php echo $name;?>"></div>
						<div id="lastname">Επώνυμο: <input id="userLname" class="<?php if($_GET['error'] == 'lastname' ){ echo 'ErrorField'; }else{echo 'lastname';} ?>" type="text" name="lastname" value="<?php echo $lastname;?>"></div>
					</div>
					<div id="second">
						<div id="mail">Email: <input id="usermail" class="<?php if($_GET['error'] == 'mail' ){ echo 'ErrorField'; }else{echo 'mail';} ?>" type="email" name="email" value="<?php echo $mail;?>"></div>
						<div id="phone">Κινητό: <input id="userphone" class="<?php if($_GET['error'] == 'phone' ){ echo 'ErrorField'; }else{echo 'phone';} ?>" type="text" name="phone" value="<?php echo $phone;?>"></div><br>
					</div>
					<div id="where">
						<p class="formTitle">Πληροφορίες για το περιστατικό:</p>
						<div id="details">
							<div id="third">
								<div id="address">Οδός: <input id="useraddress" class="<?php if($_GET['error'] == 'address' ){ echo 'ErrorField'; }else{echo 'address';} ?>" type="text" name="address" value="<?php echo $address;?>"></div>
								<div id="number">Αριθμός: <input id="userAnumber" class="<?php if($_GET['error'] == 'number' ){ echo 'ErrorField'; }else{echo 'number';} ?>" type="text" name="number" size="3" value="<?php echo $number;?>"></div>
								<div id="postCode">TK: <input id="userPostCode" class="<?php if($_GET['error'] == 'postCode' ){ echo 'ErrorField'; }else{echo 'postCode';} ?>" type="text" name="postCode" size="5" value="<?php echo $postCode;?>"></div>
							</div>
						</div>
						<div id="details-text">
							<textarea cols="67" rows="10" name="info" class="<?php if($_GET['error'] == 'info' ){ echo 'ErrorField'; }else{echo 'info';} ?>"><?php echo $info;?></textarea>
						</div>
						<div id="captcha">
							<div class="g-recaptcha" data-sitekey="6LfldhETAAAAAHxdsXItlDGCbIOWX4YOUxSjwToT"></div>
						</div>
						<input class="button" type="submit" value="Αποθήκευση">
					</div>
				</form>
			</div>';
<?php
	require_once("footer.php");
?>