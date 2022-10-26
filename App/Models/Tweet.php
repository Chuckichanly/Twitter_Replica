<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {
  private $id;
  private $id_usuario;
  private $tweet;
  private $data;

  public function __get($atributo){
    return $this->$atributo;
  }

  public function __set($atributo, $valor){
    $this->$atributo = $valor;
  }

  //salvar
  public function salvar(){

    $query = "insert into tweets(id_usuario, tweet)values(:id_usuario, :tweet)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindValue(':tweet', $this->__get('tweet'));
    $stmt->execute();

    return $this;
  }

  //recuperar (printar na tela timeline)
  public function getAll(){

    $query = "
      SELECT 
        t.id, 
        t.id_usuario, 
        u.nome, 
        t.tweet, 
        DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
      from 
        tweets as t
        left join usuarios as u on (t.id_usuario = u.id)
      where 
        t.id_usuario = :id_usuario
        or t.id_usuario in (SELECT id_usuario_seguindo FROM `usuarios_seguidores` WHERE id_usuario = :id_usuario)
      order by
        t.data desc
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

}

/* Como implementar a função para eliminar os seus tweets:
  +crie uma rota O
  -faça um envio de paramteros para essa rota
  -com base nesa rota execute um controlardor e  detrno dese controlador uma action
  -faça com q essa action trabalhe essa logica de remoçao do tweet
  -redirecione para a timeline para ver a remoçao do tweet
  
  JA FIZEMOS DIVERSAS VEZES O FLUXO É BEM PARECDO COM 'DEIXAR DE SEGUIR' OU 'SEGUIR'*/
?>