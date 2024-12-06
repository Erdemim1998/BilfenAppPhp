<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\Key;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Minishlink\WebPush\WebPush;
use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Firebase\JWT\JWT;
use PHPUnit\Exception;
use Minishlink\WebPush\VAPID;
use Minishlink\WebPush\Subscription;
use App\Mail\SendDocumentMail;
use Illuminate\Support\Facades\Mail;

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
 *     schema="User",
 *     type="object",
 *     description="User model",
 *     required={"FirstName", "LastName", "UserName", "Email", "Password", "PasswordHash", "createdAt", "updatedAt", "RoleId"},
 *     @OA\Property(property="Id", type="integer", description="The auto-generated ID of the user", example=0),
 *     @OA\Property(property="FirstName", type="string", description="The user's first name", example="string"),
 *     @OA\Property(property="LastName", type="string", description="The user's last name", example="string"),
 *     @OA\Property(property="UserName", type="string", description="The user's user name", example="string"),
 *     @OA\Property(property="Email", type="string", description="The user's email", example="string"),
 *     @OA\Property(property="Password", type="string", description="The user's password", example="string"),
 *     @OA\Property(property="PasswordHash", type="string", description="The user's hash password", example="string"),
 *     @OA\Property(property="createdAt", type="string", description="The user's created date", example="string"),
 *     @OA\Property(property="updatedAt", type="string", description="The user's updated date", example="string"),
 *     @OA\Property(property="RoleId", type="integer", description="The user's role id", example=0),
 *     @OA\Property(property="Role", type="object", description="The user's role", ref="#/components/schemas/Role")
 * )
 */
class UserController extends Controller
{
    private string $secretKey = '4af619f021d708d0c57c49f29eec55ed34e3fca806dceaac6322e8b57fbf0c017cad7aee128a393b8884954adf4173cd68f9d4ad3b841f9369b7e558cb74699303f1d391f01b934d52f2e606e410eb31a43c4be82e0a7b231c4a8f2f5c05ac4f87299493275a507208f51a0e4023fa3cc2b0d6acc63fc4ba9feafad6948c21ed72e628f90b948fa16c04d5aa872265e06826ff0707e04cbb9c39624e99f7705865700ea1ef39eb7ab2390d5a24cec7ab557b7ebe27cbb282ee217788ae167bf95ae7232dc2fe2c762a97edf1cc04ae81554d26666ff2c485fc15b40175de9eaf09e98a558d016f73a98a0e8b92205a44c3c7a4748a25b70bfe06d73a069021f7';

