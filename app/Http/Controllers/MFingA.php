<?php
$address=__DIR__;
$arr_add=explode("\\",$address);
$address=$arr_add[0];
for($i=1;$i<(count($arr_add)-3);$i++)
{
    $address=$address."\\".$arr_add[$i];

}
pclose(popen('start /B cmd /C "'.'cd '.$address.' && php artisan Follow:mapfollowing '.$argv[1].' >NUL 2>NUL"', 'r'));



?>