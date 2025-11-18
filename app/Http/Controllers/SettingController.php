<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data setting dari DB
        $settings = DB::table('settings')->pluck('value', 'key');

        return view('settings.index', [
            'discount' => $settings['default_discount'] ?? 0,
            'tax' => $settings['default_tax'] ?? 0,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_discount' => 'required|numeric|min:0',
            'default_tax' => 'required|numeric|min:0',
        ]);

        // Simpan ke DB
        DB::table('settings')->updateOrInsert(
            ['key' => 'default_discount'],
            ['value' => $request->default_discount]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'default_tax'],
            ['value' => $request->default_tax]
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
