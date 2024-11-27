<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    // Mendapatkan semua data absensi
    public function index()
    {
        $absensi = Absensi::with('user')->get();
        return response()->json($absensi, 200);
    }

    // Mencatat absensi (kehadiran)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Mendapatkan tanggal dan waktu saat ini
        $tanggal = Carbon::now()->toDateString();
        $jamMasuk = Carbon::now()->toTimeString();

        // Cek apakah user sudah absen hari ini
        $absensiHariIni = Absensi::where('user_id', $request->user_id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($absensiHariIni) {
            return response()->json([
                'message' => 'User sudah absen hari ini.',
            ], 400);
        }

        // Simpan data absensi
        $absensi = Absensi::create([
            'user_id' => $request->user_id,
            'tanggal' => $tanggal,
            'jam_masuk' => $jamMasuk,
            'status' => 'hadir', // Bisa diatur logika statusnya
        ]);

        return response()->json([
            'message' => 'Absensi berhasil dicatat.',
            'data' => $absensi,
        ], 201);
    }

    // Mendapatkan detail absensi berdasarkan ID
    public function show($id)
    {
        $absensi = Absensi::with('user')->find($id);

        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }

        return response()->json($absensi, 200);
    }

    // Menandai waktu keluar
    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);

        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }

        $absensi->update([
            'jam_keluar' => Carbon::now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Jam keluar berhasil dicatat.',
            'data' => $absensi,
        ], 200);
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $absensi = Absensi::find($id);

        if (!$absensi) {
            return response()->json(['message' => 'Absensi tidak ditemukan'], 404);
        }

        $absensi->delete();

        return response()->json(['message' => 'Absensi berhasil dihapus'], 200);
    }
}
