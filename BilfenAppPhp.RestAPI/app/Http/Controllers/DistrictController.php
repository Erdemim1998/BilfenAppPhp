<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictRequest;
use App\Http\Requests\DocumentRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *       version="1.0.0",
 *       title="Bilfen API",
 *  )
 * @OA\PathItem(path="/api-docs")
 * @OA\Tag(name="Districts", description="District management endpoints")
 */

/**
 * @OA\Schema(
 *     schema="District",
 *     type="object",
 *     description="District model",
 *     required={"Id", "Name", "CityId", "createdAt", "updatedAt"},
 *     @OA\Property(property="Id", type="string", description="The id of the district", example="TR"),
 *     @OA\Property(property="Name", type="string", description="The district's name", example="string"),
 *     @OA\Property(property="CityId", type="string", description="The district's city id", example="string"),
 *     @OA\Property(property="createdAt", type="string", description="The district's created date", example="string"),
 *     @OA\Property(property="updatedAt", type="string", description="The district's updated date", example="string"),
 * )
 */
class DistrictController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/districts/GetAllDistricts",
     *      operationId="allDistricts",
     *      tags={"Districts"},
     *      description="Retrives a list of districts",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/District")
     *       )
     * )
     */
    public function GetAllDistricts(): JsonResponse
    {
        try{
            $districts = District::with(['city' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                             DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId');
            }, 'city.country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                       DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CityId'])->get();

            $districts = $districts->map(function ($district) {
                return $this->dataFormatting($district);
            });

            return response()->json($districts, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/districts/GetAllDistrictsByCityId/{cityId}",
     *      operationId="allDistrictsByCityId",
     *      tags={"Districts"},
     *      description="Retrives a list of districts by city id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/District")
     *       )
     * )
     */
    public function GetAllDistrictsByCityId($cityId): JsonResponse
    {
        try{
            $districts = District::with(['city' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId');
            }, 'city.country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('CityId', '=', $cityId)->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CityId'])->get();

            $districts = $districts->map(function ($district) {
                return $this->dataFormatting($district);
            });

            return response()->json($districts, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/districts/GetDistrict/{id}",
     *      operationId="districtById",
     *      tags={"Districts"},
     *      description="Retrives a district by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/District")
     *       )
     * )
     */
    public function GetDistrict($id): JsonResponse
    {
        try {
            $district = District::with(['city' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId');
            }, 'city.country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('Id', $id)->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CityId'])->first();

            if ($district) {
                $district = $this->dataFormatting($district);
                return response()->json($district, 200);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/districts/CreateDistrict",
     *      operationId="createDistrict",
     *      tags={"Districts"},
     *      description="Create a new district data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/District")
     *       )
     * )
     */
    public function CreateDistrict(DistrictRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $addedDistrict = District::create([
                'Id' => $validatedData['Id'],
                'Name' => $validatedData['Name'],
                'CityId' => $validatedData['CityId']
            ]);

            $districtWithCityAndCountry = District::with(['city' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId');
            }, 'city.country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('Id', $addedDistrict->Id)
                ->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CityId'])->first();

            if ($districtWithCityAndCountry) {
                $districtWithCityAndCountry = $this->dataFormatting($districtWithCityAndCountry);
                return response()->json($districtWithCityAndCountry, 200);
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen ilçe bilgisine ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/districts/EditDistrict",
     *      operationId="editDistrict",
     *      tags={"Districts"},
     *      description="Edit an existing district data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/District")
     *       )
     * )
     */
    public function EditDistrict(DistrictRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $district = District::find($validatedData['Id']);

            if($district) {
                District::where('Id', $validatedData['Id'])
                    ->update([
                        'Name' => $validatedData['Name'],
                        'CityId' => $validatedData['CityId']
                    ]);

                $districtWithCityAndCountry = District::with(['city' => function ($query) {
                    $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId');
                }, 'city.country' => function ($query) {
                    $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                        DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
                }])->where('Id', $district['Id'])
                    ->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                            DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CityId'])->first();

                $districtWithCityAndCountry = $this->dataFormatting($districtWithCityAndCountry);

                return response()->json($districtWithCityAndCountry, 200);
            }

            return response()->json(['message' => 'İlçe kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen ilçe bilgisine ait bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/districts/DeleteDistrict/{id}",
     *      operationId="deleteDistrict",
     *      tags={"Districts"},
     *      description="Delete an existing district data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteDistrict($id): JsonResponse
    {
        try {
            $deleted = District::where('Id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Silme işlemi başarıyla yapıldı!'
                ]);
            } else {
                return response()->json([
                    'code' => 204,
                    'message' => 'İlçe kaydı bulunamadı!'
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

        $data['City'] = $data['city'];
        unset($data['city']);
        $data['City']['createdAt'] = Carbon::parse($data['City']['createdAt'])->format('d.m.Y H:i:s');
        $data['City']['updatedAt'] = Carbon::parse($data['City']['updatedAt'])->format('d.m.Y H:i:s');

        $data['City']['Country'] = $data['City']['country'];
        unset($data['City']['country']);
        $data['City']['Country']['createdAt'] = Carbon::parse($data['City']['Country']['createdAt'])->format('d.m.Y H:i:s');
        $data['City']['Country']['updatedAt'] = Carbon::parse($data['City']['Country']['updatedAt'])->format('d.m.Y H:i:s');
        return $data;
    }
}
