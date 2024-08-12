<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Tampilkan daftar alamat user.
     */
    public function index()
    {
        $addresses = Auth::user()->address()->with('province')->with('city')->get();
        // return response()->json($addresses);
        return response()->json([
            'message' => 'List Address',
            'data' => $addresses
        ]);
    }

    /**
     * Tampilkan detail alamat tertentu.
     */
    public function show(Address $address): JsonResponse
    {
        $address = Address::with('user');
        // $transactions = Transaction::with('listing')->whereUserId(auth()->id())->paginate();
        // $address = Auth::user()->address() > findOrFail($id);
        return response()->json([
            'message' => 'List Address',
            'data' => $address
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Validasi input
        $validatedData = $request->validate([
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:12',
            'address' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'other' => 'nullable|string|max:255',
            'main' => 'required|boolean',
            'location' => 'required|string|in:home,office',
        ]);

        // Tambahkan user_id dari pengguna yang sedang login
        $validatedData['user_id'] = auth()->id();

        // Jika nilai 'main' di request adalah true
        if ($request->input('main')) {
            // Set 'main' menjadi false untuk semua alamat lainnya milik user yang login
            Address::where('main', true)
                ->where('user_id', auth()->id()) // Hanya untuk user yang sedang login
                ->update(['main' => false]);
        }

        // Simpan data ke database
        $address = Address::create($validatedData);

        // Berikan respon sukses
        return response()->json([
            'message' => 'Address created successfully',
            'address' => $address
        ], 201);
    }



    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:12',
            'address' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'other' => 'nullable|string|max:255',
            'main' => 'required|boolean',
            'location' => 'required|string|in:home,office',
        ]);

        // Temukan address berdasarkan ID dan milik user yang sedang login
        $address = Address::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Jika nilai 'main' di request adalah true
        if ($request->input('main')) {
            // Set 'main' menjadi false untuk semua alamat lainnya milik user yang login
            Address::where('main', true)
                ->where('user_id', auth()->id()) // Hanya untuk user yang sedang login
                ->where('id', '!=', $id) // Kecuali untuk ID yang sedang diupdate
                ->update(['main' => false]);
        }

        // Update data dengan input yang divalidasi
        $address->update($validatedData);

        // Berikan respon sukses
        return response()->json([
            'message' => 'Address updated successfully',
            'address' => $address
        ], 200);
    }


    public function destroy($id)
    {
        // Temukan address berdasarkan ID
        $address = Address::findOrFail($id);

        // Hapus data address
        $address->delete();

        // Berikan respon sukses
        return response()->json([
            'message' => 'Address deleted successfully'
        ], 200);
    }
}