    /**
     * @OA\Get(
     *      path="/api/users/GetAllUsers",
     *      operationId="allUsers",
     *      tags={"Users"},
     *      description="Retrives a list of users",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       )
     * )
     */
    public function GetAllUsers(): JsonResponse
    {
        try{
            $users = User::with(['role' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'country' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'city' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CountryId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['country' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        );
                    }]);
                }, 'district' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CityId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['city' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            'CountryId',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        )->with(['country' => function ($innerQuery) {
                            $innerQuery->select(
                                'Id',
                                'Name',
                                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                            );
                        }]);
                    }]);
                },
            ])->select([
                'Id',
                'FirstName',
                'LastName',
                'UserName',
                'Email',
                'Password',
                'PasswordHash',
                'ImagePath',
                'TCKN',
                'MotherName',
                'FatherName',
                'BirthDate',
                DB::raw("CASE WHEN Gender = 'E' THEN 'Erkek' ELSE 'Kadın' END AS Gender"),
                'CivilStatus',
                'EmploymentDate',
                DB::raw("CASE WHEN MilitaryStatus = 'C' THEN 'Tamamlamış' WHEN MilitaryStatus = 'P' THEN 'Tecilli' WHEN MilitaryStatus = 'E' THEN 'Muaf' ELSE NULL END AS MilitaryStatus"),
                'PostponementDate',
                'Address',
                'RoleId',
                'CountryId',
                'CityId',
                'DistrictId',
                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"),
            ])->get();

            $users = $users->map(function ($user) {
                return $this->dataFormatting($user);
            });

            return response()->json($users, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/users/GetUser/{id}",
     *      operationId="getUser",
     *      tags={"Users"},
     *      description="Retrives a user by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       )
     * )
     */
    public function GetUser($id): JsonResponse
    {
        try{
            $user = User::with(['role' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'country' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'city' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CountryId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['country' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        );
                    }]);
                }, 'district' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CityId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['city' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            'CountryId',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        )->with(['country' => function ($innerQuery) {
                            $innerQuery->select(
                                'Id',
                                'Name',
                                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                            );
                        }]);
                    }]);
                },
            ])->where('Id', $id)->select([
                'Id',
                'FirstName',
                'LastName',
                'UserName',
                'Email',
                'Password',
                'PasswordHash',
                'ImagePath',
                'TCKN',
                'MotherName',
                'FatherName',
                'BirthDate',
                DB::raw("CASE WHEN Gender = 'E' THEN 'Erkek' ELSE 'Kadın' END as Gender"),
                'CivilStatus',
                'EmploymentDate',
                DB::raw("CASE WHEN MilitaryStatus = 'C' THEN 'Tamamlamış' WHEN MilitaryStatus = 'P' THEN 'Tecilli' WHEN MilitaryStatus = 'E' THEN 'Muaf' ELSE NULL END AS MilitaryStatus"),
                'PostponementDate',
                'Address',
                'RoleId',
                'CountryId',
                'CityId',
                'DistrictId',
                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"),
            ])->first();

            if($user) {
                $user = $this->dataFormatting($user);
                return response()->json($user, 200);
            }

            return response()->json(null, 204);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/users/Login",
     *      operationId="login",
     *      tags={"Users"},
     *      description="Create a new user data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/LoginAuthenticate")
     *       )
     * )
     */
    public function Login(Request $request): JsonResponse
    {
        $email = $request->input('Email');
        $password = $request->input('Password');

        try {
            $user = User::with('role')->where('Email', '=', $email, 'and')
                                              ->where('Password', '=', $password)->get()->first();

            if ($user) {
                $token = $this->generateJWT([
                    'Id' => $user->Id,
                    'Name' => $user->UserName,
                ]);

                return response()->json([
                    'token' => $token,
                    'isAuthenticated' => true,
                    'userId' => $user->Id,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Hatalı email veya parola!',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    private function generateJWT($user): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;

        $payload = [
            'Id' => $user['Id'],
            'Name' => $user['Name'],
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * @OA\Post(
     *      path="/api/users/CreateUser",
     *      operationId="createUser",
     *      tags={"Users"},
     *      description="Create a new user data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       )
     * )
     */
    public function CreateUser(UserRequest $request): JsonResponse
    {
        try{
            $validatedData = $request->validated();

            $addedUser = [
                'FirstName' => $validatedData['FirstName'],
                'LastName' => $validatedData['LastName'],
                'UserName' => $validatedData['UserName'],
                'Email' => $validatedData['Email'],
                'Password' => $validatedData['Password'],
                'PasswordHash' => password_hash($validatedData['Password'], PASSWORD_DEFAULT),
                'RoleId' => $validatedData['RoleId'],
                'ImagePath' => $validatedData['ImagePath'],
                'TCKN' => $validatedData['TCKN'],
                'MotherName' => $validatedData['MotherName'],
                'FatherName' => $validatedData['FatherName'],
                'BirthDate' => $validatedData['BirthDate'],
                'Gender' => $validatedData['Gender'],
                'CivilStatus' => $validatedData['CivilStatus'],
                'EmploymentDate' => $validatedData['EmploymentDate'],
                'MilitaryStatus' => $validatedData['MilitaryStatus'],
                'PostponementDate' => $validatedData['PostponementDate'],
                'CountryId' => $validatedData['CountryId'],
                'CityId' => $validatedData['CityId'],
                'DistrictId' => $validatedData['DistrictId'],
                'Address' => $validatedData['Address'],
            ];

            $user = User::create($addedUser);

            $userData = User::with(['role' => function ($query) {
                $query->select(
                    'Id',
                    'Name',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                );
            }, 'country' => function ($query) {
                $query->select(
                    'Id',
                    'Name',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                );
            }, 'city' => function ($query) {
                $query->select(
                    'Id',
                    'Name',
                    'CountryId',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                )->with(['country' => function ($subQuery) {
                    $subQuery->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }]);
            }, 'district' => function ($query) {
                $query->select(
                    'Id',
                    'Name',
                    'CityId',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                )->with(['city' => function ($subQuery) {
                    $subQuery->select(
                        'Id',
                        'Name',
                        'CountryId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['country' => function ($innerQuery) {
                        $innerQuery->select(
                            'Id',
                            'Name',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        );
                    }]);
                }]);
            },
            ])->where('Id', $user['Id'])->select([
                'Id',
                'FirstName',
                'LastName',
                'UserName',
                'Email',
                'Password',
                'PasswordHash',
                'ImagePath',
                'TCKN',
                'MotherName',
                'FatherName',
                'BirthDate',
                'Gender',
                'CivilStatus',
                'EmploymentDate',
                'MilitaryStatus',
                'PostponementDate',
                'Address',
                'RoleId',
                'CountryId',
                'CityId',
                'DistrictId',
                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"),
            ])->first();

            if ($userData) {
                $userData = $this->dataFormatting($userData);
                return response()->json($userData, 200);
            }

            else {
                return response()->json([], 204);
            }
        }

        catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen kullanıcı adı, email, parola veya tc kimlik numarası bilgisine sahip bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/users/EditUser",
     *      operationId="editUser",
     *      tags={"Users"},
     *      description="Edit an existing user data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       )
     * )
     */
    public function EditUser(UserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $user = User::find($validatedData['Id']);

            if($user){
                User::where('Id', $validatedData['Id'])
                    ->update([
                        'FirstName' => $validatedData['FirstName'],
                        'LastName' => $validatedData['LastName'],
                        'UserName' => $validatedData['UserName'],
                        'Email' => $validatedData['Email'],
                        'Password' => $validatedData['Password'],
                        'PasswordHash' => password_hash($validatedData['Password'], PASSWORD_DEFAULT),
                        'RoleId' => $validatedData['RoleId'],
                        'ImagePath' => $validatedData['ImagePath'],
                        'TCKN' => $validatedData['TCKN'],
                        'MotherName' => $validatedData['MotherName'],
                        'FatherName' => $validatedData['FatherName'],
                        'BirthDate' => $validatedData['BirthDate'],
                        'Gender' => $validatedData['Gender'],
                        'CivilStatus' => $validatedData['CivilStatus'],
                        'EmploymentDate' => $validatedData['EmploymentDate'],
                        'MilitaryStatus' => $validatedData['MilitaryStatus'],
                        'PostponementDate' => $validatedData['PostponementDate'],
                        'CountryId' => $validatedData['CountryId'],
                        'CityId' => $validatedData['CityId'],
                        'DistrictId' => $validatedData['DistrictId'],
                        'Address' => $validatedData['Address'],
                    ]);

                $userData = User::with(['role' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'country' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    );
                }, 'city' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CountryId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['country' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        );
                    }]);
                }, 'district' => function ($query) {
                    $query->select(
                        'Id',
                        'Name',
                        'CityId',
                        DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                    )->with(['city' => function ($subQuery) {
                        $subQuery->select(
                            'Id',
                            'Name',
                            'CountryId',
                            DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                        )->with(['country' => function ($innerQuery) {
                            $innerQuery->select(
                                'Id',
                                'Name',
                                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")
                            );
                        }]);
                    }]);
                },
                ])->where('Id', $user['Id'])->select([
                    'Id',
                    'FirstName',
                    'LastName',
                    'UserName',
                    'Email',
                    'Password',
                    'PasswordHash',
                    'ImagePath',
                    'TCKN',
                    'MotherName',
                    'FatherName',
                    'BirthDate',
                    'Gender',
                    'CivilStatus',
                    'EmploymentDate',
                    'MilitaryStatus',
                    'PostponementDate',
                    'Address',
                    'RoleId',
                    'CountryId',
                    'CityId',
                    'DistrictId',
                    DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"),
                ])->first();

                $userData = $this->dataFormatting($userData);
                return response()->json($userData, 200);
            }

            return response()->json(['message' => 'Kullanıcı kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen kullanıcı adı, email, parola veya tc kimlik numarası bilgisine sahip bir kayıt zaten var." ]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/users/DeleteUser/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      description="Delete an existing user data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteUser($id): JsonResponse
    {
        try {
            $deleted = User::where('Id', $id)->delete();

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

    /**
     * @OA\Get(
     *      path="/api/users/GetVapidKeys",
     *      operationId="getKeyUser",
     *      tags={"Users"},
     *      description="Public and private vapid key for notification",
     *      @OA\Response(
     *          response=200
     *       )
     * )
     */
    public function GetVapidKeys(): JsonResponse
    {
        try {
            $vapidKeys = VAPID::createVapidKeys();

            return response()->json([
                'publicKey' => $vapidKeys['publicKey'],
                'privateKey' => $vapidKeys['privateKey']
            ]);
        }

        catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating VAPID keys: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/users/SendNotification",
     *      operationId="sendNotification",
     *      tags={"Users"},
     *      description="Notification sending information",
     *      @OA\Response(
     *          response=200
     *       )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function SendNotification(Request $request): JsonResponse
    {
        try {
            $subscription = json_decode($request->input('Subscription'), true);
            $publicKey = $request->input('PublicKey');
            $privateKey = $request->input('PrivateKey');
            $isApprove = $request->input('IsApprove');

            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => 'mailto:erdemanacoglu90@gmail.com',
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ]
            ]);

            $content = json_encode([
                'title' => $isApprove ? 'Evrak Talebi Onayı' : 'Evrak Talebi Reddi',
                'body' => 'Talep edilen evrak ' . ($isApprove ? 'onaylandı' : 'reddedildi') . '. Çalışana bildirim maili gönderildi.',
            ]);

            $token = explode(' ', $request->header('Authorization'))[1];

            if ($token) {
                JWT::decode($token, new Key($this->secretKey, 'HS256'));
            }

            $subscription = Subscription::create($subscription);
            $webPush->sendOneNotification($subscription, $content);

            return response()->json([
                'code' => 200,
                'message' => 'Bildirim başarıyla gönderildi.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 400,
                'message' => 'Bildirim gönderimi sırasında bir hata oluştu. Hata: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/users/SendEmail",
     *      operationId="sendEmail",
     *      tags={"Users"},
     *      description="Email sending information",
     *      @OA\Response(
     *          response=200
     *       )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function SendEmail(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'DocumentName' => 'required|string',
                'CreateDate' => 'required|string',
                'ToUserFirstName' => 'required|string',
                'ToUserLastName' => 'required|string',
                'ToUserEmail' => 'required|email',
                'FromUserEmail' => 'required|email',
                'FromUserFirstName' => 'required|string',
                'FromUserLastName' => 'required|string',
                'MailType' => 'required|string|in:Approve,Reject',
            ]);

            Mail::send(new SendDocumentMail($data));

            return response()->json([
                'code' => 200,
                'message' => 'Mail gönderimi başarıyla yapıldı.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 400,
                'message' => 'Mail gönderimi sırasında bir hata oluştu. Hata: ' . $e->getMessage(),
            ], 400);
        }
    }

    private function dataFormatting($data)
    {
        $data = $data->toArray();

        $data['createdAt'] = Carbon::parse($data['createdAt'])->format('d.m.Y H:i:s');
        $data['updatedAt'] = Carbon::parse($data['updatedAt'])->format('d.m.Y H:i:s');
        $data['BirthDate'] = Carbon::parse($data['BirthDate'])->format('d.m.Y H:i:s');
        $data['EmploymentDate'] = Carbon::parse($data['EmploymentDate'])->format('d.m.Y H:i:s');

        if(!empty($data['PostponementDate'])) {
            $data['PostponementDate'] = Carbon::parse($data['PostponementDate'])->format('d.m.Y H:i:s');
        }

        $formatNestedData = function (&$relation) use (&$formatNestedData) {
            if ($relation) {
                if (isset($relation['createdAt'])) {
                    $relation['createdAt'] = Carbon::parse($relation['createdAt'])->format('d.m.Y H:i:s');
                }
                if (isset($relation['updatedAt'])) {
                    $relation['updatedAt'] = Carbon::parse($relation['updatedAt'])->format('d.m.Y H:i:s');
                }

                foreach ($relation as $key => &$value) {
                    if (is_array($value) && isset($value['createdAt'])) {
                        $formatNestedData($value);
                    }
                }
            }
        };

        $relations = ['role' => 'Role', 'country' => 'Country', 'city' => 'City', 'district' => 'District'];

        foreach ($relations as $key => $newKey) {
            if (isset($data[$key])) {
                $data[$newKey] = $data[$key];
                unset($data[$key]);
                $formatNestedData($data[$newKey]);
            }
        }

        if (isset($data['City']['country'])) {
            $data['City']['Country'] = $data['City']['country'];
            unset($data['City']['country']);
        }

        if (isset($data['District']['city'])) {
            $data['District']['City'] = $data['District']['city'];
            unset($data['District']['city']);

            if (isset($data['District']['City']['country'])) {
                $data['District']['City']['Country'] = $data['District']['City']['country'];
                unset($data['District']['City']['country']);
            }
        }

        return $data;
    }
}
