<?php

namespace App\Http\Controllers;

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

    // Validasi QR Code berdasarkan angka acak
    public function validateQrCode(Request $request)
    {
        $qrCodeData = $request->input('qr_code'); // Data QR code yang dikirimkan
// dd($qrCodeData);
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
                    // dd($cachedData['random_number']);
                    return response()->json(['status' => 'valid', 'message' => 'QR Code valid'], 200);
                }
            }
        }

        return response()->json(['status' => 'invalid', 'message' => 'QR Code tidak valid'], 400);
    }
}
