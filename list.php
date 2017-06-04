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

//Login
$crawler = $client->request('GET','http://www.skoob.com.br/login/');
$form = $crawler->selectButton('Entrar')->form();
$crawler = $client->submit($form,
  array('data[Usuario][email]' => $email, 'data[Usuario][senha]' => $pass)
);
$respRaw = $crawler->html();

if(strpos($respRaw,'Login ou senha inv') !== false){
  echo '{"success" : false}';
  exit();
}
//get id
$meuPerfil = $crawler->selectLink('Meu Perfil')->link()->getUri();
$myid = explode('/',$meuPerfil)[4];
//Books
$crawler = $client->request('GET',"https://www.skoob.com.br/v1/bookcase/books/$myid/shelf_id:2/page:1/limit:9000/");
echo $crawler->filter('body > p')->html();
 ?>
