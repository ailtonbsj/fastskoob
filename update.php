<?php
namespace Tutorial;

session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  $pass = $_SESSION['pass'];
} else {
  exit();
}

require('vendor/autoload.php');

use Goutte\Client;
$client = new Client();

//book url
$book = $_POST['id'];
$texto = $_POST['texto'];
$paginas = $_POST['paginas'];
$nota = $_POST['nota'];
$hist = "https://www.skoob.com.br/estante/s_historico_leitura/$book";
//Login
$crawler = $client->request('GET','http://www.skoob.com.br/login/');
$form = $crawler->selectButton('Entrar')->form();
//UpHist
$crawler = $client->submit($form,
array('data[Usuario][email]' => $email, 'data[Usuario][senha]' => $pass)
);
$crawler = $client->request('GET',$hist);
$form = $crawler->selectButton('Gravar histórico de leitura')->form();
$crawler = $client->submit($form,
  array(
  'data[LendoHistorico][texto]' => $texto,
  'data[LendoHistorico][paginas]' => $paginas,
  'data[LendoHistorico][nota]' => $nota)
);
echo $crawler->html();
 ?>
