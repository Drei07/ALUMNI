

<?php
	try {

		// localhost
		// $pdoConnect = new PDO("mysql:host=localhost;dbname=alumni", "root", "");

		// Live
		$pdoConnect = new PDO("mysql:host=localhost;dbname=u839560647_alumni", "u839560647_alumni", "Andreishania12");
		$pdoConnect->setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

	}
	catch (PDOException $exc){
		echo $exc -> getMessage();
	}
    catch (PDOException $exc){
        echo $exc -> getMessage();
    exit();
    }
?>