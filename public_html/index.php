<?php

declare(strict_types=1);

use App\Domain\Employee;
use App\Helper\Converter;
use App\Helper\PeselParser;
use App\Service\EmployeeService;
use App\Validator\PeselValidator;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Container;
use Slim\Exception\NotFoundException;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;


/**
 * @var Container $container
 */
$container = require_once '../bootstrap.php';

$app = new App($container);

// Register twig view component on container
$container['view'] = function ($container) {
    $view = new Twig('../templates', [
//        'cache' => '../var/cache/twig'
        'cache' => false
    ]);

    $router = $container->get('router');
    $uri = Uri::createFromEnvironment(new Environment($_SERVER));
    $view->addExtension(new TwigExtension($router, $uri));
    return $view;
};


$app->get('/', function (Request $request, Response $response, array $args) {

    /**
     * @var EmployeeService $employeeService
     */
    $employeeService = $this->get(EmployeeService::class);
    $employees = $employeeService->getEmployees();
    /**
     * @var PeselParser $peselParser
     */
    $peselParser = $this->get(PeselParser::class);

    return $this->view->render($response, 'index.html.twig', [

        'employees' => array_map(function(Employee $employee) use($peselParser){

            $arr = Converter::convertEntityToArray($employee);
            $peselParser->setPesel($employee->getPesel());
            $arr['dateOfBirth'] = $peselParser->getDateOfBirthText();
            $arr['gender'] = $peselParser->getGenderText();
            return $arr;

        },$employees)
    ]);

})->setName("index");

$app->get('/edit/{id}', function (Request $request, Response $response, array $args) {

    $employeeId = (int)$args['id'];
    /**
     * @var EmployeeService $employeeService
     */
    $employeeService = $this->get(EmployeeService::class);
    $employee = $employeeService->getOneEmployee($employeeId);


    return $this->view->render($response, 'edit.html.twig', [
        'employee' => Converter::convertEntityToArray($employee)
    ]);

})->setName("edit");

$app->get('/view/{id}', function (Request $request, Response $response, array $args) {

    $employeeId = (int)$args['id'];

    /**
     * @var EmployeeService $employeeService
     */
    $employeeService = $this->get(EmployeeService::class);
    $employee = $employeeService->getOneEmployee($employeeId);
    /**
     * @var PeselParser $peselParser
     */
    $peselParser = $this->get(PeselParser::class);
    $peselParser->setPesel($employee->getPesel());

    return $this->view->render($response, 'view.html.twig', [
        'employee' => Converter::convertEntityToArray($employee) +
            ['dateOfBirth' => $peselParser->getDateOfBirthText()] +
            ['gender' => $peselParser->getGenderText()]
    ]);

})->setName("view");


$app->post('/edit/{id}', function (Request $request, Response $response, array $args) {

    $employeeId = (int)$args['id'];

    /**
     * @var EmployeeService $employeeService
     */
    $employeeService = $this->get(EmployeeService::class);
    $employee = $employeeService->getOneEmployee($employeeId);

    if(empty($employee)){
        throw new NotFoundException($request, $response);
    }

    $data = $request->getParsedBody();

    if(isset($data['delete'])){

        $employeeService->deleteEmployee($employee);
        return $response->withRedirect('/', 301);

    }else{
        $employee->setFirstName(filter_var($data['firstName'], FILTER_SANITIZE_STRING));
        $employee->setLastName(filter_var($data['lastName'], FILTER_SANITIZE_STRING));
        $employee->setAddress(filter_var($data['address'], FILTER_SANITIZE_STRING));
        $employee->setPesel(filter_var($data['pesel'], FILTER_SANITIZE_STRING));

        /**
         * @var PeselValidator $validator
         */
        $validator = $this->get(PeselValidator::class);


        if(!$validator->validate($employee->getPesel())){
            $error = $validator->getError();

            return $this->view->render($response, 'edit.html.twig', [
                    'employee' => Converter::convertEntityToArray($employee),
                    'peselErrorMessage' => $error
            ]);

        }

        $employeeService->saveOneEmployee($employee);
        return $response->withRedirect('/view/'.$employeeId, 301);
    }

})->setName("edit_post");

$app->get('/create', function (Request $request, Response $response, array $args) {

    $employee = new Employee();
    return $this->view->render($response, 'create.html.twig', [
        'employee' => Converter::convertEntityToArray($employee)
    ]);

})->setName("create");

$app->post('/create', function (Request $request, Response $response, array $args) {


    /**
     * @var EmployeeService $employeeService
     */
    $employeeService = $this->get(EmployeeService::class);
    $employee = new Employee();

    $data = $request->getParsedBody();


    $employee->setFirstName(filter_var($data['firstName'], FILTER_SANITIZE_STRING));
    $employee->setLastName(filter_var($data['lastName'], FILTER_SANITIZE_STRING));
    $employee->setAddress(filter_var($data['address'], FILTER_SANITIZE_STRING));
    $employee->setPesel(filter_var($data['pesel'], FILTER_SANITIZE_STRING));

    /**
     * @var PeselValidator $validator
     */
    $validator = $this->get(PeselValidator::class);


    if(!$validator->validate($employee->getPesel())){
        $error = $validator->getError();

        return $this->view->render($response, 'create.html.twig', [
            'employee' => Converter::convertEntityToArray($employee),
            'peselErrorMessage' => $error
        ]);
    }

    $employee = $employeeService->saveOneEmployee($employee);
    return $response->withRedirect('/view/'.$employee->getId(), 301);


})->setName("create_post");


$app->run();
