<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * GET /api/staff - Ambil semua data staff
     */
    public function index()
    {
        try {
            $staff = Staff::with('user')->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data staff berhasil diambil',
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/staff - Buat staff baru
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'position' => 'nullable|string|max:50',
                'bio' => 'nullable|string',
                'photo' => 'nullable|string|max:255',
                'user_id' => 'required|integer|exists:user,id_user'
            ]);

            $staff = Staff::create([
                'name' => $request->name,
                'position' => $request->position,
                'bio' => $request->bio,
                'photo' => $request->photo,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Staff berhasil dibuat',
                'data' => $staff->load('user')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/staff/{id} - Ambil data staff berdasarkan ID
     */
    public function show($id)
    {
        try {
            $staff = Staff::with('user')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data staff berhasil diambil',
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Staff tidak ditemukan'
            ], 404);
        }
    }

    /**
     * PUT /api/staff/{id} - Update data staff
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'position' => 'nullable|string|max:50',
                'bio' => 'nullable|string',
                'photo' => 'nullable|string|max:255',
                'user_id' => 'required|integer|exists:user,id_user'
            ]);

            $staff = Staff::findOrFail($id);
            
            $staff->update([
                'name' => $request->name,
                'position' => $request->position,
                'bio' => $request->bio,
                'photo' => $request->photo,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Staff berhasil diupdate',
                'data' => $staff->load('user')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/staff/{id} - Hapus data staff
     */
    public function destroy($id)
    {
        try {
            $staff = Staff::findOrFail($id);
            
            // Hapus photo jika ada
            if ($staff->photo && Storage::disk('public')->exists($staff->photo)) {
                Storage::disk('public')->delete($staff->photo);
            }
            
            $staff->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Staff berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/staff/position/{position} - Ambil staff berdasarkan posisi
     */
    public function getByPosition($position)
    {
        try {
            $staff = Staff::with('user')
                ->where('position', 'LIKE', '%' . $position . '%')
                ->get();
            
            if ($staff->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada staff dengan posisi tersebut'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data staff berdasarkan posisi berhasil diambil',
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data staff: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/staff/search/{keyword} - Cari staff berdasarkan nama atau posisi
     */
    public function search($keyword)
    {
        try {
            $staff = Staff::with('user')
                ->where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('position', 'LIKE', '%' . $keyword . '%')
                ->orWhere('bio', 'LIKE', '%' . $keyword . '%')
                ->get();
            
            if ($staff->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada staff yang ditemukan'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pencarian staff berhasil',
                'data' => $staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari staff: ' . $e->getMessage()
            ], 500);
        }
    }
}
