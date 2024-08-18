<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckShippingCostController extends Controller
{
    public function province(Request $request): JsonResponse
    {
        try {
            $provinces = Province::where('name', 'like', '%' . $request->keyword . '%')->select('id', 'name')->get();
            $data = [];
            foreach ($provinces as $province) {
                $data[] = [
                    'id'    => $province->id,
                    'text'  => $province->name
                ];
            }

            return response()->json([
                'message' => 'Get all data provinces',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data Fetch Failed',
                'data'    => []
            ]);
        }
    }

    public function city(Request $request): JsonResponse
    {
        try {
            $cities = Province::find($request->province_id)->cities()
                ->where('name', 'like', '%' . $request->keyword . '%')
                ->select('id', 'name')->get();

            $data = [];
            foreach ($cities as $city) {
                $data[] = [
                    'id'    => $city->id,
                    'text'  => $city->name
                ];
            }

            return response()->json([
                'message' => 'Get all data cities',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data Fetch Failed',
                'data'    => []
            ]);
        }
    }

    public function checkShippingCost(Request $request): JsonResponse
    {
        try {
            $admin = User::where('role', 'ADMIN')->firstOrFail(); // Mendapatkan admin pertama yang ditemukan

            $address = Address::where('main', true)
                ->where('user_id', $admin->id)
                ->firstOrFail(); // Mengambil address utama dari admin tersebut


            $response = Http::withOptions(['verify' => false,])->withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->post('https://api.rajaongkir.com/starter/cost', [
                'origin'        => $address->city_id,
                'destination'   => $request->destination,
                'weight'        => $request->weight,
                'courier'       => $request->courier
            ])
                ->json()['rajaongkir']['results'][0]['costs'];

            return response()->json([
                'message' => 'Get all data cost',
                'data' => $response
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data'    => []
            ]);
        }
    }
}
