<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *       version="1.0.0",
 *       title="Bilfen API",
 *  )
 * @OA\PathItem(path="/api-docs")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/documents/GetAllDocuments",
     *      operationId="getExample",
     *      tags={"Documents"},
     *      description="Retrives a list of documents",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     */
    public function index()
    {
        // Your logic here
    }
}
