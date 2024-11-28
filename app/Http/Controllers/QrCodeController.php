<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    // Menampilkan QR Code
    public function show()
    {
        // Cek apakah QR Code sudah ada di cache
        $cacheKey = 'attendance_qrcode';

        // Jika QR Code belum ada atau sudah expired, generate yang baru
        if (!Cache::has($cacheKey)) {
            // Generate angka acak yang unik untuk QR code
            $randomNumber = rand(100000, 999999); // Angka acak antara 100000 dan 999999

            // Data QR Code yang akan dipakai (hanya angka acak)
            $data = json_encode([
                'random_number' => $randomNumber, // Menyimpan angka acak
            ]);

            // Generate QR Code
            $qrCodeImage = QrCode::size(300)->generate($data);

            // Simpan angka acak di cache
            Cache::put($cacheKey, [
                'random_number' => $randomNumber, // Simpan angka acak di cache
            ], now()->addMinutes(3));  // Cache QR Code selama 3 menit
        } else {
            // Ambil data QR Code yang sudah ada dari cache
            $cachedData = Cache::get($cacheKey);
            $qrCodeImage = QrCode::size(300)->generate(json_encode([
                'random_number' => $cachedData['random_number'],
            ]));
        }

        // Menampilkan QR Code di halaman web
        return view('qrcode_show', ['qrCode' => $qrCodeImage]);
    }


    public function validateQrCode(Request $request)
    {
        $qrCodeData = $request->input('qr_code'); // Data QR code yang dikirimkan
        $user_id = $request->input('user_id'); // ID pengguna dari request

        // Ambil angka acak dari cache
        $cacheKey = 'attendance_qrcode';
        $cachedData = Cache::get($cacheKey);

        // Validasi QR Code berdasarkan angka acak
        if ($cachedData) {
            // Ambil angka acak dari QR code yang dikirimkan
            $decodedData = json_decode($qrCodeData, true);
            if ($decodedData && isset($decodedData['random_number'])) {
                $randomNumber = $decodedData['random_number'];

                // Cek apakah angka acak cocok
                if ($cachedData['random_number'] === $randomNumber) {
                    $now = Carbon::now('Asia/Jayapura'); // Waktu sekarang dalam zona waktu yang diinginkan
                    
                    // Cek apakah user sudah melakukan absensi untuk hari ini
                    $absensiHariIni = Absensi::where('user_id', $user_id)
                        ->where('tanggal', $now->format('Y-m-d'))
                        ->first();

                    // Logika untuk absensi masuk atau pulang
                    if ($now->hour >= 5 &&$now->hour <= 8) {
                        // Jika jam kurang dari 8, set jam masuk jika belum ada jam masuk
                        if (!$absensiHariIni) {
                            $absensi = new Absensi();
                            $absensi->user_id = $user_id;
                            $absensi->tanggal = $now->format('Y-m-d'); // Tanggal dalam format Y-m-d
                            $absensi->jam_masuk = $now->format('H:i:s'); // Jam masuk
                            $absensi->status = 'Hadir'; // Status kehadiran
                            $absensi->created_at = now();
                            $absensi->updated_at = now();
                            $absensi->save(); // Menyimpan data absensi ke database

                            return response()->json(['status' => 'valid', 'message' => 'Absensi masuk berhasil dicatat.'], 200);
                        } else {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda sudah absen masuk hari ini.'], 400);
                        }
                    }elseif ($now->hour > 8 && $now->hour < 17) {
                        // Jika terlambat
                        if ($absensiHariIni && empty($absensiHariIni->jam_masuk)) {
                            $absensi = new Absensi();
                            $absensi->user_id = $user_id;
                            $absensi->tanggal = $now->format('Y-m-d'); // Tanggal dalam format Y-m-d
                            $absensi->jam_masuk = $now->format('H:i:s'); // Jam masuk
                            $absensi->status = 'Terlambat'; // Status kehadiran
                            $absensi->created_at = now();
                            $absensi->updated_at = now();
                            $absensi->save(); // Menyimpan data absensi ke database

                            return response()->json(['status' => 'valid', 'message' => 'Absensi masuk berhasil dicatat tapi terlambat.'], 200);
                        } elseif ($absensiHariIni) {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda sudah absen masuk hari ini.'], 400);
                        } else {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda belum melakukan absensi masuk hari ini.'], 400);
                        }
                    } elseif ($now->hour >= 17 && $now->hour <= 19) {
                        // Jika jam sudah lewat 17, set jam keluar jika belum ada jam keluar
                        if ($absensiHariIni && empty($absensiHariIni->jam_keluar)) {
                            $absensiHariIni->jam_keluar = $now->format('H:i:s'); // Jam keluar
                            $absensiHariIni->updated_at = now();
                            $absensiHariIni->save(); // Update data absensi ke database

                            return response()->json(['status' => 'valid', 'message' => 'Absensi pulang berhasil dicatat.'], 200);
                        } elseif ($absensiHariIni) {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda sudah absen pulang hari ini.'], 400);
                        } else {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda belum melakukan absensi masuk hari ini.'], 400);
                        }
                    } else {
                         // Jika jam sudah lewat 17, set jam keluar jika belum ada jam keluar
                         if ($absensiHariIni && empty($absensiHariIni->jam_keluar)) {
                            $absensiHariIni->jam_keluar = $now->format('H:i:s'); // Jam keluar
                            $absensiHariIni->updated_at = now();
                            $absensiHariIni->save(); // Update data absensi ke database

                            return response()->json(['status' => 'valid', 'message' => 'Absensi pulang berhasil dicatat.'], 200);
                        } elseif ($absensiHariIni) {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda sudah absen pulang hari ini.'], 400);
                        } else {
                            return response()->json(['status' => 'invalid', 'message' => 'Anda belum melakukan absensi masuk hari ini.'], 400);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'invalid', 'message' => 'QR Code tidak valid'], 400);
    }
}

    
