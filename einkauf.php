
<?php
  $user = '';
  $pass = '';
  $database = '';
 
  // establish database connection
  $conn = oci_connect($user, $pass, $database);
  if (!$conn) exit;

 //var_dump($_GET);
?>

<html>
<title>Lecker: Backen</title>
<head>
 <link rel="stylesheet" href="index.css" />
</head>
<body>
<img src="b5.png" alt="logo" width="500" height="300">
<br></br>

        <ul> 
		<li><a href="baeckerei.php">Lecker</a></li>
		<li><a href="mitarbeiter.php">Mitarbeiter</a></li>
		<li><a href="konditor.php">Konditor</a></li>
		<li><a href="kuechengehilfe.php">Kuechengehilfe</a></li>
                <li><a href="kunde.php">Kunde</a></li>
                <li><a href="backwaren.php">Backwaren</a></li>
                <li><a href="produkte.php">Produkte</a></li>
           	<li><a href="backen.php">Backen</a></li>
     		<li><a class="active" href="einkauf.php">Einkauf</a></li>
		<li><a href="bestand.php">Bestandteil</a></li>	
		<li><a href="view.php">Views</a></li>	
       </ul>

<br></br>

<div id="wrapper">
<center>
  <div>
    <form id='searchform' action='einkauf.php' method='get'>
      <a href='einkauf.php'>Alles</a> ---
      Suche nach Name: 
      <input id='search' name='search' type='text' size='15' value='<?php if (isset($_GET['search'])) echo $_GET['search']; ?>' />
      <input id='submit' type='submit' class="testbutton" value='Search' />
</form>
  </div>
<?php
  // check if search view of list view
  if (isset($_GET['search'])) {
    $sql = "SELECT * FROM einkauf WHERE kname like '%" . $_GET['search'] . "%'";
  } else {
    $sql = "SELECT * FROM einkauf";
  }

  // execute sql statement
  $stmt = oci_parse($conn, $sql);
  oci_execute($stmt);
?>
 

<div>

  <form id='insertform' action='einkauf.php' method='get'>
<center>
    Neue "Einkauf" einfuegen:
	<table style='border: 5px solid #DDDDDD'>
	  <thead>
	    <tr>
	      <th>Kunden Name</th>
	      <th>ArtikelNr</th>
	    </tr>
	  </thead>
	  <tbody>
	     <tr>
	        <td>
	           <input id='kname' name='kname' type='text' size='50' value='<?php if (isset($_GET['kname'])) echo $_GET['kname']; ?>' />
                </td>
                <td>
                   <input id='artikelnr' name='artikelnr' type='number' size='15' value='<?php if (isset($_GET['artikelnr'])) echo $_GET['artikelnr']; ?>' />
                </td>
	      </tr>
           </tbody>
        </table>
</center>
        <input id='submit' type='submit' class="testbutton" value='Insert' />
  </form>
</div>

<?php
  //Handle insert
   if (isset($_GET['kname'])) 
  {


    //Prepare insert statementd
    $sql = "INSERT INTO einkauf VALUES('" . $_GET['kname'] . "',"  . $_GET['artikelnr'] . ")";
    //Parse and execute statement
    $insert = oci_parse($conn, $sql);
    oci_execute($insert);


    $conn_err=oci_error($conn);
    $insert_err=oci_error($insert);
   
    if(!$conn_err & !$insert_err){
	print("Successfully inserted");
 	print("<br>");
    }
    //Print potential errors and warnings
    else{
       print($conn_err);
       print_r($insert_err);

    }
    oci_free_statement($insert);
  } 

?>

 <table style='border: 5px solid #DDDDDD'>
    <thead>
      <tr>
        <th>Kunden Name</th>
        <th>ArtikelNr</th>
      </tr>
    </thead>
    <tbody>
<?php
  // fetch rows of the executed sql query
  while ($row = oci_fetch_assoc($stmt)) {
    echo "<tr>";
    echo "<td>" . $row['KNAME'] . "</td>";
    echo "<td>" . $row['ARTIKELNR'] . "</td>";
    echo "</tr>";
  }
?>
    </tbody>
  </table>

<div>Insgesamt <?php echo oci_num_rows($stmt); ?> "Einkauf" gefunden!</div>
</center>
<br></br>
<?php  oci_free_statement($stmt); ?>

<?php
oci_close($conn);
?>
</div>
</body>
</html>
