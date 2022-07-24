<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $permissions = [
        "pengaturan-hakakses",
        "pengaturan-pengguna",
        "pengaturan-pengiriman",
        "pengaturan-pembayaran",
        "pengaturan-gudang",
        "pengaturan-cabang",
        "pengaturan-wilayah",

        "laporan-penjualan",
        "laporan-stok",
        "laporan-piutang",
        "laporan-hutang",
        "laporan-persediaan",
        "laporan-labarugi",
        "laporan-pengiriman",

        "mitra-pelanggan-lihat",
        "mitra-pelanggan-tambah",
        "mitra-pelanggan-edit",

        "mitra-suplier-lihat",
        "mitra-suplier-tambah",
        "mitra-suplier-edit",

        "mitra-kurirtoko-lihat",
        "mitra-kurirtoko-tambah",
        "mitra-kurirtoko-edit",

        "dompet-lihat",
        "dompet-tambah",
        "dompet-kurangi",

        "kas-lihat",
        "kas-tambah",
        "kas-edit",

        "kas-transfer-lihat",
        "kas-transfer-tambah",
        "kas-transfer-edit",

        "inventori-produk-lihat",
        "inventori-produk-edit",
        "inventori-produk-tambah",

        "inventori-produk-kategori-lihat",
        "inventori-produk-kategori-edit",
        "inventori-produk-kategori-tambah",

        "inventori-rakitproduk-lihat",
        "inventori-rakitproduk-edit",
        "inventori-rakitproduk-tambah",

        "inventori-stokmasuk-lihat",
        "inventori-stokmasuk-edit",
        "inventori-stokmasuk-tambah",

        "inventori-stokkeluar-lihat",
        "inventori-stokkeluar-edit",
        "inventori-stokkeluar-tambah",

        "inventori-transferstok-lihat",
        "inventori-transferstok-edit",
        "inventori-transferstok-tambah",

        "inventori-returbarang-lihat",
        "inventori-returbarang-edit",
        "inventori-returbarang-tambah",

        "pengeluaran-lihat",
        "pengeluaran-edit",
        "pengeluaran-tambah",
        "pengeluaran-hapus",

        "kategori-pengeluaran-lihat",
        "kategori-pengeluaran-edit",
        "kategori-pengeluaran-tambah",
        "kategori-pengeluaran-hapus",

        "pesanan-preorder-lihat",
        "pesanan-preorder-tambah",
        "pesanan-preorder-edit",

        "pesanan-masuk-lihat",
        "pesanan-masuk-tambah",
        "pesanan-masuk-edit",

        "pesanan-diproses-lihat",
        "pesanan-diproses-edit",

        "pesanan-dikirim-lihat",
        "pesanan-dikirim-edit",

        "pesanan-selesai-lihat",
        "pesanan-dibatalkan-lihat",
        "pesanan-status",

        "akses-global"

      ];

     foreach ($permissions as $permission) {
      Permission::create(['name' => $permission]);
     }

     $super_admin = Role::findByName("Super Admin");
     $super_admin->givePermissionTo(Permission::all());
    }
}
