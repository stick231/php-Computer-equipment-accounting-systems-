<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use Controllers\AuthMiddleWare;
use Phroute\Phroute\RouteCollector;
use Controllers\TableController;
use Entities\Database;
use Entities\TableDevice;
use Repository\TableRepository;
use Factory\TableFactory;
use Phroute\Phroute\Dispatcher;
use CustomTable\ColorCustom;
use Controllers\AuthController;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router = new RouteCollector();

$authController = new AuthController();

$router->get('/check-user', function() use ($authController){
    $authController->checkUser();
});

$router->any('/auth', function() use($authController){
    $authController->redirectToAuth();
});

$router->any('/register', function() use($authController){
    $authController->redirectToRegister();
});

$authMiddleWare = new AuthMiddleWare();

$router->get('/', function() use($authMiddleWare) {
    $authMiddleWare->handle($_REQUEST, function(){
        if(isset($_GET['getColorTable'])){
            $CustomColorTable = new ColorCustom();
            $CustomColorTable->getColor();
        }
    });
});

$router->post('/api/custom-table', function(){
    $CustomColorTable = new ColorCustom();
    $CustomColorTable->saveColors();
});

$database = new Database();
$tableRepository = new TableRepository($database);

$router->post('/api/devices', function() use ($tableRepository) {
    try {
        $tableController = new TableController($tableRepository);
        $data = json_decode(file_get_contents('php://input'), true);
        
        $searchQuery = isset($data['search']) ? $data['search'] : '';
        $id = isset($data['id']) ? $data['id'] : null;
        $table = new TableDevice();

        if (!empty($searchQuery)) {
            $table->withSearch($searchQuery); 
        } elseif ($id !== null) {
            $table->withId($id); 
        }
        $tableController->read($table);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Произошла ошибка на сервере.']);
    }
});

$router->post('/api/latestAddedDevices', function() use ($tableRepository) {
    try {
        $tableController = new TableController($tableRepository);
        $tableController->readLatestAddedDevices();
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Произошла ошибка на сервере.']);
    }
});

$tableFactory = new TableFactory();

$router->post('/', function() use ($tableRepository, $tableFactory, $authMiddleWare){
    $authMiddleWare->handle($_REQUEST, function() use ($tableRepository, $tableFactory){
        $tableController = new TableController($tableRepository, $tableFactory);
        $actionMethods = $tableController::getActionMethodsTable();
    
        $action = null;
        foreach ($actionMethods as $key => $value) {
            if (isset($_POST[$key])) {
                $action = $value;
                break;
            }
        }
    
        if ($action !== null) {
            call_user_func([$tableController, $action]);
            exit;
        } else {
            echo json_encode(['error' => 'Некорректный запрос для напоминаний.']);
        }
    });
});

$dispatcher = new Dispatcher($router->getData());

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

try {
    echo $dispatcher->dispatch($httpMethod, $uri);
} catch (Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
    echo 'Метод не разрешен: ' . $e->getMessage();
    exit;
} catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo 'Маршрут не найден: ' . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo 'Произошла ошибка: ' . $e->getMessage();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Device management</title>
</head>
<body>
    <section id="deviceManagementSection">
    <header>
        <h1>Управление устройствами</h1>
    </header>
    <div id="Form">
        <form id="deviceForm">
            <div class="form__input">
                <input type="text" id="device_type" name="device_type" maxlength="15">
                <label class="form__label" for="device_type">Тип устройства:</label>
            </div>
            <div class="form__input">
                <input type="text" id="manufacturer" name="manufacturer" maxlength="15">
                <label class="form__label" for="manufacturer">Производитель:</label>
            </div>
            <div class="form__input">
                <input type="text" id="model" name="model" required maxlength="17">
                <label class="form__label" for="model">Модель:</label>
            </div>
            <div class="form__input">
                <input type="number" id="serial_number" name="serial_number" max="10000000" pattern="[0-9]{1,8}">
                <label class="form__label" for="serial_number">Серийный номер:</label>
            </div>
            <div class="form__input">
                <input type="date" id="purchase_date" name="purchase_date">
                <label class="form__label" for="purchase_date">Дата приобретения:</label>
            </div>
            <input type="submit" id="button-create" value="создать">
        </form>
        </table>
    <div>    
        <table id="AddedDevices">
            <h1>Последние добавленые устройства</h1>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тип устройства</th>
                    <th>Производитель</th>
                    <th>Модель</th>
                    <th>Серийный номер</th>
                    <th>Дата приобретения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="added-device-table"></tbody>
        </table>
    </div>
</section>
    </div> 
        <form id="form-search">
            <label for="search" id="form__label">Поиск устройств <input type="text" name="search" id="search-inp"></label>
        </form>
        <table id="devicesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тип устройства</th>
                    <th>Производитель</th>
                    <th>Модель</th>
                    <th>Серийный номер</th>
                    <th>Дата приобретения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>   
    <h2>Сделай свою таблицу сам</h2>
    <footer>
        <form id="form-color">
        <div class="container-color">
            <label class="label-color" for="inp-border-color">Цвет рамки</label>
            <input type="color" name="color_border" class="inp-color" id="color_border">
        </div>
        <div class="container-color">
            <label class="label-color" for="inp-border-color">Цвет текста</label>
            <input type="color" name="color_text" class="inp-color" id="color_text">
        </div>
        <div class="container-color">
            <label class="label-color" for="color_background">Цвет фона</label>
            <input type="color" name="color_background" class="inp-color" id="color_background">
        </div>
        <input type="button" id="color-button" value="Сохранить параметры">
        </form>
        <table id="table-example">
            <thead>
                <tr>
                    <th>
                        Это твоя будущая таблица, измени цвет и увидишь как она меняется
                    </th>
                </tr>
            </thead>
        </table>
    </footer>
     <script src="script.js"></script>
</body>
</html>  
