<?php


function getDadosArquivo($arquivo){
    
    $html = file_get_contents($arquivo);

    # Trocar o br por quebra de linha

    $html = str_replace("<br>", "\r\n", $html);

    # Tira todas as tags

    $html = strip_tags($html);


    # Tira todos os espaços desnecessários
    $html = str_replace("\t", "", $html);
    $html = str_replace("vento_rajada,radiacao,precipitacao", "", $html);
    $html = str_replace("vento_vel,", "vento-vel,vento_rajada,radiacao,precipitacao", $html);
    $html = str_replace("\r\n\r\n", "", $html);
    $html = str_replace("codigo_estacao,data,hora,temp_inst,temp_max,temp_min,umid_inst,umid_max,umid_min,pto_orvalho_inst,pto_orvalho_max,pto_orvalho_min,pressao,pressao_max,pressao_min,vento_direcao,vento-vel,vento_rajada,radiacao,precipitacao", "", $html);
    $linhas = explode("\r\n", $html);
    print "<pre>";

    # Criar todas as variáveis para cada coluna

    $dia = 0;
    $mes = 0;
    $count = 0;
    $tMedia = 0;
    $tMaxima = 0;
    $tMinima = 100;
    $uMedia = 0;
    $uMaxima = 0;
    $uMinima = 1000;
    $oMedio = 0;
    $oMaximo = 0;
    $oMinimo = 100;
    $pMedia = 0;
    $pMaxima = 0;
    $pMinima = 10000;
    $ventoVel = 0;
    $ventoRajada = 0;
    $radiacao = 0;
    $precipitacao = 0;

    foreach($linhas as $linha){

        # Ignorar linhas em branco

        if (trim($linha) == ""){
            continue;
        }

        $count++;

        # Separo os dados de cada linha

        $dados = explode(",", $linha);

        if ($dia == 0){
            list($dia,$mes,$ano) = explode("/", $dados[1]);
        }

    # Separo os dados de cada linha

    # $dados = explode(",", $linha);

    # Somando

        $tMedia += $dados[3];
        $uMedia += $dados[7];
        $oMedio += $dados[9];
        $pMedia += $dados[12];
        $ventoVel += $dados[16];
        $ventoRajada += $dados[17];
        $radiacao += $dados[18];
        $precipitacao += $dados[19];

    # Mostra a maior

        if ($dados[4] > $tMaxima){
            $tMaxima = $dados[4];
        }
        if ($dados[8] > $uMaxima){
            $uMaxima = $dados[8];
        }
        if ($dados[10] > $oMaximo){
            $oMaximo = $dados[10];
        }
        if ($dados[13] > $pMaxima){
            $pMaxima = $dados[13];
        }

    # Mostra a menor 

        if ($dados[5] < $tMinima){
            $tMinima = $dados[5];
        }
        if ($dados[9] < $uMinima){
            $uMinima = $dados[9];
        }
        if ($dados[11] < $oMinimo){
            $oMinimo = $dados[11];
        } 
        if ($dados[14] < $pMinima){
            $pMinima = $dados[14];
        }  
    }
    # calcular a media

    $tMedia = $tMedia/$count;
    $uMedia = $uMedia/$count;
    $oMedio = $oMedio/$count;
    $pMedia = $pMedia/$count;
    $ventoVel = $ventoVel/$count;
    $ventoRajada = $ventoRajada/$count;
    $radiacao = $radiacao/$count;


    return ["dia"=>$dia,
        "mes"=>$mes,
        "temperatura_media" =>$tMedia,
        "temperatura_maxima" =>$tMaxima,
        "temperatura_minima" =>$tMinima,
        "umidade_media" =>$uMedia,
        "umidade_maxima" =>$uMaxima,
        "umidade_minima" =>$uMinima,
        "orvalho_medio" =>$oMedio,
        "orvalho_maximo" =>$oMaximo,
        "orvalho_minimo" =>$oMinimo,
        "pressao_media" =>$pMedia,
        "pressao_maxima" =>$pMaxima,
        "pressao_minima" =>$pMinima,
        "vento_vel" =>$ventoVel,
        "vento_rajada" =>$ventoRajada,
        "radiacao" =>$radiacao,
        "precipitacao" =>$precipitacao
     ];
}



#$arquivo = "pg_downDadosCodigo_sim.php.html";
#$dados = getDadosArquivo($arquivo);

#print"<pre>";
#print_r($dados);

