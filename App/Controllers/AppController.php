<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

  public function timeline(){

    $this->validaAutenticacao();
    
    //recuperação dos tweets
    $tweet = Container::getModel('Tweet');

    $tweet->__set('id_usuario', $_SESSION['id']);

    $tweets = $tweet->getAll();

    /* echo '<pre>';
    print_r($tweets);
    echo '</pre>'; */

    $this->view->tweets = $tweets;

    $this->render('timeline');

  }

  public function tweet(){

    $this->validaAutenticacao();
    
    $tweet = Container::getModel('Tweet');
    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->__set('id_usuario', $_SESSION['id']);
    
    $tweet->salvar();
    header('Location: /timeline');

  }
  
  public function validaAutenticacao(){

    session_start();

    if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
      header('Location: /?login=erro');
    }
  }

  public function quemSeguir(){
    
    $this->validaAutenticacao();

    // echo '<br/><br/><br/><br/><br/><br/><br/>';
    // echo '<pre>';
    $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

    // echo '</pre>';
    // echo 'Pesquisando por: '.$pesquisarPor;
    
    $usuarios = array();
    
    // echo '<br/><br/><br/><br/><br/><br/><br/>';
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    
    if($pesquisarPor != ''){

      $usuario = Container::getModel('Usuario');
      $usuario->__set('nome', $pesquisarPor);
      $usuario->__set('id', $_SESSION['id']);
      $usuarios= $usuario->getAll();
      
      // echo '<pre>';
      // print_r($usuarios);
      // echo '</pre>';
      
    }
    
    $this->view->usuarios = $usuarios;
    
    $this->render('quemSeguir');
  }

  public function acao(){

    $this->validaAutenticacao();

    /* echo '<pre>';
    print_r($_GET);
    echo '</pre>'; */

    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    if($acao == 'seguir'){
      $usuario->seguirUsuario($id_usuario_seguindo);
    }else if($acao == 'deixar_de_seguir'){
      $usuario->deixarSeguirUsuario($id_usuario_seguindo);
    }

  }
}

?>