<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PesananDetil;
use Carbon\Carbon;
use App\Produk;
use App\KategoriProduk;
use App\RiwayatStokProduk;
use App\Pengeluaran;
use App\KategoriPengeluaran;
use DB;

class LabaRugiController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:laporan.labarugi', ['except' => []]);
  }

    public function index(Request $req){
      $judul = "Laporan Laba Rugi";
      $breadcrumbs = [
        ['link' => '#', 'name' => "Laporan"],
        ['link' => '#', 'name' => "Laba Rugi"],
      ];

      $start_date = Carbon::now()->startOfMonth();
      $end_date = $start_date->copy()->endOfMonth();

      if ($req->has('range')){
        $range = $req->range;
        $tanggal = explode(" - ", $range);

        try
        {
           $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
           $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
        }
        catch (\Throwable $t)
        {

            $start_date = Carbon::now()->startOfMonth();
            $end_date = $start_date->copy()->endOfMonth();

        }
      }



      $sum_pesanan = PesananDetil::
                      select(DB::raw('sum(pesanan_detil.kuantitas*pesanan_detil.harga_pelanggan) as total_penjualan,
                      pesanan_detil.produk_id'))
                      ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
                      ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
                      ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
                      ->groupBy('pesanan_detil.produk_id');
      $sum_hpp = RiwayatStokProduk::
                      select(DB::raw('sum(riwayat_stok_produk.kuantitas*riwayat_stok_produk.harga_pokok) as total_hpp,
                      riwayat_stok_produk.produk_id'))
                      ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
                      ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
                      ->join('pesanan', function ($join) {
                        $join->on('pesanan.id', '=', 'riwayat_stok_produk.sumber_id')
                              ->where('riwayat_stok_produk.sumber_type','=','App\Pesanan');
                       })
                      ->groupBy('riwayat_stok_produk.produk_id');
      $penjualan_per_kat= Produk::
                select(DB::raw("sum(sum_pesanan.total_penjualan) as total_penjualan"),
                DB::raw("sum(sum_hpp.total_hpp) as total_hpp"),
                "produk.kategori_id")
                ->joinSub($sum_pesanan, 'sum_pesanan', function ($join) {
                    $join->on('produk.id', '=', 'sum_pesanan.produk_id');
                })
                ->joinSub($sum_hpp, 'sum_hpp', function ($join) {
                  $join->on('produk.id', '=', 'sum_hpp.produk_id');
                })
                ->groupBy('produk.kategori_id');


      $daftar_penjualan= KategoriProduk::
                joinSub($penjualan_per_kat, 'penjualan_per_kategori', function ($join) {
                    $join->on('kategori_produk.id', '=', 'penjualan_per_kategori.kategori_id');
                })->get();

      $daftar_pengeluaran = Pengeluaran::
            select('kategori_pengeluaran_id',DB::raw('sum(nominal) as total_biaya') )
            ->whereDate('tanggal','>=',$start_date->format('Y-m-d'))
            ->whereDate('tanggal','<=',$end_date->format('Y-m-d'))
            ->groupBy('kategori_pengeluaran_id');

      $daftar_kategori_pengeluaran= KategoriPengeluaran::
            leftJoinSub($daftar_pengeluaran, 'pengeluaran', function ($join) {
                $join->on('kategori_pengeluaran.id', '=', 'pengeluaran.kategori_pengeluaran_id');
            })->get();

      return view(
                  'mimin.laporan.laba_rugi.index',
                  compact('judul', 'breadcrumbs', 'daftar_penjualan','start_date','end_date','daftar_kategori_pengeluaran')
                );

    }
}
