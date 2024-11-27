<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function index() {
        // Mengambil data absensi terbaru
        $data = Absensi::latest()->get();
    
        // Mengembalikan respons dalam format JSON
        return response()->json($data, 200);
    }
}
