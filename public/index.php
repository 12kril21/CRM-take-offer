<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Загружаем .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Используем PHP-DI для контейнера
$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

// Добавим Middleware для парсинга JSON
$app->addBodyParsingMiddleware();

// Пример роутов
$app->get('/', function ($req, $res) {
    $res->getBody()->write('Hello from Slim MyCRM!');
    return $res;
});

// Здесь подключим наши контроллеры
(require __DIR__ . '/../src/routes.php')($app);

$app->run();
