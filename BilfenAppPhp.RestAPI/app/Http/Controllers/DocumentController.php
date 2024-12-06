<?php

namespace App\Http\Controllers;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Firebase\JWT\JWT;

/**
 * @OA\Info(
 *       version="1.0.0",
 *       title="Bilfen API",
 *  )
 * @OA\PathItem(path="/api-docs")
 * @OA\Tag(name="Users", description="User management endpoints")
 */

/**
 * @OA\Schema(
 *     schema="Document",
 *     type="object",
 *     description="Document model",
 *     required={"Name", "FilePath", "Status", "CreatedAt", "UpdatedAt", "UserId"},
 *     @OA\Property(property="Id", type="integer", description="The auto-generated ID of the document", example=0),
 *     @OA\Property(property="Name", type="string", description="The document's name", example="string"),
 *     @OA\Property(property="FilePath", type="string", description="The document's file path", example="string"),
 *     @OA\Property(property="Status", type="string", description="The document's status", example="string"),
 *     @OA\Property(property="CreatedAt", type="string", description="The document's created date", example="string"),
 *     @OA\Property(property="UpdatedAt", type="string", description="The document's updated date", example="string"),
 *     @OA\Property(property="UserId", type="integer", description="The document's user ID", example=0),
 *     @OA\Property(property="User", type="object", description="The document's user", ref="#/components/schemas/User")
 * )
 */
class DocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/documents/GetAllDocuments",
     *      operationId="allDocuments",
     *      tags={"Documents"},
     *      description="Retrives a list of documents",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Document")
     *       )
     * )
     */
    public function GetAllDocuments(): JsonResponse
    {
        try {
            $documents = Document::with(['user' => function ($query) {
                $query->select('Id', 'FirstName', 'LastName', 'UserName', 'Email', 'Password', 'PasswordHash',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'RoleId');
            }, 'user.role' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                             DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->select(['Id', 'Name', 'FilePath', DB::raw("CASE
                                                                    WHEN Status = 'OB' THEN 'Onay Bekliyor'
                                                                    WHEN Status = 'O' THEN 'Onaylandı'
                                                                    ELSE 'Reddedildi' END AS Status"),
                                                   DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                   DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'UserId'])->get();


            $documents = $documents->map(function ($document) {
                return $this->dataFormatting($document);
            });

            return response()->json($documents, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/documents/GetAllDocumentsByUserId/{userId}",
     *      operationId="allDocumentsByUserId",
     *      tags={"Documents"},
     *      description="Retrives a list of documents by user id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Document")
     *       )
     * )
     */
    public function GetAllDocumentsByUserId($userId): JsonResponse
    {
        try {
            $documents = Document::with(['user' => function ($query) {
                $query->select('Id', 'FirstName', 'LastName', 'UserName', 'Email', 'Password', 'PasswordHash',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'RoleId');
            }, 'user.role' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('UserId', $userId)->select(['Id', 'Name', 'FilePath',
                                                            DB::raw("CASE
                                                                            WHEN Status = 'OB' THEN 'Onay Bekliyor'
                                                                            WHEN Status = 'O' THEN 'Onaylandı'
                                                                            ELSE 'Reddedildi' END AS Status"),
                                                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'UserId'])->get();

            $documents = $documents->map(function ($document) {
                return $this->dataFormatting($document);
            });

            return response()->json($documents, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/documents/GetDocument/{id}",
     *      operationId="documentById",
     *      tags={"Documents"},
     *      description="Retrives a document by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Document")
     *       )
     * )
     */
    public function GetDocument($id): JsonResponse
    {
        try {
            $document = Document::with(['user' => function ($query) {
                $query->select('Id', 'FirstName', 'LastName', 'UserName', 'Email', 'Password', 'PasswordHash',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'RoleId');
            }, 'user.role' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                             DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('Id', $id)->select(['Id', 'Name', 'FilePath', DB::raw("CASE
                                                                                             WHEN Status = 'OB' THEN 'Onay Bekliyor'
                                                                                             WHEN Status = 'O' THEN 'Onaylandı'
                                                                                             ELSE 'Reddedildi' END AS Status"),
                                                                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'UserId'])->first();

            if ($document) {
                $document = $this->dataFormatting($document);
                return response()->json($document, 200);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/documents/CreateDocument",
     *      operationId="createDocument",
     *      tags={"Documents"},
     *      description="Create a new document data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Document")
     *       )
     * )
     */
    public function CreateDocument(DocumentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $addedDocument = Document::create([
                'Name' => $validatedData['Name'],
                'FilePath' => $validatedData['FilePath'],
                'Status' => $validatedData['Status'],
                'UserId' => $validatedData['UserId']
            ]);

            $documentWithUserAndRole = Document::with(['user' => function ($query) {
                    $query->select('Id', 'FirstName', 'LastName', 'UserName', 'Email', 'Password', 'PasswordHash',
                                                 DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                 DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'RoleId');
                }, 'user.role' => function ($query) {
                    $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                 DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
                }])->where('Id', $addedDocument->Id)
                   ->select(['Id', 'Name', 'FilePath', DB::raw("CASE
                                                                        WHEN Status = 'OB' THEN 'Onay Bekliyor'
                                                                        WHEN Status = 'O' THEN 'Onaylandı'
                                                                        ELSE 'Reddedildi' END AS Status"),
                                                                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'UserId'])->first();

            if ($documentWithUserAndRole) {
                $documentWithUserAndRole = $this->dataFormatting($documentWithUserAndRole);
                return response()->json($documentWithUserAndRole, 200);
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen evraka ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/documents/EditDocument",
     *      operationId="editDocument",
     *      tags={"Documents"},
     *      description="Edit an existing document data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Document")
     *       )
     * )
     */
    public function EditDocument(DocumentRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $document = Document::find($validatedData['Id']);

            if($document) {
                Document::where('Id', $validatedData['Id'])
                    ->update([
                        'Name' => $validatedData['Name'],
                        'FilePath' => $validatedData['FilePath'],
                        'Status' => $validatedData['Status'],
                        'UserId' => $validatedData['UserId']
                    ]);

                $documentWithUserAndRole = Document::with(['user' => function ($query) {
                    $query->select('Id', 'FirstName', 'LastName', 'UserName', 'Email', 'Password', 'PasswordHash',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'RoleId');
                }, 'user.role' => function ($query) {
                    $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                 DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
                }])->where('Id', $document['Id'])
                    ->select(['Id', 'Name', 'FilePath', DB::raw("CASE
                                                                        WHEN Status = 'OB' THEN 'Onay Bekliyor'
                                                                        WHEN Status = 'O' THEN 'Onaylandı'
                                                                        ELSE 'Reddedildi' END AS Status"),
                                                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'UserId'])->first();

                $documentWithUserAndRole = $this->dataFormatting($documentWithUserAndRole);

                return response()->json($documentWithUserAndRole, 200);
            }

            return response()->json(['message' => 'Evrak kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen evraka ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/documents/DeleteDocument/{id}",
     *      operationId="deleteDocument",
     *      tags={"Documents"},
     *      description="Delete an existing document data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteDocument($id): JsonResponse
    {
        try {
            $deleted = Document::where('Id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Silme işlemi başarıyla yapıldı!'
                ]);
            } else {
                return response()->json([
                    'code' => 204,
                    'message' => 'Kullanıcı kaydı bulunamadı!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Silme işlemi sırasında bir hata oluştu! Hata: ' . $e->getMessage()
            ], 400);
        }
    }

    private function dataFormatting($data)
    {
        $data = $data->toArray();
        $data['createdAt'] = Carbon::parse($data['createdAt'])->format('d.m.Y H:i:s');
        $data['updatedAt'] = Carbon::parse($data['updatedAt'])->format('d.m.Y H:i:s');

        $data['User'] = $data['user'];
        unset($data['user']);
        $data['User']['createdAt'] = Carbon::parse($data['User']['createdAt'])->format('d.m.Y H:i:s');
        $data['User']['updatedAt'] = Carbon::parse($data['User']['updatedAt'])->format('d.m.Y H:i:s');

        $data['User']['Role'] = $data['User']['role'];
        unset($data['User']['role']);
        $data['User']['Role']['createdAt'] = Carbon::parse($data['User']['Role']['createdAt'])->format('d.m.Y H:i:s');
        $data['User']['Role']['updatedAt'] = Carbon::parse($data['User']['Role']['updatedAt'])->format('d.m.Y H:i:s');
        return $data;
    }
}
