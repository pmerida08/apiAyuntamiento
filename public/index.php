<?php

require '../bootstrap.php';

use App\Controllers\ActividadesController;
use App\Controllers\CentCivicosController as CentCivicosController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\InscripcionesController;
use App\Controllers\InstalacionesController;
use App\Controllers\ReservasController;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");


// Responde a las solicitudes OPTIONS (preflight) de CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0); // No hacer nada para la solicitud OPTIONS
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $request);

$userId = null;
if (isset($uri[2])) {
    $userId = (int) $uri[2];
}

$input = json_decode(file_get_contents('php://input'), TRUE);
$jwt = null;
$userRole = null;

if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    $arr = explode(" ", $authHeader);
    if (count($arr) == 2) {
        $jwt = $arr[1];
    }
}

function isAuthenticated()
{
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return false;
    }
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    $arr = explode(" ", $authHeader);
    $jwt = $arr[1];
    if (!$jwt) {
        return false;
    }
    try {
        $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));
        $userRole = "usuario";
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$router = new Router();


// Definir rutas
$router->add([
    'name' => 'login',
    'path' => '/^\/login\/$/',
    'action' => UserController::class,
]);

$router->add([
    'name' => 'register',
    'path' => '/^\/register\/$/',
    'action' => UserController::class,
]);


$router->add([
    'name' => 'tokenRefresh',
    'path' => '/^\/token\/refresh$/',
    'action' => UserController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'infoUser',
    'path' => '/^\/user$/',
    'action' => UserController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'updateUser',
    'path' => '/^\/user$/',
    'action' => UserController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'deleteUser',
    'path' => '/^\/user$/',
    'action' => UserController::class,
    'auth' => ['usuario'],
]);

// Centros
$router->add([
    'name' => 'centros',
    'path' => '/^\/centros$/',
    'action' => CentCivicosController::class,
]);

$router->add([
    'name' => 'infoCentro',
    'path' => '/^\/centros\/(\d+)$/',
    'action' => CentCivicosController::class,
]);

// Instalaciones
$router->add([
    'name' => 'instalacionCentroCiv',
    'path' => '/^\/centros\/(\d+)\/instalaciones$/',
    'action' => InstalacionesController::class,
]);

$router->add([
    'name' => 'instalaciones',
    'path' => '/^\/instalaciones$/',
    'action' => InstalacionesController::class,
]);

// Actividades
$router->add([
    'name' => 'actividadesCentroCiv',
    'path' => '/^\/centros\/(\d+)\/actividades$/',
    'action' => ActividadesController::class,
]);

$router->add([
    'name' => 'actividades',
    'path' => '/^\/actividades$/',
    'action' => ActividadesController::class,
]);

// Reservas
$router->add([
    'name' => 'nuevaReservas',
    'path' => '/^\/reservas$/',
    'action' => ReservasController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'cancelarReservas',
    'path' => '/^\/reservas\/(\d+)$/',
    'action' => ReservasController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'misReservas',
    'path' => '/^\/reservas\/(\d+)$/',
    'action' => ReservasController::class,
    'auth' => ['usuario'],
]);

// Inscripciones
$router->add([
    'name' => 'nuevaInscripcion',
    'path' => '/^\/inscripciones$/',
    'action' => InscripcionesController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'cancelarInscripcion',
    'path' => '/^\/inscripciones\/(\d+)$/',
    'action' => InscripcionesController::class,
    'auth' => ['usuario'],
]);

$router->add([
    'name' => 'misInscripciones',
    'path' => '/^\/inscripciones\/(\d+)$/', 
    'action' => InscripcionesController::class,
    'auth' => ['usuario'],
]);

$route = $router->match($request);
if ($route) {
    if (!empty($route['auth']) && !isAuthenticated() && !in_array($userRole, $route['auth'])) {
        echo json_encode([
            "message" => "Acceso no autorizado",
            "role" => $userRole,
            "route" => $route['auth']
        ]);
        exit(http_response_code(403));
    }

    $controllerName = $route['action'];
    $controller = new $controllerName($requestMethod, $userId);
    $controller->processRequest();
} else {
    echo json_encode(["message" => "Ruta no encontrada"]);
    exit(http_response_code(404));
}
