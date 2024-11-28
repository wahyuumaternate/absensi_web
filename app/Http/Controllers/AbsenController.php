<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function index($user_id) {
        // Mengambil data absensi terbaru berdasarkan user_id
        $data = Absensi::where('user_id', $user_id)->latest()->get();
    
        // Mengembalikan respons dalam format JSON
        return response()->json($data, 200);
    }
}
