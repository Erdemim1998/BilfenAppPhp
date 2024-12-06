<?php

namespace App\Http\Controllers;
use App\Http\Requests\RoleRequest;
use App\Models\Document;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
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
 *     schema="Role",
 *     type="object",
 *     description="Role model",
 *     required={"Name", "createdAt", "updatedAt"},
 *     @OA\Property(property="Id", type="integer", description="The auto-generated ID of the role", example=0),
 *     @OA\Property(property="Name", type="string", description="The role's name", example="string"),
 *     @OA\Property(property="createdAt", type="string", description="The role's created date", example="string"),
 *     @OA\Property(property="updatedAt", type="string", description="The role's updated date", example="string")
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/roles/GetAllRoles",
     *      operationId="allRoles",
     *      tags={"Roles"},
     *      description="Retrives a list of roles",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *       )
     * )
     */
    public function GetAllRoles(): JsonResponse
    {
        try {
            $roles = Role::select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') AS createdAt"),
                                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') AS updatedAt"))->get();

            $roles = $roles->map(function ($role) {
                return $this->dataFormatting($role);
            });

            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/roles/GetRole/{id}",
     *      operationId="roleById",
     *      tags={"Roles"},
     *      description="Retrives a role by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *       )
     * )
     */
    public function GetRole($id): JsonResponse
    {
        try {
            $role = Role::where('Id', $id)->selectRaw("Id, Name, DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt,
                                                             DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")->first();

            if ($role) {
                $role = $this->dataFormatting($role);
                return response()->json($role, 200);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/roles/CreateRole",
     *      operationId="createRole",
     *      tags={"Roles"},
     *      description="Create a new role data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *       )
     * )
     */
    public function CreateRole(RoleRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $addedRole = Role::create([
                'Name' => $validatedData['Name']
            ]);

            $role = Role::where('Id', $addedRole->Id)->selectRaw("Id, Name, DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt,
                                                                                  DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")->first();

            if ($role) {
                $role = $this->dataFormatting($role);
                return response()->json($role, 200);
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen role ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/roles/EditRole",
     *      operationId="editRole",
     *      tags={"Roles"},
     *      description="Edit an existing role data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *       )
     * )
     */
    public function EditRole(RoleRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $role = Role::find($validatedData['Id']);

            if($role) {
                Role::where('Id', $validatedData['Id'])
                    ->update([
                        'Name' => $validatedData['Name']
                    ]);

                $role = Role::where('Id', $role->Id)->select(['Id', 'Name',
                                                                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")])->first();

                $role = $this->dataFormatting($role);
                return response()->json($role, 200);
            }

            return response()->json(['message' => 'Rol kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen role ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/roles/DeleteRole/{id}",
     *      operationId="deleteRole",
     *      tags={"Roles"},
     *      description="Delete an existing role data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteRole($id): JsonResponse
    {
        try {
            $deleted = Role::where('Id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Silme işlemi başarıyla yapıldı!'
                ]);
            } else {
                return response()->json([
                    'code' => 204,
                    'message' => 'Rol kaydı bulunamadı!'
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
        return $data;
    }
}
