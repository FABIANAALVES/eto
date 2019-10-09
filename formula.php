<?php
//Fabiana
$teste = true;


function es($tMedia){
    return 0.6108*pow(10,(7.5*$tMedia)/(237.3+$tMedia));
}
function s($tMedia){
    return (4098*es($tMedia))/pow($tMedia+237.3,2);
}
function ea($tMedia, $ur){
    return (es($tMedia)*$ur)/100;
}
function eto($tMedia, $rn, $g, $j, $u2, $ur){
    return (0.408*s($tMedia)*$rn-$g+($j*900*$u2*(es($tMedia)-ea($tMedia, $ur)))/($tMedia+273))/(s($tMedia)+$j*(1+0.34*$u2));
}


if ($teste){
    $tMedia = 26;
    $ur = 72;
    $u2 = 2;
    $rn = 7.8;
    $g = 0;
    $j = 0.063;
    #print s($tMedia); //3.36
    #print ea($tMedia, $ur); //2.42
    #print es($tMedia); //0.19
    print eto($tMedia, $rn, $g, $j, $u2, $ur); // 3.24
}

?>