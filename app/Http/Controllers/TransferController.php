<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\RekeningAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TransaksiTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nilai_transfer' => 'required',
            'bank_tujuan' => 'required',
            'rekening_tujuan' => 'required',
            'atasnama_tujuan' => 'required',
            'bank_pengirim' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tanggal = Carbon::now('Asia/Jakarta')->format('ymd');
        $transaksiHariIni = TransaksiTransfer::whereDate('created_at', Carbon::today('Asia/Jakarta'))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($transaksiHariIni) {
            $idTransaksiTerakhir = substr($transaksiHariIni->id_transaksi, 5);
            $counter = intval($idTransaksiTerakhir) + 1;
        } else {
            $counter = 1;
        }

        $idTransaksi = 'TF' . $tanggal . str_pad($counter, 5, '0', STR_PAD_LEFT);

        $kodeUnik = sprintf('%03d', rand(0, 999));

        $bankTujuan = Bank::where('nama', $request->bank_tujuan)->first();

        $bankPengirim = Bank::where('nama', $request->bank_pengirim)->first();

        $rekeningAdmin = RekeningAdmin::where('id_bank', $bankPengirim->id)->first();
        // dd($rekeningAdmin);
        try {
            DB::connection('mysql')->beginTransaction();

            $eloquent = new TransaksiTransfer;
            $eloquent->id_transaksi = $idTransaksi;
            $eloquent->id_user = Auth::id();
            $eloquent->id_bank_tujuan = $bankTujuan->id;
            $eloquent->nomor_rekening_tujuan = $request->rekening_tujuan;
            $eloquent->atas_nama_rekening_tujuan = $request->atasnama_tujuan;
            $eloquent->id_rekening_admin = $rekeningAdmin->id;
            $eloquent->nilai_transfer = $request->nilai_transfer;
            $eloquent->kode_unik = $kodeUnik;
            $eloquent->biaya_admin = 0;
            $eloquent->total_transfer = $request->nilai_transfer + $kodeUnik;
            $eloquent->berlaku_hingga = Carbon::now('Asia/Jakarta')->addHours(24);
            $eloquent->save();
            DB::connection('mysql')->commit();

            return response()->json([
                'id_transaksi' => $eloquent->id_transaksi,
                'nilai_transfer' => $eloquent->nilai_transfer,
                'kode_unik' => $eloquent->kode_unik,
                'biaya_admin' => $eloquent->biaya_admin,
                'total_transfer' => $eloquent->total_transfer,
                'bank_perantara' => $request->bank_pengirim,
                'rekening_perantara' => $rekeningAdmin->nomor_rekening,
                'berlaku_hingga' => $eloquent->berlaku_hingga
            ]);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }
}
