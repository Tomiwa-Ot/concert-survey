<?php

require_once __DIR__ . '/vendor/autoload.php';

use Project\Library\Env;
use Project\Library\Router;
use Project\Library\Database;
use Project\Controller\Admin;
use Project\Controller\Contact;
use Project\Controller\Event;
use Project\Controller\Home;
use Project\Controller\Faq;

foreach (glob(__DIR__ . '/Controller/*.php') as $file) {
    if ($file === __DIR__ . '/Controller/BaseController.php') continue;
    require_once $file;
}

Env::load(__DIR__ . '/.env');

/** @var object $route */
$route = new Router();

$admin = new Admin();
$contact = new Contact();
$event = new Event();
$faq = new Faq();
$home = new Home();

$pdo = Database::getpdo();

$statement = $pdo->prepare("SELECT * FROM concerts WHERE active=?");
$statement->execute([1]);

$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    $uri = "/event/" . str_replace(" ", "-", $row['title']);

    $route->get($uri, function() use (&$event) {
        $event->get();
    });

    $route->post($uri, function () use (&$event) {
        $event->post();
    });

    $uri = "/admin/event/" . str_replace(" ", "-", $row['title']);

    $route->get($uri, function() use (&$event) {
        $event->adminView();
    });

    $route->post($uri, function() use (&$event) {
        $event->addArtist();
    });

    $route->get($uri . '/edit', function() use (&$event) {
        $event->editEvent();
    });

    $route->post($uri . '/edit', function() use (&$event) {
        $event->editEvent();
    });
}