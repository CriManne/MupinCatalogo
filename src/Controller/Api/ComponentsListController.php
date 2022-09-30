<?php

declare(strict_types=1);
namespace App\Controller\Api;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Response\HaltResponse;

class ComponentsListController implements ControllerInterface {    

    public static array $categories = [
        'Cpu',
        'Ram',
        'Os',
        'PeripheralType',
        'Publisher',
        'Author',
        'SoftwareType',
        'SupportType'         
    ];

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {        
                
        return new Response(
            200,
            [],
            json_encode(self::$categories)
        );
    }
}