<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function ($request, $response, $args) {
    $pokemonModel = new \App\Model\Pokemon();
    $pokemons = $pokemonModel->findAll();
    return $this->view->render($response, 'pokemons/list.html.twig', [
        'pokemons' => $pokemons
    ]);
})->setName('pokemons');

$app->get('/type/{type}', function ($request, $response, $args) {
    $pokemonModel = new \App\Model\Pokemon();
    $type = $args['type'];
    $pokemons = $pokemonModel->findByType($type);
    return $this->view->render($response, 'pokemons/list.html.twig', [
        'pokemons' => $pokemons
    ]);
})->setName('type');


$app->get('/pokemon/{slug}', function ($request, $response, $args) {
    $pokemonModel = new \App\Model\Pokemon();
    $slug = $args['slug'];
    $pokemon = $pokemonModel->findBySlug($slug);
    $types = $pokemonModel->findType($pokemon['id']);
    return $this->view->render($response, 'pokemons/unique.html.twig', [
        'pokemon' => $pokemon,
        'types' => $types
    ]);
})->setName('pokemon');


