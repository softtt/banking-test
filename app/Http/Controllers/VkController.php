<?php

namespace App\Http\Controllers;

use App\Services\Api\VkIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VkController extends Controller
{

    private function getApiService(): VkIntegrationService
    {
        return app(VkIntegrationService::class);
    }

    public function setCurrenciesInGroup(): JsonResponse
    {
        $this->getApiService()->setCurrenciesIntoPublicPage();

        return response()->json(array('set' => true));
    }

    public function getPosts(): JsonResponse
    {
        $posts = $this->getApiService()->getPosts();

        return response()->json($posts);
    }
}
