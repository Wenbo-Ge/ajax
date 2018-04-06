<?php

$a=$_POST['username'];
$b=$_POST['password'];

$servername = 'localhost';  // mysql服务器主机地址
$username = 'root';            // mysql用户名
$password = 'root';          // mysql用户名密码

$sel_p_id=$_POST['sel_p_id'];
$sel_p_name=$_POST['sel_p_name'];
//$_REQUEST

$upd_p_id=$_POST['upd_p_id'];
$upd_p_q_orign=$_POST['upd_p_q_orign'];
$upd_p_q_cur=$_POST['upd_p_q_cur'];

$del_p_id=$_POST['del_p_id'];

if($a&&$b){
	$c="xin";
	$d="123";
	$response = array();

	if($a==$c&&$b==$d){
		$response=array(
			"res"=>"success"
		);
	}else{
		$response=array(
			"res"=>"failed"
		);
	}
	echo json_encode($response);
}else{

	echo "<table style='border: solid 1px black;'>";
	echo "<tr><th>Id</th><th>Name</th><th>Description</th><th>Quantity</th></tr>";

	class TableRows extends RecursiveIteratorIterator { 
	    function __construct($it) { 
	        parent::__construct($it, self::LEAVES_ONLY); 
	    }

	    function current() {
	        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
	    }

	    function beginChildren() { 
	        echo "<tr>"; 
	    } 

	    function endChildren() { 
	        echo "</tr>" . "\n";
	    } 
	} 

	try{
		$conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if($sel_p_id || $sel_p_name){
			$conn->exec("SET names utf8");
		    $stmt = $conn->prepare("SELECT * FROM product where p_id = $sel_p_id"); 
		    $stmt->execute();
		    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
		    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
		        echo $v;
		    }
		}else if($upd_p_id&&$upd_p_q_orign&&$upd_p_q_cur){
			$sql = "UPDATE product SET p_quantity=$upd_p_q_cur WHERE p_id = $upd_p_id";
			//echo $sql;
		    $stmt = $conn->prepare($sql);
		    $stmt->execute();
		    echo $stmt->rowCount() . " records UPDATED successfully";
		}else if($del_p_id){
    		//delete SQL statement
		    $sql = "DELETE FROM product WHERE p_id = $del_p_id";
		    $conn->exec($sql);
		    echo "Record deleted successfully";
		}else{
			
		}

	}catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}

	$conn = null;
}

?>