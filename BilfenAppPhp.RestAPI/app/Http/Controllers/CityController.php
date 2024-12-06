<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\UserRequest;
use App\Models\City;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *       version="1.0.0",
 *       title="Bilfen API",
 *  )
 * @OA\PathItem(path="/api-docs")
 * @OA\Tag(name="Cities", description="City management endpoints")
 */

/**
 * @OA\Schema(
 *     schema="City",
 *     type="object",
 *     description="City model",
 *     required={"Id", "Name", "CountryId", "createdAt", "updatedAt"},
 *     @OA\Property(property="Id", type="string", description="The id of the city", example="TR"),
 *     @OA\Property(property="Name", type="string", description="The city's name", example="string"),
 *     @OA\Property(property="CountryId", type="string", description="The city's country id", example="string"),
 *     @OA\Property(property="createdAt", type="string", description="The city's created date", example="string"),
 *     @OA\Property(property="updatedAt", type="string", description="The city's updated date", example="string"),
 * )
 */
class CityController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/cities/GetAllCities",
     *      operationId="allCities",
     *      tags={"Cities"},
     *      description="Retrives a list of cities",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/City")
     *       )
     * )
     */
    public function GetAllCities(): JsonResponse
    {
        try{
            $cities = City::with(['country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                       DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId'])->get();

            $cities = $cities->map(function ($city) {
                return $this->dataFormatting($city);
            });

            return response()->json($cities, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/cities/GetAllCitiesByCountryId/{countryId}",
     *      operationId="allCitiesByCountryId",
     *      tags={"Cities"},
     *      description="Retrives a list of cities by country id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/City")
     *       )
     * )
     */
    public function GetAllCitiesByCountryId($countryId): JsonResponse
    {
        try{
            $cities = City::with(['country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('CountryId', '=', $countryId)->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId'])->get();

            $cities = $cities->map(function ($city) {
                return $this->dataFormatting($city);
            });

            return response()->json($cities, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/cities/GetCity/{id}",
     *      operationId="getCity",
     *      tags={"Cities"},
     *      description="Retrives a city by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/City")
     *       )
     * )
     */
    public function GetCity($id): JsonResponse
    {
        try{
            $city = City::with(['country' => function ($query) {
                $query->select('Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"));
            }])->where('Id', $id)->select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                                 DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt"), 'CountryId'])->first();

            if($city) {
                $city = $this->dataFormatting($city);
                return response()->json($city, 200);
            }

            return response()->json(null, 204);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/cities/CreateCity",
     *      operationId="createCity",
     *      tags={"Cities"},
     *      description="Create a new city data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/City")
     *       )
     * )
     */
    public function CreateCity(CityRequest $request): JsonResponse
    {
        try{
            $validatedData = $request->validated();

            $addedCity = [
                'Id' => $validatedData['Id'],
                'Name' => $validatedData['Name'],
                'CountryId' => $validatedData['CountryId']
            ];

            $city = City::create($addedCity);
            $cityWithCountry = City::with('country')->where('cities.Id', '=', $city->Id)->first();

            if ($cityWithCountry) {
                $cityWithCountry = $this->dataFormatting($cityWithCountry);
                return response()->json($cityWithCountry, 200);
            }

            else {
                return response()->json([], 204);
            }
        }

        catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen il bilgisine sahip bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/cities/EditCity",
     *      operationId="editCity",
     *      tags={"Cities"},
     *      description="Edit an existing city data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/City")
     *       )
     * )
     */
    public function EditCity(CityRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $city = City::find($validatedData['Id']);

            if($city){
                City::where('Id', $validatedData['Id'])
                    ->update([
                        'Id' => $validatedData['Id'],
                        'Name' => $validatedData['Name'],
                        'CountryId' => $validatedData['CountryId']
                    ]);

                $cityData = City::with('country')->where('cities.Id', '=', $city->Id)->first();
                $cityData = $this->dataFormatting($cityData);
                return response()->json($cityData, 200);
            }

            return response()->json(['message' => 'İl kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen il bilgisine sahip bir kayıt zaten var." ]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/cities/DeleteCity/{id}",
     *      operationId="DeleteCity",
     *      tags={"Cities"},
     *      description="Delete an existing city data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteCity($id): JsonResponse
    {
        try {
            $deleted = City::where('Id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Silme işlemi başarıyla yapıldı!'
                ]);
            } else {
                return response()->json([
                    'code' => 204,
                    'message' => 'İl kaydı bulunamadı!'
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
        $data['Country'] = $data['country'];
        unset($data['country']);
        $data['Country']['createdAt'] = Carbon::parse($data['Country']['createdAt'])->format('d.m.Y H:i:s');
        $data['Country']['updatedAt'] = Carbon::parse($data['Country']['updatedAt'])->format('d.m.Y H:i:s');
        return $data;
    }
}
