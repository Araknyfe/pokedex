<?php

namespace App\Model;


class Pokemon
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=pokedex;port=3306', 'root', '');
    }

    public function findAll()
    {
        $sql = "SELECT
    p.`id`,
    p.`slug`,
    p.`name`,
    p.`height`,
    p.`weight`,
    p.`picture`,
    (SELECT t.name FROM pokemons_types pt
    JOIN types t ON pt.id_type = t.id
    WHERE pt.id_pokemon = p.id
    LIMIT 1) as main_type
  FROM
    pokemons p 
    ;
";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            throw new \Exception('Oh crappy SQL!');
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findBySlug($slug)
    {
        $sql = "SELECT
    p.`id`,
    p.`slug`,
    p.`name`,
    p.`height`,
    p.`weight`,
    p.`picture`
    FROM 
    pokemons p
    WHERE 
    slug = :slug
    LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug',$slug, \PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            throw new \Exception('Oh crappy SQL!');
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByType($type)
    {
        $sql = "SELECT
    p.`id`,
    p.`slug`,
    p.`name`,
    p.`height`,
    p.`weight`,
    if(p.`picture` != '', p.`picture`, '/pokemons/front/1.png') as picture,
    (SELECT t.name FROM pokemons_types pt
    JOIN types t ON pt.id_type = t.id
    WHERE pt.id_pokemon = p.id
    LIMIT 1) as main_type
    FROM 
    pokemons p
    JOIN pokemons_types pt ON p.id = pt.id_pokemon
    JOIN types t ON pt.id_type = t.id
    WHERE 
    t.name = :type
";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':type',$type, \PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            throw new \Exception('Oh crappy SQL!');
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findType(int $id)
    {
        $sql = "SELECT
    t.`id`,
    t.`name`
    FROM 
    types t
    JOIN pokemons_types pt ON pt.id_type = t.id
    WHERE
      pt.id_pokemon = :id
";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id',$id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            throw new \Exception('Oh crappy SQL!');
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}