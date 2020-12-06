<?php

$host_name = 'db';
$db_name = 'mydb';
$dsn = 'mysql:host='.$host_name.';dbname='.$db_name.';charset=utf8';
$db_user = 'root';
$db_pass = 'secret';
$table_name = 'test';
$keyColumn_name = 'name';		//値があるか検索したいカラム名を代入
$valueColumn_name = 'value';	//値を変更したいカラム名を代入

try {
	$pdo = new PDO($dsn, $db_user, $db_pass);
	// echo 'データベース接続成功' . PHP_EOL;

	if (isset($_GET['key'])) {
		$getKey = $_GET['key'];

		if (isset($_GET['value'])) {
			$getValue = $_GET['value'];

			//$_GET['key'] の値が登録されている場合、対象カラムを $_GET['value'] に変更する
			$updateSql = 'UPDATE ' .$table_name;
			$updateSql .= ' SET ' .$valueColumn_name. ' = CASE WHEN ' .$keyColumn_name. ' = ? THEN ? END';
			$updateSql .= ' WHERE ' .$keyColumn_name. ' = "' .$getKey. '"';

			$updateStmt = $pdo->prepare($updateSql);
			$updateStmt->execute(array($getKey, $getValue));
			
			//$_GET['key'] の値が登録されていない場合、エラーを返す
			$searchSql = 'SELECT ' .$keyColumn_name;
			$searchSql .= ' FROM ' . $table_name;
			$searchSql .= ' WHERE ' .$keyColumn_name. ' = "' .$getKey.'"';

			$searchStmt = $pdo->query($searchSql);

			if (count($searchStmt->fetchAll()) == 0) {
				echo 'ERROR! key[' .$getKey. ']は登録されていません';
			}

		} else {
			echo 'ERROR! valueがセットされていません';
		}
	} else {
		echo 'ERROR! keyがセットされていません';
	}

} catch (PDOException $e) {
    exit('ERROR! データベース接続失敗' . $e->getMessage());	
}
