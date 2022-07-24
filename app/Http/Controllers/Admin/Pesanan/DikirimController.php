<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
use Validator;
use Auth, Session, DB;
use Carbon\Carbon;
use App\MetodePembayaran;
use App\TransaksiKas;
use App\User;
use App\Dompet;
use App\Point;
use App\Pengeluaran;

class DikirimController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:pesanan.dikirim.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:pesanan.dikirim.batalkan', ['only' => ['destroy']]);
       $this->middleware('permission:pesanan.dikirim.selesaikan', ['only' => ['edit','update']]);
  }

  public function index()
  {
    $judul = "Pesanan Dikirim";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dikirim"],
    ];

      $daftar_pesanan = Pesanan::dikirim()->with('pelanggan', 'metode_pembayaran');
      $user = Auth::user();
      if($user->hasRole('Kurir')){
      $daftar_pesanan = $daftar_pesanan->where('diantar_oleh_id',$user->id);
      }

      $daftar_pesanan = $daftar_pesanan->orderBy('created_at', 'desc')
      ->simplePaginate(10);





    return view(
      'mimin.pesanan.dikirim.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }


  public function show($id)
  {

    // return $id;
    $judul = "Pesanan Dikirim";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dikirim"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::dikirim()->findOrFail($id);



    return view(
      'mimin.pesanan.dikirim.show',
      compact(
        'judul',
        'breadcrumbs',
        'judul_deskripsi',
        'deskripsi',
        'pesanan'
      )
    );
  }


  public function destroy(Request $req, $id)
  {

    try {
      $pesanan = Pesanan::dikirim()->findOrFail($id);
      $now = Carbon::now();
      $nama = 'Pesanan Dikirim tanggal ' . $pesanan->created_at->format('d-m-Y');

      if ($req->has('aksi') &&  $req->aksi == "batal") {

        $keterangan = 'Membatalkan ';
        $pesanan->status = "dibatalkan";
        $pesanan->dibatalkan_oleh_id = Auth::id();
        $pesanan->dibatalkan_pada = $now;
        $pesanan->save();

        return response()->json([
          'judul' => $keterangan . 'sukses!',
          'pesan' => $keterangan . ' ' . $nama . ' Sukses',
          'success' => true,
          'redirect' => route('mimin.pesanan.dikirim.index')
        ]);
      } else {
        return response()->json([
          'judul' => $nama . ' Gagal',
          'pesan' => 'Gagal',
          'success' => false,
          'redirect' => route('mimin.pesanan.dikirim.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal',
        'pesan' => 'Terjadi kesalahan',
        'success' => false,
        'redirect' => route('mimin.pesanan.dikirim.index')
      ]);
    }
  }



  public function edit($id)
  {

    // return $id;
    $judul = "Pesanan Dikirim";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dikirim"],
      ['link' => '#', 'name' => "Konfirmasi"],
    ];

    $pesanan = Pesanan::dikirim()->findOrFail($id);

    $daftar_kurir = User::notRole(['Pelanggan'])->pluck('nama', 'id');
    $daftar_metode_pembayaran = MetodePembayaran::where('status', 'Aktif')->pluck('nama', 'id');
    return view(
      'mimin.pesanan.dikirim.konfirmasi',
      compact(
        'judul',
        'breadcrumbs',
        'judul_deskripsi',
        'daftar_metode_pembayaran',
        'daftar_kurir',
        'deskripsi',
        'pesanan'
      )
    );
  }

  public function update(Request $req, $id)
  {


    $rules = [
      'metode_pembayaran_id' => 'required',
      'tanggal_pembayaran' => 'required',
      'nominal_yang_dibayar' => 'required|min:0',
    ];
    $messages = [
      'metode_pembayaran_id.required' => 'Metode Pembayaran harus diisi',
      'tanggal_pembayaran.required' => 'Tanggal pembayaran harus diisi',
      'nominal_yang_dibayar.required' => 'Ongkos Kirim wajib diisi',
    ];

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    DB::transaction(function () use ($req, $id) {

      $pesanan = Pesanan::dikirim()->findOrFail($id);
      $pesanan->metode_pembayaran_id = $req->metode_pembayaran_id;
      $pesanan->nominal_yang_dibayar = $req->nominal_yang_dibayar;
      $pesanan->status = "selesai";
      $pesanan->diselesaikan_pada = Carbon::now();
      $pesanan->status_pembayaran = "sudah_bayar";
      $pesanan->tanggal_pembayaran = Carbon::createFromFormat("d-m-Y",$req->tanggal_pembayaran);
      $pesanan->save();



      // masukkan ke dompet pelanggan...
      // jika kelebihan tagihan, uang masuk ke dompet pelanggan
      // jika kekurangan pembyarannya, uang akan ditagihkan di dompet pelanggan dicatat sbg hutang..
      // jika pelanggan adalah reseller, maka tidak ada fee.. didapat dri seleisih tagihan..
      // jika pelanggan adalah pelanggan langusng, maka ada fee..

      // masukkan ke dompet-dompet

      $pelanggan = $pesanan->pelanggan;
      $pelanggan_1 = new User;
      $pelanggan_2 = new User;
      $pelanggan_3 = new User;
      $pelanggan_4 = new User;

      $fee_pelanggan_1 = 0;
      $fee_pelanggan_2 = 0;
      $fee_pelanggan_3 = 0;
      $fee_pelanggan_4 = 0;

      $pendapatan_lain_0      = 0;  // => biaya tambahan 0 adalah pendapatan tambahan dari sisi refill madu
      $biaya_diskon_0         = 0;  // => diskon 0 adalah biaya biaya diskon refill madu

      $pendapatan_ongkos_kirim = $pesanan->ongkos_kirim;    // => pendapatan ongkos kirim refill madu
      $pendapatan_ongkos_kurir = $pesanan->ongkos_kurir;    // => ongkos antar refill madu

      $margin_pelanggan_1 = 0;
      $margin_pelanggan_2 = 0;
      $margin_pelanggan_3 = 0;
      $margin_pelanggan_4 = 0;

      $level = $pesanan->pelanggan->level;

      switch ($level) {
        case 1 :
          $pelanggan_1 = $pesanan->pelanggan;
          $pendapatan_lain_0 = $pesanan->biaya_tambahan;
          $biaya_diskon_0 = $pesanan->diskon;
          break;

        case 2 :
          $pelanggan_1 = $pesanan->pelanggan->parent;
          $pelanggan_2 = $pesanan->pelanggan;
          $nominal_pelanggan_1 = $pesanan->nominal_pembelian_pelanggan($pelanggan_1->id);
          $fee_pelanggan_1 = $pesanan->nominal_pembelian - $nominal_pelanggan_1;
          $margin_pelanggan_1 = $fee_pelanggan_1 + $pesanan->biaya_tambahan - $pesanan->diskon;
          break;

        case 3 :
          $pelanggan_1 = $pesanan->pelanggan->parent->parent;
          $pelanggan_2 = $pesanan->pelanggan->parent;
          $pelanggan_3 = $pesanan->pelanggan;
          $nominal_pelanggan_1 = $pesanan->nominal_pembelian_pelanggan($pelanggan_1->id);
          $fee_pelanggan_1 = $pesanan->nominal_pembelian - $nominal_pelanggan_1;
          $margin_pelanggan_1 = $fee_pelanggan_1 + $pesanan->biaya_tambahan - $pesanan->diskon;
          break;

        case 4 :
          $pelanggan_1 = $pesanan->pelanggan->parent->parent->parent;
          $pelanggan_2 = $pesanan->pelanggan->parent->parent;
          $pelanggan_3 = $pesanan->pelanggan->parent;
          $pelanggan_4 = $pesanan->pelanggan;
          $nominal_pelanggan_1 = $pesanan->nominal_pembelian_pelanggan($pelanggan_1->id);
          $fee_pelanggan_1 = $pesanan->nominal_pembelian - $nominal_pelanggan_1;
          $margin_pelanggan_1 = $fee_pelanggan_1 + $pesanan->biaya_tambahan - $pesanan->diskon;
          break;

      }

      $tanggal = Carbon::createFromFormat('d-m-Y', $req->tanggal_pembayaran);
      // kalau metode pembayaran ada kas nya

      $metode_pembayaran = $pesanan->metode_pembayaran;


      /** -----------------------------
       *  TINDAKAN UNTUK REFILL MADU
       *  -----------------------------
       *  Step 1 : Jika Metode Pembayaran ada Kas nya, maka masukkan ke kas jumlah yang dibayar,
       *           kalau yang dibayar kurang, maka kurangi isi dompet pelanggan 1,
       *           kalau yang dibayar berlebih, masukkan ke dompet pelanggan 1 (sebagai deposit).
       *  Step 2 : Jika Metode Pembayaran tidak ada kasnya, maka kurangi dompet pelanggan 1,
       *
       *
      */

      $keterangan_pesanan = " atas pesanan " . $pelanggan->nama . " pada " . $pesanan->created_at->format('d-m-Y') . " dengan No Pesanan: " . $pesanan->no_invoice;

//      if ($metode_pembayaran->kas->id != null) { // step 1 => jika metode pembayaran ada kas nya...

                  $tagihan = $pesanan->grand_total;
                  $selisih = $pesanan->nominal_yang_dibayar - $tagihan;

                  $saldo_akhir_pelanggan_1 = $pelanggan_1->saldo_tanggal($tanggal);
                  $saldo_berjalan_pelanggan_1 = $saldo_akhir_pelanggan_1 + $margin_pelanggan_1;

                  $keterangan_fee_pelanggan_1 = " atas pesanan " . $pelanggan->nama . " pada " . $pesanan->created_at->format('d-m-Y') . " dengan No Pesanan: " . $pesanan->no_invoice;

                  // kasih fee
                  if ($margin_pelanggan_1 != 0) {

                            $jumlah = 0;
                            if ($margin_pelanggan_1 > 0) {
                              $dk = 'd';
                              $debet_kredit = 'debet';
                              $jumlah = $margin_pelanggan_1;
                              $keterangan_fee_pelanggan_1 =   "Profit Margin ".$keterangan_fee_pelanggan_1;
                            } else {
                              $dk = 'k';
                              $debet_kredit = 'kredit';
                              $jumlah = $margin_pelanggan_1 * (-1);
                              $keterangan_fee_pelanggan_1 =   "Loss Margin ".$keterangan_fee_pelanggan_1;
                            }
                            $pelanggan_1->update_saldo($tanggal, $jumlah, $dk);

                            Dompet::create([
                              'tanggal' => $tanggal,
                              'nominal' => $jumlah,
                              'keterangan' => $keterangan_fee_pelanggan_1,
                              'dibayar_oleh' => $pesanan->pelanggan->nama,
                              'dibayar_kepada' => $pesanan->diselesaikan_oleh->nama,
                              'user_id' => $pelanggan_1->id,
                              'saldo_berjalan' => $saldo_berjalan_pelanggan_1,
                              'debet_kredit' => $debet_kredit,
                              'pesanan_id' => $pesanan->id
                            ]);
                }
                $saldo_berjalan_pelanggan_1 += $selisih;


                if ($selisih != 0) {
                          $jumlah = 0;
                          if ($selisih > 0) {
                            $dk = 'd';
                            $debet_kredit = 'debet';
                            $jumlah = $selisih;
                            $ket = "Kelebihan Pembayaran" . $keterangan_pesanan;
                          } else {
                            $dk = 'k';
                            $debet_kredit = 'kredit';
                            $jumlah = $selisih * (-1);
                            $ket = "Kekurangan Pembayaran" . $keterangan_pesanan;
                          }

                          $pelanggan_1->update_saldo($tanggal, $jumlah, $dk);
                          Dompet::create([
                              'tanggal' => $tanggal,
                              'nominal' => $jumlah,
                              'keterangan' => $ket,
                              'dibayar_oleh' => $pesanan->pelanggan->nama,
                              'dibayar_kepada' => $pesanan->diselesaikan_oleh->nama,
                              'user_id' => $pelanggan_1->id,
                              'saldo_berjalan' => $saldo_berjalan_pelanggan_1,
                              'debet_kredit' => $debet_kredit,
                              'pesanan_id' => $pesanan->id
                          ]);
                }


                // uang dimasukkan ke kas, uang nominal yang dibayar dimasukkan ke kas..
                // sesuai dengan metode pembayarannya.

                // $kas = $metode_pembayaran->kas;
                // $jumlah = $pesanan->nominal_yang_dibayar;
                // $keterangan = "Pembayaran tran"

                // kalau metode pembayarnanya memiliki kas, maka masukkan ke kas..
                // kalau metode pembayarannya tidak memiliki kas, maka kurangi dompet pelanggan

                if($pesanan->nominal_yang_dibayar > 0 ){

                      if ($metode_pembayaran->kas->id != null){

                            // masukkan ke kas
                            $trx_kas = TransaksiKas::create([
                              'kas_id' => $metode_pembayaran->kas->id,
                              'tanggal' => $tanggal,
                              'keterangan' => "Pembayaran " . $keterangan_pesanan,
                              'debet_kredit' => 'd',
                              'nominal' => $pesanan->nominal_yang_dibayar
                            ]);
                            // update saldo
                            $metode_pembayaran->kas->update_saldo($tanggal, $pesanan->nominal_yang_dibayar, 'd');
                            $pesanan->transaksi_kas_id = $trx_kas->id;
                      } else {

                            $saldo_akhir_pelanggan_1 = $pelanggan_1->saldo_tanggal($tanggal);
                            $saldo_berjalan_pelanggan_1 = $saldo_akhir_pelanggan_1 - $pesanan->nominal_yang_dibayar;
                            $pelanggan_1->update_saldo($tanggal, $pesanan->nominal_yang_dibayar, 'k');

                            Dompet::create([
                              'tanggal' => $tanggal,
                              'nominal' => $pesanan->nominal_yang_dibayar,
                              'keterangan' => "Pembayaran " . $keterangan_pesanan,
                              'dibayar_oleh' => $pesanan->pelanggan->nama,
                              'dibayar_kepada' => $pesanan->diselesaikan_oleh->nama,
                              'user_id' => $pelanggan_1->id,
                              'saldo_berjalan' => $saldo_berjalan_pelanggan_1,
                              'debet_kredit' => 'kredit'
                            ]);

                      }
               }



/*       } else { // Step 2, kalau metode pembayaran tidak ada kas nya ....

                $saldo_akhir_pelanggan_1 = $pelanggan_1->saldo_tanggal($tanggal);
                $saldo_berjalan_pelanggan_1 = $saldo_akhir_pelanggan_1 - $pesanan->grand_total_pelanggan;
                $pelanggan_1->update_saldo($tanggal, $pesanan->grand_total_pelanggan, 'k');

                Dompet::create([
                  'tanggal' => $tanggal,
                  'nominal' => $pesanan->grand_total_pelanggan,
                  'keterangan' => "Pembayaran " . $keterangan_pesanan,
                  'dibayar_oleh' => $pesanan->pelanggan->nama,
                  'dibayar_kepada' => $pesanan->diselesaikan_oleh->nama,
                  'user_id' => $pelanggan_1->id,
                  'saldo_berjalan' => $saldo_berjalan_pelanggan_1,
                  'debet_kredit' => 'kredit'
                ]);

      }
 */
      // catat pengeluaran ongkir
      // semua pengeluaran ongkir adalah hutang..
      // jadi yang dicatat di sini adalah penambahan saldo hutang dari masing-masing metode pengiriman
      $metode_pengiriman = $pesanan->metode_pengiriman;

      // tambahkan hutang pada saldo pengiriman...

      if ($pesanan->ongkos_kirim > 0 && $metode_pengiriman->id > 2) {
        $pesanan->metode_pengiriman()->increment('saldo', $pesanan->ongkos_kirim);
      }

   //   if ($metode_pengiriman->jenis == "diantar" && $pesanan->ongkos_kirim > 0) {
        // tambahain dompet kurir toko, catat sebagai hutang
        // tambah ongkir ke dompet kurir

  //      $kurir = $pesanan->diantar_oleh;
  //      $saldo_akhir_kurir = $kurir->saldo_tanggal($tanggal);
  //      $saldo_berjalan_kurir = $saldo_akhir_kurir + $pesanan->ongkos_kirim;
  //      $kurir->update_saldo($tanggal, $pesanan->ongkos_kirim, 'd');
  //      Dompet::create([
  //        'tanggal' => $tanggal,
  //        'nominal' => $pesanan->ongkos_kirim,
  //        'keterangan' => "Upah Pengiriman " . $keterangan_pesanan,
  //        'dibayar_oleh' => $pesanan->pengiriman_diinput_oleh->nama,
  //        'dibayar_kepada' => $pesanan->diantar_oleh->nama,
  //        'user_id' => $kurir->id,
  //        'saldo_berjalan' => $saldo_berjalan_kurir,
  //        'debet_kredit' => 'debet'
  //      ]);

        // update pengeluaran

//        $pengeluaran = Pengeluaran::create([
//          'tanggal' => $tanggal,
//          'nominal' => $pesanan->ongkos_kirim,
//          'nomor_transaksi' =>"KurirToko-".$pesanan->id,
//          'nomor_invoice' => "KurirToko-".$pesanan->id,
//          'kategori_pengeluaran_id'=>1,
//          'keterangan'=>"Biaya Pengiriman " . $keterangan_pesanan,
//          'dibayar_oleh'=>$pesanan->pengiriman_diinput_oleh->nama,
//          'dibayar_kepada'=>$kurir->nama,
//          'diinput_oleh_id'=>Auth::id()
//         ]);

 //     }

      // stok ditarik
      // catat stok nya sebagai terjual.. dikurangin lah jumlahnya..

      // mencatat sejarah STOK YANG BERKUrang
      foreach ($pesanan->daftar_detil as $detil) {
        // mencatat sejarah STOK YANG BERTAMBAH
        $pesanan->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($pesanan->gudang_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $pesanan->gudang_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'keluar',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($pesanan->gudang_id, $detil->kuantitas, 'keluar');
      }


      // menambah point bagi setiap level yang terlibat


      $total_point = 0;

      foreach ($pesanan->daftar_detil as $detil) {
          $total_point += $detil->point;
      }

      switch ($level) {
        case 1 :
            if($pesanan->pelanggan->kategori_id != 5) {
                $pesanan->pelanggan->update_point($tanggal,$total_point,'d');
                Point::create([
                  'tanggal' => $tanggal,
                  'user_id' => $pelanggan_1->id,
                  'pesanan_id' => $pesanan->id,
                  'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
                  'nominal' => $total_point,
                  'debet_kredit' => 'debet'
                ]);
              }
        break;

        case 2 :
          if($pesanan->pelanggan->kategori_id != 5) {
            $pesanan->pelanggan->update_point($tanggal,$total_point,'d');
            Point::create([
              'tanggal' => $tanggal,
              'user_id' => $pelanggan_1->id,
              'pesanan_id' => $pesanan->id,
              'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
              'nominal' => $total_point,
              'debet_kredit' => 'debet'
            ]);
          }

          if($pelanggan_2->kategori_id != 5) {
            $pesanan->pelanggan->parent->update_point($tanggal,$total_point,'d');
            Point::create([
              'tanggal' => $tanggal,
              'user_id' => $pelanggan_2->id,
              'pesanan_id' => $pesanan->id,
              'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
              'nominal' => $total_point,
              'debet_kredit' => 'debet'
            ]);
          }

        break;
        case 3 :
          if($pesanan->pelanggan->kategori_id != 5) {
            $pesanan->pelanggan->update_point($tanggal,$total_point,'d');
            Point::create([
              'tanggal' => $tanggal,
              'user_id' => $pelanggan_1->id,
              'pesanan_id' => $pesanan->id,
              'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
              'nominal' => $total_point,
              'debet_kredit' => 'debet'
            ]);
          }

          if($pelanggan_2->kategori_id != 5) {
            $pesanan->pelanggan->parent->update_point($tanggal,$total_point,'d');
            Point::create([
              'tanggal' => $tanggal,
              'user_id' => $pelanggan_2->id,
              'pesanan_id' => $pesanan->id,
              'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
              'nominal' => $total_point,
              'debet_kredit' => 'debet'
            ]);
          }

        if($pelanggan_3->kategori_id != 5) {
          $pesanan->pelanggan->parent->parent->update_point($tanggal,$total_point,'d');
          Point::create([
            'tanggal' => $tanggal,
            'user_id' => $pelanggan_3->id,
            'pesanan_id' => $pesanan->id,
            'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
            'nominal' => $total_point,
            'debet_kredit' => 'debet'
          ]);
        }
        break;

        case 4 :
          if($pesanan->pelanggan->kategori_id != 5) {
              $pesanan->pelanggan->update_point($tanggal,$total_point,'d');
              Point::create([
                'tanggal' => $tanggal,
                'user_id' => $pelanggan_1->id,
                'pesanan_id' => $pesanan->id,
                'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
                'nominal' => $total_point,
                'debet_kredit' => 'debet'
              ]);
          }

          if($pelanggan_2->kategori_id != 5) {
            $pesanan->pelanggan->parent->update_point($tanggal,$total_point,'d');
            Point::create([
              'tanggal' => $tanggal,
              'user_id' => $pelanggan_2->id,
              'pesanan_id' => $pesanan->id,
              'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
              'nominal' => $total_point,
              'debet_kredit' => 'debet'
            ]);
          }

          if($pelanggan_3->kategori_id != 5) {
              $pesanan->pelanggan->parent->parent->update_point($tanggal,$total_point,'d');
              Point::create([
                'tanggal' => $tanggal,
                'user_id' => $pelanggan_3->id,
                'pesanan_id' => $pesanan->id,
                'keterangan' => 'Point atas transaksi nomor:'.$pesanan->id.' tanggal '.$tanggal,
                'nominal' => $total_point,
                'debet_kredit' => 'debet'
              ]);
          }
          break;
      }



    });


    return redirect()->route('mimin.pesanan.dikirim.index')->with('sukses', 'Pesanan berhasil diselesaikan');
  }
}
