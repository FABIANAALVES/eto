<?php

include"calcular.php";
include"download.php";
include "fabiana.php";

$data = new DateTime();
$data->modify('-1 day');
$data = $data->format('d/m/Y');

$arquivo = download($data);
$dados = getDadosArquivo($arquivo);

print_r($dados);

print ETo($dados);