<?php

namespace App;

use App\BarangMasuk;
use App\StokBarang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class BarangMasukImport implements ToModel, WithStartRow
{
    public $duplicates = [];
    public $accumulated = []; // Define the accumulated property

    private $jenisbarangMapping = [
        'Aset' => 1,
        'Barang Habis Pakai' => 2,
        'Barang Pinjaman' => 11,
        'Lainnya' => 22,
    ];

    public function model(array $row)
    {
        $existingRecord = BarangMasuk::where('serial_number', $row[1])->first();
        

        if ($existingRecord) {
            $this->duplicates[] = $row[1];  // Track duplicates
            return null;  // Skip this row
        }
        $short = substr($row[0], 0, 3);  // Using column A (Nama Barang)

    
        $stokBarang = StokBarang::where('nama_barang', $row[0])->first();  // Using column A (Nama Barang)

        if (!$stokBarang) {
            // If StokBarang doesn't exist, create it
            $stokBarang = StokBarang::create([
                'nama_barang' => $row[0],        // Column A (Nama Barang)
                'jumlah_sekarang' => 1,          // Initialize jumlah_sekarang to 1 (or other logic if needed)
                'jumlah_awal' => 1,              // Initialize jumlah_awal to 1 (or other logic if needed)
                'satuan' => $row[2],             // Column B (Satuan)
                'lokasi' => $row[3],             // Column C (Lokasi)
                'aktif' => 1,              // Column E (Aktif)
                'asal_brg' => $row[4],           // Column D (Asal Barang)
                'kib' => $row[5],                // Column E (KIB)
                'last' => Carbon::now()->format('Y-m-d H:i:s'),
                'stokupdate' => Carbon::now()->format('Y-m-d'),
                'tahun_anggaran' => $row[6],     // Column F (Tahun Anggaran)
                'jenisbarang' => $this->jenisbarangMapping[$row[7]] ?? null, // Column H (Jenis Barang) and mapping
                'id_qrs' => $short . $row[6] . Auth::id() . '0', // Construct id_qrs based on available data
            ]);
            $stat_barangmasuk = 1; 
        } else {
            $stokBarang->increment('jumlah_sekarang', 1);  
            if ($stokBarang->jumlah_sekarang == 1) {
                $stokBarang->jumlah_awal = 1;  
            } 
            else {
                $stokBarang->increment('jumlah_awal', 1);  
            }

            $stokBarang->save(); 
            $stat_barangmasuk = 2;  
        }

        // $serialNumber = $row[1];  // Column B (Serial Number)
        // if (empty($serialNumber)) {
        //     $short = substr($row[0], 0, 3);
        //     $random_number = rand(1, 99999);
        //     $serialNumber = Str::slug($short) . '-' . $random_number;
        // }
        
        // if (BarangMasuk::where('serial_number', $serialNumber)->exists()) {
        //     $this->duplicates[] = $serialNumber; 
        //     return null;
        // }

        return new BarangMasuk([
            'id_barang' => $stokBarang->id,
            'jumlah_masuk' => 1,
            'id_qr' => Str::upper(Str::random(5)),
            'serial_number' => $row[1],  // Use the generated or provided serial number
            'satuan' => $row[2],
            'stat_barangmasuk' => $stat_barangmasuk,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d'),
            'aktif' => 1,
            'last' => Carbon::now()->format('Y-m-d H:i:s'),
            'id_user' => Auth::id(),
        ]);
    }


    public function startRow(): int
    {
        return 2;  // Skip the first row (header row)
    }
}
