<?

date_default_timezone_set("Europe/Kiev");

$db_name = "test";
$db_user = "root";
$db_pass = "";


$folder = "./backup";
$start_date = '2012-06-05';
$end_date = '2012-06-05';

$date = new DateTime($start_date);
$tdate = '';

while ($tdate != $end_date) {
	$tdate = $date->format('Y-m-d');
    echo "Restoring date - ".$tdate."<br/>";
    if(!empty($db_pass)){
    	$cmd = "gunzip < {$folder}/db_{$tdate}.sql.gz | mysql -u {$db_user} -p{$db_pass} {$db_name}";
    }else{
    	$cmd = "gunzip < {$folder}/db_{$tdate}.sql.gz | mysql -u {$db_user} {$db_name}";
    }
    $res = `{$cmd}`;
    $date->add(new DateInterval('P1D'));
}  



?>
