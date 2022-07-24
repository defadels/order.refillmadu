<?php
  use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route url
Route::get('/', 'Admin\LagiDibikinController@index')->middleware('auth');

// Route Dashboards
//Route::get('/dashboard-analytics', 'DashboardController@dashboardAnalytics');

// Route Components/
//Route::get('/sk-layout-2-columns', 'StaterkitController@columns_2');
//Route::get('/sk-layout-fixed-navbar', 'StaterkitController@fixed_navbar');
//Route::get('/sk-layout-floating-navbar', 'StaterkitController@floating_navbar');
//Route::get('/sk-layout-fixed', 'StaterkitController@fixed_layout');

// acess controller
//Route::get('/access-control', 'AccessController@index');
//Route::get('/access-control/{roles}', 'AccessController@roles');
//Route::get('/modern-admin', 'AccessController@home')->middleware('permissions:approve-post');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name("logout");
Route::get('/register', 'Auth\RegisterController@showRegisterForm');
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// locale Route
Route::get('lang/{locale}',[LanguageController::class,'swap']);

Route::get('ganti/cabang/{cabang_id}','GantiCabangController@swap')->name('ganti_cabang');

Route::prefix('mimin')->name('mimin.')->namespace('Admin')->middleware(['auth','role:Super Admin|Admin Produksi|Kurir|Admin Orderan|Supervisor'])->group(function () {

  Route::get('/', 'Dashboard\Model1Controller@index')->name('dashboard.index');
  Route::prefix('pesanan')->name('pesanan.')->namespace('Pesanan')->group(function () {
    Route::resource('masuk','MasukController');
    Route::get('masuk/cari/pelanggan','MasukController@cari_pelanggan')->name('masuk.cari-pelanggan');
    Route::get('masuk/cari/produk','MasukController@cari_produk')->name('masuk.cari-produk');
    Route::get('masuk/cari/harga','MasukController@harga')->name('masuk.harga');
    Route::get('masuk/create/2','MasukController@create2')->name('masuk.create2');
    Route::get('masuk/cari/pelanggan','MasukController@cari_pelanggan')
        ->name('masuk.cari-pelanggan');

    Route::get('masuk/cari/alamat','MasukController@cari_alamat')->name('masuk.cari-alamat');

    Route::resource('preorder','PreorderController');
    Route::get('preorder/cari/pelanggan','PreorderController@cari_pelanggan')->name('preorder.cari-pelanggan');
    Route::get('preorder/cari/produk','PreorderController@cari_produk')->name('preorder.cari-produk');
    Route::get('preorder/cari/harga','PreorderController@harga')->name('preorder.harga');
    Route::get('preorder/create/2','PreorderController@create2')->name('preorder.create2');
    Route::get('preorder/cari/pelanggan','PreorderController@cari_pelanggan')
        ->name('preorder.cari-pelanggan');


    Route::get('preorder/cari/alamat','PreorderController@cari_alamat')->name('preorder.cari-alamat');
    Route::resource('diproses','DiprosesController');
    Route::get('diproses/{id}/cetak','DiprosesController@cetak')->name('diproses.cetak');

    Route::resource('dikirim','DikirimController');
    Route::resource('diantar','DiantarController');
    Route::resource('dibatalkan','DibatalkanController');
    Route::resource('selesai','SelesaiController');
    Route::get('selesai/{id}/cetak','SelesaiController@cetak')->name('selesai.cetak');

    Route::resource('status','StatusController');


 });
  Route::prefix('inventori')->name('inventori.')->namespace('Inventori')->group(function () {

    Route::prefix('produk')->name('produk.')->group(function () {
      Route::resource('/','ProdukController')->parameters([''=>'produk']);
      Route::resource('k/kategori','KategoriProdukController');
      Route::resource('{produk}/hargakhusus','HargaKhususProdukController');
      Route::resource('{produk}/struktur','StrukturProdukController');
    });

    Route::resource('rakit-produk','RakitProdukController');
    Route::get('rakit-produk/{id}/post','RakitProdukController@post_form')->name('rakit-produk.post');
    Route::patch('rakit-produk/{id}/post','RakitProdukController@post')->name('rakit-produk.post');

    Route::resource('stok-masuk','StokMasukController');
    Route::get('stok-masuk/{id}/post','StokMasukController@post_form')->name('stok-masuk.post');
    Route::patch('stok-masuk/{id}/post','StokMasukController@post')->name('stok-masuk.post');

    Route::resource('stok-keluar','StokKeluarController');
    Route::get('stok-keluar/{id}/post','StokKeluarController@post_form')->name('stok-keluar.post');
    Route::patch('stok-keluar/{id}/post','StokKeluarController@post')->name('stok-keluar.post');

    Route::resource('transfer','TransferController');
    Route::get('transfer/{id}/post','TransferController@post_form')->name('transfer.post');
    Route::patch('transfer/{id}/post','TransferController@post')->name('transfer.post');

    Route::resource('retur-barang','ReturBarangController');
    Route::get('retur-barang/{id}/post','ReturBarangController@post_form')->name('retur-barang.post');
    Route::patch('retur-barang/{id}/post','ReturBarangController@post')->name('retur-barang.post');
    Route::get('retur-barang/cari/pelanggan','ReturBarangController@cari_pelanggan')->name('retur-barang.cari-pelanggan');
 });

 Route::prefix('laporan')->name('laporan.')->namespace('Laporan')->group(function () {
     Route::get('penjualan', 'PenjualanController@index')->name('penjualan.index');
     Route::get('penjualan/laporan1', 'PenjualanController@laporan_1')->name('penjualan.laporan1');
     Route::get('penjualan/laporan2', 'PenjualanController@laporan_2')->name('penjualan.laporan2');
     Route::get('penjualan/laporan3', 'PenjualanController@laporan_3')->name('penjualan.laporan3');
     Route::get('penjualan/laporan4', 'PenjualanController@laporan_4')->name('penjualan.laporan4');
     Route::get('penjualan/laporan5', 'PenjualanController@laporan_5')->name('penjualan.laporan5');
     Route::get('penjualan/pelanggan/cari','PenjualanController@cari_pelanggan')->name('penjualan.pelanggan.cari');

     Route::get('stok', 'StokController@index')->name('stok.index');
     Route::get('piutang', 'PiutangController@index')->name('piutang.index');
     Route::get('hutang', 'HutangController@index')->name('hutang.index');
     Route::get('hutang/dompet', 'HutangController@dompet')->name('hutang.dompet');
     Route::get('hutang/pengiriman', 'HutangController@pengiriman')->name('hutang.pengiriman');
     Route::get('arus-kas', 'ArusKasController@index')->name('arus_kas.index');
     Route::get('persediaan', 'PersediaanController@index')->name('persediaan.index');
     Route::get('laba-rugi', 'LabaRugiController@index')->name('laba_rugi.index');
     Route::get('pengiriman', 'PengirimanController@index')->name('pengiriman.index');
  });

  Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
    Route::resource('/','PengeluaranController')->parameters([''=>'pengeluaran']);
    Route::get('{pengeluaran}/batalkan', 'PengeluaranController@batalkan')->name('batalkan');
    Route::resource('k/kategori','KategoriPengeluaranController');
 });
  Route::prefix('kas')->name('kas.')->group(function () {
    Route::resource('/','KasController')->parameters([''=>'kas']);
    Route::resource('t/transfer','TransferKasController');

  });
  Route::get('dompet','DompetController@index')->name('dompet.index');
  Route::get('dompet/pelanggan/cari','DompetController@cari_pelanggan')->name('dompet.pelanggan.cari');
  Route::get('dompet/transaksi','DompetController@transaksi')->name('dompet.transaksi');
  Route::get('dompet/transaksi/tambah','DompetController@tambah_transaksi')->name('dompet.transaksi.tambah');
  Route::get('dompet/transaksi/kurangi','DompetController@kurangi_transaksi')->name('dompet.transaksi.kurangi');
  Route::post('dompet/transaksi/tambah','DompetController@tambah_transaksi_post')->name('dompet.transaksi.tambah');
  Route::post('dompet/transaksi/kurangi','DompetController@kurangi_transaksi_post')->name('dompet.transaksi.kurangi');
  Route::get('dompet/transaksi/{id}/lihat','DompetController@lihat_transaksi')->name('dompet.transaksi.lihat');
  Route::get('dompet/{user}/rincian','DompetController@dompet')->name('dompet.pelanggan.index');
  Route::get('dompet/{user}/tambah','DompetController@tambah')->name('dompet.pelanggan.tambah');
  Route::post('dompet/{user}/tambah','DompetController@tambah_post')->name('dompet.pelanggan.tambah');
  Route::get('dompet/{user}/kurangi','DompetController@kurangi')->name('dompet.pelanggan.kurangi');
  Route::post('dompet/{user}/kurangi','DompetController@kurangi_post')->name('dompet.pelanggan.kurangi');

  Route::get('point','PointController@index')->name('point.index');
  Route::get('point/pelanggan/cari','PointController@cari_pelanggan')->name('point.pelanggan.cari');
  Route::get('point/transaksi','PointController@transaksi')->name('point.transaksi');
  Route::get('point/transaksi/tambah','PointController@tambah_transaksi')->name('point.transaksi.tambah');
  Route::get('point/transaksi/kurangi','PointController@kurangi_transaksi')->name('point.transaksi.kurangi');
  Route::post('point/transaksi/tambah','PointController@tambah_transaksi_post')->name('point.transaksi.tambah');
  Route::post('point/transaksi/kurangi','PointController@kurangi_transaksi_post')->name('point.transaksi.kurangi');
  Route::get('point/transaksi/{id}/lihat','PointController@lihat_transaksi')->name('point.transaksi.lihat');
  Route::get('point/{user}/rincian','PointController@point')->name('point.pelanggan.index');
  Route::get('point/{user}/tambah','PointController@tambah')->name('point.pelanggan.tambah');
  Route::post('point/{user}/tambah','PointController@tambah_post')->name('point.pelanggan.tambah');
  Route::get('point/{user}/kurangi','PointController@kurangi')->name('point.pelanggan.kurangi');
  Route::post('point/{user}/kurangi','PointController@kurangi_post')->name('point.pelanggan.kurangi');

  Route::prefix('orang')->name('orang.')->namespace('Orang')->group(function () {

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
      Route::resource('/','PelangganController')->parameters([''=>'pelanggan'])->except(['show']);;
      Route::resource('k/kategori','KategoriPelangganController');

     Route::get('{id}/alamat','PelangganAlamatController@index')->name('alamat.index');
     Route::get('{id}/alamat/create','PelangganAlamatController@create')->name('alamat.create');
     Route::post('{id}/alamat/create','PelangganAlamatController@store')->name('alamat.store');
     Route::get('{id}/alamat/{id_alamat}/edit','PelangganAlamatController@edit')->name('alamat.edit');
     Route::put('{id}/alamat/{id_alamat}/edit','PelangganAlamatController@update')->name('alamat.update');
     Route::delete('{id}/alamat/{id_alamat}/destroy','PelangganAlamatController@destroy')->name('alamat.destroy');

     Route::get('cari','PelangganController@cari_pelanggan')->name('cari');

    });
    Route::resource('distributor','DistributorController');
    Route::resource('suplier','SuplierController');
    Route::resource('kurir','KurirController');
  });

  Route::prefix('pengaturan')->name('pengaturan.')->namespace('Pengaturan')->group(function () {
    Route::resource('hakakses','HakAksesController');
    Route::resource('pengguna','PenggunaController');
    Route::resource('provinsi','ProvinsiController');
    Route::resource('provinsi.kabupaten','KabupatenController');
    Route::resource('provinsi.kabupaten.kecamatan','KecamatanController');
    Route::resource('gudang','GudangController');
    Route::resource('cabang','CabangController');
    Route::resource('pengiriman','PengirimanController');
    Route::resource('pembayaran','PembayaranController');
  });
});

