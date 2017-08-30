<?php
$year = 2022;
$month = 9;
$day = 15;
$date = $year.'-'.$month.'-'.$day;
$unix = strtotime($date);
echo $unix;
echo "<br>";
echo date("Y-m-d", $unix);
