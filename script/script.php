<?php
function query() {
    // Obtém a variável de ambiente VAR1
    $var1 = getenv('VAR1');
    echo "VAR1: $var1\n";  // Depuração: mostrar o valor de VAR1

    // API settings
    $key = 'YoD62Zf677c3bce57914';
    $secret = 'YoD62Zf677c3bce57915';
    $trading_url = 'http://' . rtrim($var1, '/'); // Corrigir para remover barra extra no final

    echo "Trading URL: $trading_url\n";  // Depuração: mostrar a URL gerada

    $module = 'trunk';
    $action = 'save';

    // Inicializa o array $req
    $req = [];
    $req['allow'] = 'g729,g723,gsm,opus,alaw,ulaw';
    $req['id'] = '2';
    $req['module'] = $module;
    $req['action'] = $action;

    // Gera um nonce
    $mt = explode(' ', microtime());
    $req['nonce'] = $mt[1] . substr($mt[0], 2, 6);

    // Gera a string de dados POST
    $post_data = http_build_query($req, '', '&');
    $sign = hash_hmac('sha512', $post_data, $secret);

    // Gera os cabeçalhos extras
    $headers = [
        'Key: ' . $key,
        'Sign: ' . $sign,
    ];

    // Inicializa a handle do cURL
    static $ch = null;
    if (is_null($ch)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/4.0 (compatible; MagnusBilling PHP bot; ' . php_uname('a') . '; PHP/' . phpversion() . ')'
        );
    }

    curl_setopt($ch, CURLOPT_URL, $trading_url . '/mbilling/index.php/' . $module . '/' . $action);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Executa a query
    $res = curl_exec($ch);

    if ($res === false) {
        echo "Curl error: " . curl_error($ch) . "\n";  // Depuração: mostrar o erro do cURL
        exit;
    }

    echo "Response: \n$res\n";  // Depuração: mostrar a resposta

    $dec = json_decode($res, true);
    if (!$dec) {
        echo "Invalid JSON response: \n$res\n";  // Depuração: mostrar resposta não JSON
        exit;
    } else {
        return $dec;
    }
}

// Chama a função e exibe o resultado
$query_result = query();
var_dump($query_result);  // Depuração: mostrar o conteúdo da resposta
?>