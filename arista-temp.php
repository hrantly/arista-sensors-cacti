<?php

/* do NOT run this script through a web browser */
if (!isset($_SERVER["argv"][0]) || isset($_SERVER['REQUEST_METHOD'])  || isset($_SERVER['REMOTE_ADDR'])) {
   die("<br><strong>This script is only meant to run at the command line.</strong>");
}

if (defined('STDIN')) {
  $host = $argv[1];
  $community = $argv[2];
  $job = $argv[3];
  //sessors table oid

  $oid = 'ENTITY-SENSOR-MIB::entPhySensorTable';
  $desc = 'ENTITY-MIB::entPhysicalDescr';

  $ar = new SNMP(SNMP::VERSION_2C, $host, $community);
  $res1 = $ar->walk($oid, true);
}


function define_job ($job) {
  $res = array();
    if (strpos($job, 'temp') === 0 ) {
      if (strpos($job, ':all') !== false) {
        $res['front'] = '100006001';
        $res['fan1'] = '100006002';
        $res['fan2'] = '100006003';
        $res['chip'] = '100006004';
        $res['vrm'] = '100006005';
        $res['psu1'] = '100711101';
        $res['psu2'] = '100721101';
      }
      elseif (strpos($job, 'power') === 0) {
      	if (strpos($job, ':all') !== false) {
	        $res['bat'] = '100006100'; // battery V AC
	        $res['psu1inA'] = '100711102'; // amperes 220AC
	        $res['psu2inA'] = '100721102'; // amperes 220AC
	        $res['psu1outA'] = '100711103'; // amperes 220AC
	        $res['psu2outA'] = '100721103'; // amperes 220AC
	    }
      }
      else {
        $exp_job = explode(':', $job);
        unset($exp_job[0]);
        foreach ($exp_job as $v) {
        	$r1 = explode('-', $v);
        	$res[$r1[0]] = $r1[1];
        }
      }     
    }
    return $res;
}


function execute_job ($ar, $data) {
	$string = '';
	foreach ($ar as $k => $v) {
		if (strpos($data['1.5.' . $v], 'ok') === false)
		exit;
		$value = $data['1.4.' . $v];
		$string .=  $k . ':' . round(apply_precision($value, $data['1.3.' . $v])) . ' ';
	}
	print_r($string);
	// comment
}

function apply_precision ($val, $data) {
	$d = explode(' ', $data);
	$dInt = (int)$d[1];
	$v = explode(' ', $val);
	$vInt = (int)$v[1];
	$res = $vInt / pow(10 , $dInt);
	return $res;
}	


execute_job(define_job($job), $res1);