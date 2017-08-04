<?

include("db.php");

//SELECT last_value FROM test_id_seq;
//SELECT c.relname FROM pg_class c WHERE c.relkind = 'S';

$rows=$db->get_results("select * from pg_class c WHERE c.relkind = 'S' order by relname");

echo "<table border=\"1\">";

foreach($rows as $row){

	$seqname=$row->relname;
	
	$lastval = $db->get_var("select last_value from $seqname");
	
	echo "<tr><td>$seqname</td><td>$lastval</td></tr>";


}

echo "</table>";



?>