<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Http\Requests\UserRequest;
use App\Models\Country;
use App\Models\User;
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
 * @OA\Tag(name="Countries", description="Country management endpoints")
 */

/**
 * @OA\Schema(
 *     schema="Country",
 *     type="object",
 *     description="Country model",
 *     required={"Name", "CountryId", "createdAt", "updatedAt"},
 *     @OA\Property(property="Id", type="string", description="The id of the country", example="TR"),
 *     @OA\Property(property="Name", type="string", description="The country's name", example="string"),
 *     @OA\Property(property="createdAt", type="string", description="The country's created date", example="string"),
 *     @OA\Property(property="updatedAt", type="string", description="The country's updated date", example="string"),
 * )
 */
class CountryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/countries/GetAllCountries",
     *      operationId="allCountries",
     *      tags={"Countries"},
     *      description="Retrives a list of countries",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Country")
     *       )
     * )
     */
    public function GetAllCountries(): JsonResponse
    {
        try{
            $countries = Country::select(['Id', 'Name', DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                    DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")])->get();

            $countries = $countries->map(function ($country) {
                return $this->dataFormatting($country);
            });

            return response()->json($countries, 200);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/countries/GetCountry/{id}",
     *      operationId="getCountry",
     *      tags={"Countries"},
     *      description="Retrives a country by id",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Country")
     *       )
     * )
     */
    public function GetCountry($id): JsonResponse
    {
        try{
            $country = Country::where('Id', $id)->select(['Id', 'Name',
                                                DB::raw("DATE_FORMAT(createdAt, '%d.%m.%Y %H:%i:%s') as createdAt"),
                                                DB::raw("DATE_FORMAT(updatedAt, '%d.%m.%Y %H:%i:%s') as updatedAt")])->first();

            if($country) {
                $country = $this->dataFormatting($country);
                return response()->json($country, 200);
            }

            return response()->json(null, 204);
        }

        catch (\Exception $e) {
            return response()->json(['message' => 'Hata: ' . $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/countries/CreateCountry",
     *      operationId="createCountry",
     *      tags={"Countries"},
     *      description="Create a new country data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Country")
     *       )
     * )
     */
    public function CreateCountry(CountryRequest $request): JsonResponse
    {
        try{
            $validatedData = $request->validated();

            $addedCountry = [
                'Id' => $validatedData['Id'],
                'Name' => $validatedData['Name']
            ];

            $country = Country::create($addedCountry);

            if ($country) {
                $country = $this->dataFormatting($country);
                return response()->json($country, 200);
            }

            else {
                return response()->json([], 204);
            }
        }

        catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen ülke bilgisine sahip bir kayıt zaten var." ], 200);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/countries/EditCountry",
     *      operationId="editCountry",
     *      tags={"Countries"},
     *      description="Edit an existing country data",
     *      @OA\Response(
     *          response=200,
     *          @OA\JsonContent(ref="#/components/schemas/Country")
     *       )
     * )
     */
    public function EditCountry(CountryRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $country = Country::find($validatedData['Id']);

            if($country){
                Country::where('Id', $validatedData['Id'])
                    ->update([
                        'Name' => $validatedData['Name']
                    ]);

                $countryData = Country::where('Id', $country->Id)->get()->first();
                $countryData = $this->dataFormatting($countryData);
                return response()->json($countryData, 200);
            }

            return response()->json(['message' => 'Ülke kaydı bulunamadı!'], 204);
        } catch (\Exception $e) {
            return response()->json([ 'code' => 400, 'message' => "Girilen ülke bilgisine sahip bir kayıt zaten var." ]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/countries/DeleteCountry/{id}",
     *      operationId="deleteCountry",
     *      tags={"Countries"},
     *      description="Delete an existing country data",
     *      @OA\Response(
     *          response=204
     *       )
     * )
     */
    public function DeleteCountry($id): JsonResponse
    {
        try {
            $deleted = Country::where('Id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Silme işlemi başarıyla yapıldı!'
                ]);
            } else {
                return response()->json([
                    'code' => 204,
                    'message' => 'Ülke kaydı bulunamadı!'
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
