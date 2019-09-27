<?php
    header("Access-Control-Allow-Origin: *");
	define('AES_128_CBC', 'aes-128-cbc');
	$memcache = new Memcached;

	$hash = $_REQUEST['hash'];
	$encryption_key = "D47D8CA98F20B704";
	$iv = "C3A1LSKDFBLJKDS1";
	$decrypted = openssl_decrypt($hash, AES_128_CBC, $encryption_key, 0, $iv);
	$key = substr($decrypted,0,strlen($decrypted)-3);
	$inning  = $_REQUEST['inning'];
	$data = $memcache->get('history_'.$key."_".$inning);
	if($data!==FALSE){
		echo $data;	
	}
	else{
		$data = file_get_contents("https://cricket-exchange.firebaseio.com/history/".$key."/0".$inning.".json"); 
		$memcache->set('history_'.$key."_".$inning,$data,30);
		echo $data;
	}
?>
