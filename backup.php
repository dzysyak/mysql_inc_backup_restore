<?

$db_name = "test";
$db_user = "root";
$db_pass = "";

date_default_timezone_set("Europe/Kiev");
$mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$folder = "./backup";
$tables = array("symbols"=>"", "test_rand"=>"DateField");
$date = date("Y-m-d", time());

$fp = gzopen("{$folder}/db_{$date}.sql.gz",'w9');
foreach($tables as $table=>$filter){
	if(!empty($filter))
		$sql = "SELECT * FROM `{$table}` WHERE `{$filter}`='{$date}'";
	else
		$sql = "SELECT * FROM `{$table}`";
	if($result = $mysqli->query($sql)){
		$io_ar = array();
		
		$finfo = $result->fetch_fields();
		$fields = array();
		foreach ($finfo as $val) {
			$fields[] = $val->name;
		}

		$io = "INSERT INTO `{$table}` (`".implode("`, `", $fields)."`) VALUES\n";
		while ($row = $result->fetch_assoc()) {
			$tmp = array();
			foreach($fields as $f){
				$tmp[] = "'".$mysqli->real_escape_string($row[$f])."'";
			}
		    $io_ar[] = "(".implode(", ", $tmp).")";
		}
		
		$result->free();
		gzwrite($fp, $io.implode(",\n", $io_ar).";\n\n");
	}
}

gzclose($fp);
$mysqli->close();

?>
