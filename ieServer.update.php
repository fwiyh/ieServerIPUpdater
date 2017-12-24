<?php

$account = "username";
$domain = "domain";
$password = "password";
$interval = "+60 day";
$defaultTimezone = "Asia/Tokyo";

$domainName = $account .".". $domain;
$addrPath = "/var/tmp/ies_$domainName/current.addr";
$logPath = "/var/log/ies_$domainName.log";
// $addrPath = "F:\\My Documents\\Projects\\eclipse\\SomeUtils\\update.addr";
// $logPath = "F:\\My Documents\\Projects\\eclipse\\SomeUtils\\ies_$domainName.log";

$remoteIPCheckUri = "http://ieserver.net/ipcheck.shtml";
$ddnsUpdateUri = "http://ieserver.net/cgi-bin/dip.cgi?username=$account&domain=$domain&password=$password&updatehost=1";

/* tmp format rule
 * datetime,currentip[EOF]
 */
$currentDate = "1970-01-01 00:00:00";
$currentIP = "0.0.0.0";

// set default time zone
date_default_timezone_set($defaultTimezone);

// get currentIp from tmpfile.
if (file_exists($addrPath)){
	$tmp = array();
	$tmp = explode(",", file_get_contents($addrPath));
	// set configdata.
	$currentDate = $tmp[0];
	$currentIP = $tmp[1];
}else {
	touch($addrPath);
	file_put_contents($addrPath, "1970-01-01 00:00:00,0.0.0.0");
}

// get nown ip address.
$nowIp = file_get_contents($remoteIPCheckUri);
if ($nowIp === false){
	return 1;
}

// diff datetime
$nowTime = date("Y-m-d H:i:s");
if (strtotime($nowTime) >= strtotime($currentDate ." ".$interval)
	|| ($currentIP == "0.0.0.0" || $currentIP != $nowIp)){
	// update ip address.
	$upRet = file_get_contents($ddnsUpdateUri);
	if ($upRet === false){
		// add log
		$fo = fopen($logPath, "a");
		fwrite($fo, "$nowTime : Cannot Update IP Address [from $currentId][to $nowIp].\n");
		fclose($fo);
		echo "$nowTime : Cannot Update IP Address [from $currentId][to $nowIp].\n";
		return 1;
	}else {
		// update new ip address.
		file_put_contents($addrPath, $nowTime .",". $nowIp);
		// add log
		$fo = fopen($logPath, "a");
		fwrite($fo, "$nowTime : Update IP Address from $currentIP to $nowIp.\n");
		fclose($fo);
		echo "$nowTime : Update IP Address from $currentIP to $nowIp.\n";
		return 0;
	}
}
