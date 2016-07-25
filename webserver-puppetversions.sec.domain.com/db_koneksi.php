<?php
$connect = mysql_connect("DATABASE_SERVER", "puppetversions", "PASSWORD_HERE"); //ini setingan default, namun jika kamu memiliki setingan sendiri mohon disesuaikan aja
if(!$connect){
	die ("Error: ".mysql_error());
}
mysql_select_db("puppetversions", $connect); //konekin ke database kamu namanya apa, kalo disini namanya 'datphp'
$db = mysql_query("SELECT * FROM main"); //isi nama tabel kamu, kalo disini namanya 'datables'
?>
