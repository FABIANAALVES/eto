<?php

$v10 = 4.7;
$tMedia = 26.5;
$tMaxima = 31.4;
$tMinima = 25.49;
$Urmax = 90.0;
$Urmin = 45.0;
$mes = 1;
$dia = 1;
$Rg = 13.65;
$UR = 70.7;


# Coluna L
function v2(){
	global $v10;
	return 0.748 * $v10;		
}
# Coluna M
function mj_kg(){
    global $tMedia;

	return 2.501-(0.002361)* $tMedia;
}
# Coluna N 
function kPa_oC($dados){
    $tMedia = $dados["temperatura_media"];
    return (4098*Kpa())/  (($tMedia+237.3) ** 2);
}
# Coluna O
function maxKpa($dados){
    $tMaxima = $dados["temperauta_maxima"];
    return 0.61078*exp((17.269* $tMaxima)/(237.3+ $tMaxima));
}
# Coluna P
function minKpa($dados){
    $tMinima = $dados["temperatura_minima"];
    return 0.61078*exp((17.269* $tMinima)/(237.3+ $tMinima));
}
# Coluna Q
function Kpa(){
    return (maxKpa()+minKpa())/2;
}
# Coluna R
function kPa2(){
    global $Urmax; 
    global $Urmin;
    return +(maxKpa()*$Urmin+minKpa()*$Urmax)/200;
}
# Coluna S
function kPaC1(){
	return 0.067;
}
# Coluna T
function kPaC2(){
	return kPaC1() * (1 + 0.33 * v2());
}
# Coluna U
function j(){
    global $mes;
    global $dia;
	return 30*$mes+$dia-30;
}
# Coluna V
function dr(){
    return 1+0.033 * cos(2* 3.1416 / 365 * j());
}
#coluna W
function delta(){
	return 0.4093 * sin(  (2*  3.1416 / 365 * j() ) -1.405  );
}
# Coluna X
function ws(){
    return acos((-tan(-5.18*3.1416/180))*tan(delta()));
}
# Coluna Y
function ra(){
    return 37.586*dr()*(ws()*sin(-5.18*3.1416/180)*sin(delta())+cos(-5.18*3.1416/180)*cos(delta())*sin(ws()));
}
# Coluna Z 
function Rso(){
    return +(0.75+72*0.00002)*ra();
}
# Coluna AA
function n(){
    return 24/3.1416*ws();
}
# Coluna AB ERROR
function Rb(){
    global $Rg;
    global $tMinima;
    global $UR;
    return -(1.35*($Rg/Rso())-0.35)*((0.34-0.14*(kPa2())**0.5))*0.000000004903*((($tMinima+273.16)**4)+(($UR+273.16)**4))*0.5+(-0.39);
}
# Coluna AC
function Rns(){
    global $Rg;
    return +$Rg*(1-0.23);
}
# Coluna AD ERROR
function Rn(){
    return +Rns()+Rb();
}
# Coluna AE ERROR
function ETo($dados){
    $tMedia = $dados['temperatura_media'];
    return (0.408*kPa_oC($dados)*Rn()+kPaC1()*900*v2()*(Kpa()-kPa2())/($tMedia+273))/(kPa_oC()+kPaC1()*(1+0.34*v2()));
}

#print v2();
#print mj_kg();
#print kPa_oC();
#print maxKpa();
#print minKpa();
#print Kpa();
#print kPa2();
#print kPac1();
#print kPac2();	
#print j();
#print dr();
#print delta();
#print ws();
#print ra();
#print Rso();
#print n();
#print Rb();
#print Rns();
#print Rn();
#print ETo();



?>