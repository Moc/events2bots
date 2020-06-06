<?php

require_once("../../class2.php");


$array = array(
    'fruit1' => array('apple', 'grapes'),
    'fruit2' => 'orange',
    'fruit3' => 'pineapple',
);

// this cycle echoes all associative array
// key where value equals "apple"
while ($fruit_name = current($array)) {
    if ($fruit_name == 'orange') {
        echo key($array).'<br />';
    }
    next($array);
}
