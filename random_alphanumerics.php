<?php 
set_time_limit(1000);
/*
Implements functions and add name in array called arrayFunctions
*/

$arrayFunctions = array();

// Change this params only
$totalInteractions = 100000; 
$size = 6; //size random string


function function_one($size) {
   return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $size);
}
array_push($arrayFunctions, 'function_one');


function crypto_rand_secure($min, $max) {
   $range = $max - $min;
   if ($range < 1) {
      return $min; // not so random...
   }
   $log = ceil(log($range, 2));
   $bytes = (int) ($log / 8) + 1; // length in bytes
   $bits = (int) $log + 1; // length in bits
   $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
   do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; // discard irrelevant bits
   } while ($rnd > $range);
   return $min + $rnd;
}

function function_two($length) {
   $token = "";
   $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
   $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
   $codeAlphabet.= "0123456789";
   $max = strlen($codeAlphabet); // edited

   for ($i=0; $i < $length; $i++) {
      $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
   }
   return $token;
}
array_push($arrayFunctions, 'function_two');

function function_tree($length) {
   $key = '';
   $keys = array_merge(range(0, 9), range('a', 'z'));

   for ($i = 0; $i < $length; $i++) {
      $key .= $keys[array_rand($keys)];
   }

   return $key;
}
array_push($arrayFunctions, 'function_tree');

function function_four($length) {
   $alphabets = range('a','z');
   $numbers = range('0','9');
   $final_array = array_merge($alphabets, $numbers);
   $password = '';
   while($length--) {
      $key = array_rand($final_array);
      $password .= $final_array[$key];
   }
   if (preg_match('/[A-Za-z0-9]/', $password)) {
      return $password;
   }else{
      return random_string();
   }
}
array_push($arrayFunctions, 'function_four');


function function_five($length = 6, $onlyNumbers = false) {
   $characters = "0123456789";
   if (!$onlyNumbers) {
      $characters .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   }
   $charactersLength = strlen($characters);
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
   }
   return $randomString;
}
array_push($arrayFunctions, 'function_five');

// Start benchmark
foreach ($arrayFunctions as $function){
   $array = array();
   $totalCollisions = 0;
   
   $start = microtime(true);
   for($i = 0; $i < $totalInteractions; $i++) {
   	  $new = call_user_func($function, $size);
	  if(array_search($new, $array)) {
	     $totalCollisions++;
	  }
	  array_push($array, $new);  
   }
   
   $time = number_format(( microtime(true) - $start), 2);
   echo '********************** <br />';
   echo '<b>' . $function . ' </b>benchmark results: <br />';
   echo '<b>Total interactions: </b>' . $totalInteractions . '<br />';
   echo '<b>Colisions with size (' . $size . '): </b>' .$totalCollisions . '<br />';
   echo '<b>Time processed: </b>' . $time . '<br />';
   echo '********************** <br /><br /><br />';
}



