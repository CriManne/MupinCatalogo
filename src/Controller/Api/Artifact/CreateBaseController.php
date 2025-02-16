<?php

declare(strict_types=1);

namespace App\Controller\Api\Artifact;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\Images\DeleteBaseController;
use App\Controller\Api\Images\UploadBaseController;
use App\Controller\BaseController;
use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\BadRequest;
use App\Plugins\Http\Responses\Created;
use App\Plugins\Injection\DIC;
use App\SearchEngine\ArtifactSearchEngine;
use App\Service\IArtifactService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class CreateBaseController extends BaseController implements ControllerInterface
{
    protected IArtifactService $artifactService;

    public function __construct(
        protected ArtifactSearchEngine $artifactSearchEngine
    )
    {
        parent::__construct();
    }

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->apiHandler(
            function () use ($request, $response) {
                $userEmail = $this->getLoggedUserEmail();

                $params = $request->getParsedBody();

                $category = $params["category"] ?? null;
                unset($params["category"]);

                $artifactCategories = ArtifactsListController::CATEGORIES;

                /**
                 * Return bad request response if no category is set or a wrong one
                 */
                $errorMessage = null;

                if (!$category) {
                    $errorMessage = "No category set!";
                } else if (!in_array($category, $artifactCategories)) {
                    $errorMessage = "Category not found!";
                }

                if ($errorMessage) {
                    $this->apiLogger->info($errorMessage, [__CLASS__, $userEmail]);

                    return ResponseFactory::createJson(
                        new BadRequest($errorMessage)
                    );
                }

                /**
                 * Get service class, throws an exception if not found
                 */
                $this->artifactService = DIC::getArtifactServiceByName($category);

                $instantiatedObject = $this->artifactService->fromRequest($params);

                $this->artifactService->save($instantiatedObject);

                //Delete remained old files
                DeleteBaseController::deleteImages($instantiatedObject->genericObject->id);

                //Upload new files
                UploadBaseController::uploadFiles($instantiatedObject->genericObject->id, 'images');

                $message = "{$category} saved successfully!";
                $this->apiLogger->info($message, [__CLASS__, $userEmail]);

                return ResponseFactory::createJson(
                    new Created($message)
                );
            }
        );
    }
}
