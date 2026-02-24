<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;

class MejaController extends Controller
{
    public function index()
    {
        // Ambil hanya meja yang statusnya 'tersedia'
        $mejas = Meja::where('status', 'tersedia')
                    ->orderBy('no_meja', 'asc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $mejas
        ]);
    }
}