Route::prefix('/')->name('pelanggan.')->namespace('Pelanggan')->middleware('auth')->group(function () {

  Route::get('/', 'LagiDibikinController@index')->name('dashboard.index');
  Route::prefix('pesanan')->name('pesanan.')->namespace('Pesanan')->group(function () {
    Route::resource('masuk','MasukController');
    Route::get('masuk/cari/pelanggan','MasukController@cari_pelanggan')->name('masuk.cari-pelanggan');
    Route::get('masuk/cari/produk','MasukController@cari_produk')->name('masuk.cari-produk');

    Route::get('masuk/cari/harga','MasukController@harga')->name('masuk.harga');
    Route::get('masuk/create/2','MasukController@create2')->name('masuk.create2');
    Route::get('masuk/cari/pelanggan','MasukController@cari_pelanggan')
        ->name('masuk.cari-pelanggan');
    Route::get('masuk/cari/alamat','PelangganController@cari_alamat')->name('masuk.cari-alamat');
    Route::resource('diproses','DiprosesController');
    Route::resource('selesai','SelesaiController');
    Route::resource('dibatalkan','DibatalkanController');


 });
 Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
  Route::resource('/','Orang\PelangganController')->parameters([''=>'pelanggan'])->except(['show']);;

  Route::get('{id}/alamat','Orang\AlamatController@index')->name('alamat.index');
  Route::get('{id}/alamat/create','Orang\AlamatController@create')->name('alamat.create');
  Route::post('{id}/alamat/create','Orang\AlamatController@store')->name('alamat.store');
  Route::get('{id}/alamat/{id_alamat}/edit','Orang\AlamatController@edit')->name('alamat.edit');
  Route::put('{id}/alamat/{id_alamat}/edit','Orang\AlamatController@update')->name('alamat.update');
  Route::delete('{id}/alamat/{id_alamat}/destroy','Orang\AlamatController@destroy')->name('alamat.destroy');


  Route::get('cari','Orang\PelangganController@cari_pelanggan')->name('cari');

 // Route::resource('k/kategori','Orang\KategoriPelangganController');
 });
 Route::resource('dompet','DompetController')->parameters([''=>'dompet']);
 Route::resource('point','PointController')->parameters([''=>'point']);

});
