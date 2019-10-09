<?php
#ajusta o horario do PHP
date_default_timezone_set('America/Bahia'); 

#cria a variavel para armazenar o cookie
$_cookie = "";


#123

#funcao que sera usada para acessar a pagina
function get_url($url, $dados=null){
    global $_cookie;

    #cria o cabecalho de requisição
    $headers = "";
    
    #caso venha dados, sera POST
    if ($dados != null){
        $method = "POST";
        $postdata = http_build_query($dados);
        $headers .= "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: 125\r\n";
    } else {
        #caso nao venha dados, sera GET
        $method = "GET";
        $postdata = [];
        $headers = "";
    }

    #adiciona o cookie, caso exista
    if ($_cookie != ""){
        $headers .= "Cookie:$_cookie\r\n";
    }

    #constroi a requisicao
    $req = array(
        'method'  => $method,
        'header'  => $headers,
    );

    #adiciona os dados, se existirem
    if ($dados != null){
        $req['content'] = $postdata;
    }

    #converte a requisicao para o formato aceito pelo PHP
    $opts = array('http' => $req);
    $context  = stream_context_create($opts);


    /*print "<br>---------------------------------<pre>";
    print "Method:".$method."\r\n";
    print "URL:".$url."\r\n";
    print_r($headers);
    print "\r\n";
    print_r($postdata);
    print "</pre>---------------------------------<br>";*/
    
    #faz a requisicao e recebe a resposta
    $resp = file_get_contents($url, false, $context);

    #procura pelo Set-Cookie e salva o cookie setado
    foreach($http_response_header as $header){
        if (strstr($header,"Set-Cookie")){
            $header = explode(":",$header);
            $_cookie .= $header[1];
            $_cookie = str_replace(" path=/","",$_cookie);
        }
    }
    
    #responde com o HTML da pagina solicitada
    return $resp;
}


#funcao que ira fazer o download dos dados pelo dia
function download($data){

    #remove a barra da data
    $data = str_replace("/","-",$data);
    $fileName = $data."IP.html";

    if (file_exists($fileName)){
        return $fileName;
    }

    #URL para a estacao de IPANGUACU
    $url = "http://www.inmet.gov.br/sonabra/pg_dspDadosCodigo_sim.php?QTM3Mg==";

    #faz a primeira requisicao, para selecionar a estacao
    $html = get_url($url);

    #pega o valor do campo xaleaValue
    #esse campo sempre muda toda hora que a pagina e acessada
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $domX = new DOMXpath($dom);
    $nodes = $domX->query('//input[@name="xaleaValue"]');
    $val = $nodes->item(0)->getAttribute("value");
    #pega somente os numeros do campo, remove as letras
    $val = substr($val,0,14);
    
    #constroi o formulario para ser submetido
    $dados = ['aleaValue' => 'NDYzNg==',
                'xaleaValue' => $val.'NDYzNg==',
                'xID' => '467',
                'dtaini' => $data,
                'dtafim' =>	$data,
                'aleaNum'=>	'4636'];

    #envia o formulario para buscar os dados
    #essa pagina ja contem a tabela, porem a proxima pagina e mais lima
    $html = get_url($url, $dados);


    #pega o resultado da proxima pagina, os dados sao mais limpos, menos HTML
    $url = "http://www.inmet.gov.br/sonabra/pg_downDadosCodigo_sim.php";
    $html = get_url($url);

    
    #salva um arquivo html com a data+IP.html
    $f = fopen($fileName,"w");
    fwrite($f, $html);
    fclose($f);

    return $fileName;
}

/*$data = Date("d/m/Y");
download($data);
print "FIM";*/