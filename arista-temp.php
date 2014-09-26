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
  //$tableOid = '.1.3.6.1.2.1.99.1.1';
  $oid = 'ENTITY-SENSOR-MIB::entPhySensorTable';
  $desc = 'ENTITY-MIB::entPhysicalDescr';

  $ar = new SNMP(SNMP::VERSION_2C, $host, $community);
  $res1 = $ar->walk($oid, true);
  //$res2 =  $ar->walk($desc,true);
  //print_r($res1);
  //print_r($res2);
  //die;
}

// $final = array();

// foreach ($res2 as $k2 => $v2) {
//   foreach ($res1 as $k1 => $v1) {
//   	//print_r('k2 is = ' . $k2 . '.v2 .' . $v2 . '. k1 ' . $k1 . ' and v2=' . $v2 . "\n");
//     if  (strpos($k1, '1.1.') === 0){
//     	$final[$k2]['si'] = $v1;
//     	break;
// 	}
//     if  (strpos($k1, '1.2.') === 0){
//     	$final[$k2]['metric'] = $v1;
//     }
//     if  (strpos($k1, '1.3.') === 0){
//     	$final[$k2]['precision'] = $v1;
//     	break;
//     }
    
//     if  (strpos($k1, '1.4.') === 0){
//     	$final[$k2]['value'] = $v1;
//     	break;
//     }
//     if  (strpos($k1, '1.5.') === 0){
//     	$final[$k2]['status'] = $v1;
//     	break;
// 	}
    
//     if  (strpos($k1, '1.6.') === 0){
//     	$final[$k2]['units'] = $v1;
//     	break;
// 	}
//     $final[$k2]['description'] = $v2;
//   }
// }


function define_job ($job) {
  $res = array();
    if (strpos($job, 'temp') === 0 ) {
      if (strpos($job, ':all') !== false) {
        $res['front'] = '100006001';
        $res['fan1'] = '100006002';
        $res['fan2'] = '100006003';
        $res['psu1'] = '100711101';
        $res['psu2'] = '100721101';
      }
      else {
        $exp_job = explode(':', $job);
        unset($exp_job[0]);
        foreach ($exp_job as $v3) {
        	$res['rand_' . generateRandomString(10)] = $v3;
        }
      }     
    }
    return $res;
}


function execute_job ($ar, $data) {
	$string = '';
	foreach ($ar as $k => $v) {
		$value = $data['1.4.' . $v];
		$string .=  $k . ':' . apply_precision($value, $data['1.3.' . $v]) . ' ';
	}
	print_r($string);
	// comment
}

function apply_precision ($val, $data) {
	
	
	$d = explode(' ', $data);
	$dInt = (int)$d[1];
	var_dump($val);
	exit;
	$res = $val / pow(10 , $dInt);
	return $res;
}	


execute_job(define_job($job), $res1);