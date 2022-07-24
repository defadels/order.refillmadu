<?php

use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('kategori_produk')->insert([
        ['nama'=>'Madu','id'=>1 ],
        ['nama'=>'Botol','id'=>2 ],
        ['nama'=>'Buku','id'=>3 ],

        ['nama'=>'Herbal','id'=>4 ],
        ['nama'=>'Aren','id'=>5 ],
        ['nama'=>'Mesir','id'=>6 ],
        ['nama'=>'MDNY','id'=>7 ],
        ['nama'=>'Tiris','id'=>8 ]
      ]);


      DB::table('produk')->insert([

        ['kategori_id'=>5,'kode'=>'1001','nama'=>'GS 250Gr','deskripsi'=>'GS 250Gr','satuan'=>'pcs','harga_jual'=>30000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>5,'kode'=>'1002','nama'=>'GS 450Gr','deskripsi'=>'GS 450Gr','satuan'=>'pcs','harga_jual'=>55000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>5,'kode'=>'1003','nama'=>'GS 900Gr','deskripsi'=>'GS 900Gr','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>2,'kode'=>'1004','nama'=>'Kemasan 0.3Kg','deskripsi'=>'Kemasan 0.3Kg','satuan'=>'pcs','harga_jual'=>3000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>2,'kode'=>'1005','nama'=>'Kemasan 0.5Kg Bulat','deskripsi'=>'Kemasan 0.5Kg Bulat','satuan'=>'pcs','harga_jual'=>4000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>2,'kode'=>'1006','nama'=>'Kemasan 0.5Kg Petak','deskripsi'=>'Kemasan 0.5Kg Petak','satuan'=>'pcs','harga_jual'=>4000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>2,'kode'=>'1007','nama'=>'Kemasan 1Kg Bulat','deskripsi'=>'Kemasan 1Kg Bulat','satuan'=>'pcs','harga_jual'=>5000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>2,'kode'=>'1008','nama'=>'Kemasan 1Kg Petak','deskripsi'=>'Kemasan 1Kg Petak','satuan'=>'pcs','harga_jual'=>5000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>3,'kode'=>'1009','nama'=>'Buku Sadar Financial','deskripsi'=>'Buku Sadar Financial','satuan'=>'pcs','harga_jual'=>300000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1010','nama'=>'Gomars','deskripsi'=>'Gomars','satuan'=>'pcs','harga_jual'=>35000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1011','nama'=>'Habbasyi Oil 210','deskripsi'=>'Habbasyi Oil 210','satuan'=>'pcs','harga_jual'=>140000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1012','nama'=>'Habbasyifa 90','deskripsi'=>'Habbasyifa 90','satuan'=>'pcs','harga_jual'=>90000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1013','nama'=>'Habbats Kurma Ajwa','deskripsi'=>'Habbats Kurma Ajwa','satuan'=>'pcs','harga_jual'=>110000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1014','nama'=>'Habbatussauda','deskripsi'=>'Habbatussauda','satuan'=>'pcs','harga_jual'=>120000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1015','nama'=>'Hajar Jahanam Premium','deskripsi'=>'Hajar Jahanam Premium','satuan'=>'pcs','harga_jual'=>70000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1016','nama'=>'Jahe Merah','deskripsi'=>'Jahe Merah','satuan'=>'pcs','harga_jual'=>50000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1017','nama'=>'Madu Batuk Eliman','deskripsi'=>'Madu Batuk Eliman','satuan'=>'pcs','harga_jual'=>62000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1018','nama'=>'Madu Eury-X','deskripsi'=>'Madu Eury-X','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1019','nama'=>'Madu Langsing','deskripsi'=>'Madu Langsing','satuan'=>'pcs','harga_jual'=>70000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1020','nama'=>'Madu NA','deskripsi'=>'Madu NA','satuan'=>'pcs','harga_jual'=>80000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1021','nama'=>'Madu Pahit Gamat Gold','deskripsi'=>'Madu Pahit Gamat Gold','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1022','nama'=>'Madu Pahit Insuline','deskripsi'=>'Madu Pahit Insuline','satuan'=>'pcs','harga_jual'=>95000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1023','nama'=>'Madu Pahit Pro','deskripsi'=>'Madu Pahit Pro','satuan'=>'pcs','harga_jual'=>90000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1024','nama'=>'Madu SP','deskripsi'=>'Madu SP','satuan'=>'pcs','harga_jual'=>70000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1025','nama'=>'Madu Suka','deskripsi'=>'Madu Suka','satuan'=>'pcs','harga_jual'=>80000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1026','nama'=>'Madu Super Tonik','deskripsi'=>'Madu Super Tonik','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1027','nama'=>'Madu Zuriyat Promil','deskripsi'=>'Madu Zuriyat Promil','satuan'=>'pcs','harga_jual'=>140000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1028','nama'=>'Minyak Lintah','deskripsi'=>'Minyak Lintah','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1029','nama'=>'Minyak Sumber Waras','deskripsi'=>'Minyak Sumber Waras','satuan'=>'pcs','harga_jual'=>200000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1030','nama'=>'MSW','deskripsi'=>'MSW','satuan'=>'pcs','harga_jual'=>199000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1031','nama'=>'Sarkum Harokah','deskripsi'=>'Sarkum Harokah','satuan'=>'pcs','harga_jual'=>50000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1032','nama'=>'Sarkum Madu Angkak Gamat Plus','deskripsi'=>'Sarkum Madu Angkak Gamat Plus','satuan'=>'pcs','harga_jual'=>90000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1033','nama'=>'Sarkum Madu Angkak Plus','deskripsi'=>'Sarkum Madu Angkak Plus','satuan'=>'pcs','harga_jual'=>90000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1034','nama'=>'Zaitun Afra','deskripsi'=>'Zaitun Afra','satuan'=>'pcs','harga_jual'=>120000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1035','nama'=>'Zaitun Mumtaz','deskripsi'=>'Zaitun Mumtaz','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1036','nama'=>'Zevo Zaituna 100','deskripsi'=>'Zevo Zaituna 100','satuan'=>'pcs','harga_jual'=>60000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>4,'kode'=>'1037','nama'=>'Zevo Zaituna 200','deskripsi'=>'Zevo Zaituna 200','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.0,'poin'=>0],
['kategori_id'=>1,'kode'=>'1038','nama'=>'Curah(Kg)','deskripsi'=>'Curah(Kg)','satuan'=>'Kg','harga_jual'=>120000,'harga_pokok'=>0,'volume'=>1.0,'poin'=>0],
['kategori_id'=>1,'kode'=>'1039','nama'=>'Curah(Ltr)','deskripsi'=>'Curah(Ltr)','satuan'=>'Ltr','harga_jual'=>144000,'harga_pokok'=>0,'volume'=>1.4,'poin'=>0],
['kategori_id'=>1,'kode'=>'1040','nama'=>'Madu 1,2Kg Bulat','deskripsi'=>'Madu 1,2Kg Bulat','satuan'=>'pcs','harga_jual'=>170000,'harga_pokok'=>0,'volume'=>1.2,'poin'=>25],
['kategori_id'=>1,'kode'=>'1041','nama'=>'Madu 1,2Kg Petak','deskripsi'=>'Madu 1,2Kg Petak','satuan'=>'pcs','harga_jual'=>170000,'harga_pokok'=>0,'volume'=>1.2,'poin'=>25],
['kategori_id'=>1,'kode'=>'1042','nama'=>'Madu 1,4Kg Bulat','deskripsi'=>'Madu 1,4Kg Bulat','satuan'=>'pcs','harga_jual'=>180000,'harga_pokok'=>0,'volume'=>1.4,'poin'=>30],
['kategori_id'=>1,'kode'=>'1043','nama'=>'Madu 1Kg Bulat','deskripsi'=>'Madu 1Kg Bulat','satuan'=>'pcs','harga_jual'=>150000,'harga_pokok'=>0,'volume'=>1.0,'poin'=>20],
['kategori_id'=>1,'kode'=>'1044','nama'=>'Madu 1Kg Petak','deskripsi'=>'Madu 1Kg Petak','satuan'=>'pcs','harga_jual'=>150000,'harga_pokok'=>0,'volume'=>1.0,'poin'=>20],
['kategori_id'=>1,'kode'=>'1045','nama'=>'Madu 300gr Bulat','deskripsi'=>'Madu 300gr Bulat','satuan'=>'pcs','harga_jual'=>60000,'harga_pokok'=>0,'volume'=>0.3,'poin'=>5],
['kategori_id'=>1,'kode'=>'1046','nama'=>'Madu 500gr Bulat','deskripsi'=>'Madu 500gr Bulat','satuan'=>'pcs','harga_jual'=>85000,'harga_pokok'=>0,'volume'=>0.5,'poin'=>10],
['kategori_id'=>1,'kode'=>'1047','nama'=>'Madu 500gr Petak','deskripsi'=>'Madu 500gr Petak','satuan'=>'pcs','harga_jual'=>85000,'harga_pokok'=>0,'volume'=>0.5,'poin'=>10],
['kategori_id'=>1,'kode'=>'1048','nama'=>'Madu 700gr Bulat','deskripsi'=>'Madu 700gr Bulat','satuan'=>'pcs','harga_jual'=>110000,'harga_pokok'=>0,'volume'=>0.7,'poin'=>15],
['kategori_id'=>6,'kode'=>'1049','nama'=>'Madu Mesir 700Gr Bulat','deskripsi'=>'Madu Mesir 700Gr Bulat','satuan'=>'pcs','harga_jual'=>312500,'harga_pokok'=>0,'volume'=>0.7,'poin'=>0],
['kategori_id'=>8,'kode'=>'1050','nama'=>'MDNY 1Kg Bulat','deskripsi'=>'MDNY 1Kg Bulat','satuan'=>'pcs','harga_jual'=>90000,'harga_pokok'=>0,'volume'=>1.0,'poin'=>0],
['kategori_id'=>8,'kode'=>'1051','nama'=>'MDNY 500gr Bulat','deskripsi'=>'MDNY 500gr Bulat','satuan'=>'pcs','harga_jual'=>50000,'harga_pokok'=>0,'volume'=>0.5,'poin'=>0],
['kategori_id'=>7,'kode'=>'1052','nama'=>'Tiris 1Kg Bulat','deskripsi'=>'Tiris 1Kg Bulat','satuan'=>'pcs','harga_jual'=>180000,'harga_pokok'=>0,'volume'=>1.0,'poin'=>0],
['kategori_id'=>7,'kode'=>'1053','nama'=>'Tiris 300Gr Bulat','deskripsi'=>'Tiris 300Gr Bulat','satuan'=>'pcs','harga_jual'=>75000,'harga_pokok'=>0,'volume'=>0.3,'poin'=>0],
['kategori_id'=>7,'kode'=>'1054','nama'=>'Tiris 500gr Bulat','deskripsi'=>'Tiris 500gr Bulat','satuan'=>'pcs','harga_jual'=>100000,'harga_pokok'=>0,'volume'=>0.5,'poin'=>0]
      ]);

        DB::table('harga_khusus')->insert([
          ['produk_id'=>1, 'kategori_id'=>1, 'harga_jual'=>18000],['produk_id'=>1, 'kategori_id'=>2, 'harga_jual'=>21000],['produk_id'=>1, 'kategori_id'=>3, 'harga_jual'=>24000],
          ['produk_id'=>2, 'kategori_id'=>1, 'harga_jual'=>33000],['produk_id'=>2, 'kategori_id'=>2, 'harga_jual'=>38500],['produk_id'=>2, 'kategori_id'=>3, 'harga_jual'=>44000],
          ['produk_id'=>3, 'kategori_id'=>1, 'harga_jual'=>60000],['produk_id'=>3, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>3, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>4, 'kategori_id'=>1, 'harga_jual'=>2800],['produk_id'=>4, 'kategori_id'=>2, 'harga_jual'=>3000],['produk_id'=>4, 'kategori_id'=>3, 'harga_jual'=>3000],
          ['produk_id'=>5, 'kategori_id'=>1, 'harga_jual'=>3800],['produk_id'=>5, 'kategori_id'=>2, 'harga_jual'=>4000],['produk_id'=>5, 'kategori_id'=>3, 'harga_jual'=>4000],
          ['produk_id'=>6, 'kategori_id'=>1, 'harga_jual'=>3800],['produk_id'=>6, 'kategori_id'=>2, 'harga_jual'=>4000],['produk_id'=>6, 'kategori_id'=>3, 'harga_jual'=>4000],
          ['produk_id'=>7, 'kategori_id'=>1, 'harga_jual'=>4800],['produk_id'=>7, 'kategori_id'=>2, 'harga_jual'=>5000],['produk_id'=>7, 'kategori_id'=>3, 'harga_jual'=>5000],
          ['produk_id'=>8, 'kategori_id'=>1, 'harga_jual'=>3800],['produk_id'=>8, 'kategori_id'=>2, 'harga_jual'=>4000],
          ['produk_id'=>9, 'kategori_id'=>3, 'harga_jual'=>250000],
          ['produk_id'=>10, 'kategori_id'=>2, 'harga_jual'=>28000],['produk_id'=>10, 'kategori_id'=>3, 'harga_jual'=>31500],
          ['produk_id'=>11, 'kategori_id'=>1, 'harga_jual'=>84000],['produk_id'=>11, 'kategori_id'=>2, 'harga_jual'=>98000],['produk_id'=>11, 'kategori_id'=>3, 'harga_jual'=>112000],
          ['produk_id'=>12, 'kategori_id'=>1, 'harga_jual'=>63000],['produk_id'=>12, 'kategori_id'=>2, 'harga_jual'=>72000],['produk_id'=>12, 'kategori_id'=>3, 'harga_jual'=>81000],
          ['produk_id'=>13, 'kategori_id'=>1, 'harga_jual'=>77000],['produk_id'=>13, 'kategori_id'=>2, 'harga_jual'=>88000],['produk_id'=>13, 'kategori_id'=>3, 'harga_jual'=>99000],
          ['produk_id'=>14, 'kategori_id'=>2, 'harga_jual'=>84000],['produk_id'=>14, 'kategori_id'=>3, 'harga_jual'=>96000],
          ['produk_id'=>15, 'kategori_id'=>2, 'harga_jual'=>49000],['produk_id'=>15, 'kategori_id'=>3, 'harga_jual'=>56000],
          ['produk_id'=>16, 'kategori_id'=>1, 'harga_jual'=>30000],['produk_id'=>16, 'kategori_id'=>2, 'harga_jual'=>35000],['produk_id'=>16, 'kategori_id'=>3, 'harga_jual'=>40000],
          ['produk_id'=>17, 'kategori_id'=>3, 'harga_jual'=>50000],
          ['produk_id'=>18, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>18, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>19, 'kategori_id'=>2, 'harga_jual'=>49000],['produk_id'=>19, 'kategori_id'=>3, 'harga_jual'=>56000],
          ['produk_id'=>20, 'kategori_id'=>2, 'harga_jual'=>56000],['produk_id'=>20, 'kategori_id'=>3, 'harga_jual'=>64000],
          ['produk_id'=>21, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>21, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>22, 'kategori_id'=>2, 'harga_jual'=>63000],['produk_id'=>22, 'kategori_id'=>3, 'harga_jual'=>72000],
          ['produk_id'=>23, 'kategori_id'=>2, 'harga_jual'=>63000],['produk_id'=>23, 'kategori_id'=>3, 'harga_jual'=>72000],
          ['produk_id'=>24, 'kategori_id'=>2, 'harga_jual'=>50000],['produk_id'=>24, 'kategori_id'=>3, 'harga_jual'=>56000],
          ['produk_id'=>25, 'kategori_id'=>2, 'harga_jual'=>56000],['produk_id'=>25, 'kategori_id'=>3, 'harga_jual'=>64000],
          ['produk_id'=>26, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>26, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>27, 'kategori_id'=>1, 'harga_jual'=>87000],['produk_id'=>27, 'kategori_id'=>2, 'harga_jual'=>98000],['produk_id'=>27, 'kategori_id'=>3, 'harga_jual'=>112000],
          ['produk_id'=>28, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>28, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>29, 'kategori_id'=>2, 'harga_jual'=>140000],['produk_id'=>29, 'kategori_id'=>3, 'harga_jual'=>160000],
          ['produk_id'=>30, 'kategori_id'=>2, 'harga_jual'=>140000],['produk_id'=>30, 'kategori_id'=>3, 'harga_jual'=>160000],
          ['produk_id'=>31, 'kategori_id'=>3, 'harga_jual'=>40000],
          ['produk_id'=>32, 'kategori_id'=>2, 'harga_jual'=>63000],['produk_id'=>32, 'kategori_id'=>3, 'harga_jual'=>72000],
          ['produk_id'=>33, 'kategori_id'=>2, 'harga_jual'=>63000],['produk_id'=>33, 'kategori_id'=>3, 'harga_jual'=>72000],
          ['produk_id'=>34, 'kategori_id'=>2, 'harga_jual'=>84000],['produk_id'=>34, 'kategori_id'=>3, 'harga_jual'=>96000],
          ['produk_id'=>35, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>35, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>36, 'kategori_id'=>2, 'harga_jual'=>42000],['produk_id'=>36, 'kategori_id'=>3, 'harga_jual'=>48000],
          ['produk_id'=>37, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>37, 'kategori_id'=>3, 'harga_jual'=>80000],
          ['produk_id'=>38, 'kategori_id'=>1, 'harga_jual'=>90000],['produk_id'=>38, 'kategori_id'=>2, 'harga_jual'=>105000],['produk_id'=>38, 'kategori_id'=>3, 'harga_jual'=>120000],
          ['produk_id'=>39, 'kategori_id'=>1, 'harga_jual'=>108000],['produk_id'=>39, 'kategori_id'=>2, 'harga_jual'=>126000],['produk_id'=>39, 'kategori_id'=>3, 'harga_jual'=>144000],
          ['produk_id'=>40, 'kategori_id'=>1, 'harga_jual'=>102000],['produk_id'=>40, 'kategori_id'=>2, 'harga_jual'=>120000],['produk_id'=>40, 'kategori_id'=>3, 'harga_jual'=>136000],
          ['produk_id'=>41, 'kategori_id'=>1, 'harga_jual'=>102000],['produk_id'=>41, 'kategori_id'=>2, 'harga_jual'=>120000],['produk_id'=>41, 'kategori_id'=>3, 'harga_jual'=>136000],
          ['produk_id'=>42, 'kategori_id'=>1, 'harga_jual'=>108000],['produk_id'=>42, 'kategori_id'=>2, 'harga_jual'=>126000],['produk_id'=>42, 'kategori_id'=>3, 'harga_jual'=>144000],
          ['produk_id'=>43, 'kategori_id'=>1, 'harga_jual'=>90000],['produk_id'=>43, 'kategori_id'=>2, 'harga_jual'=>105000],['produk_id'=>43, 'kategori_id'=>3, 'harga_jual'=>120000],['produk_id'=>43, 'kategori_id'=>4, 'harga_jual'=>135000],
          ['produk_id'=>44, 'kategori_id'=>1, 'harga_jual'=>90000],['produk_id'=>44, 'kategori_id'=>2, 'harga_jual'=>105000],['produk_id'=>44, 'kategori_id'=>3, 'harga_jual'=>120000],['produk_id'=>44, 'kategori_id'=>4, 'harga_jual'=>135000],
          ['produk_id'=>45, 'kategori_id'=>1, 'harga_jual'=>36000],['produk_id'=>45, 'kategori_id'=>2, 'harga_jual'=>42000],['produk_id'=>45, 'kategori_id'=>3, 'harga_jual'=>48000],['produk_id'=>45, 'kategori_id'=>4, 'harga_jual'=>54000],
          ['produk_id'=>46, 'kategori_id'=>1, 'harga_jual'=>50000],['produk_id'=>46, 'kategori_id'=>2, 'harga_jual'=>60000],['produk_id'=>46, 'kategori_id'=>3, 'harga_jual'=>70000],['produk_id'=>46, 'kategori_id'=>4, 'harga_jual'=>77000],
          ['produk_id'=>47, 'kategori_id'=>1, 'harga_jual'=>50000],['produk_id'=>47, 'kategori_id'=>2, 'harga_jual'=>60000],['produk_id'=>47, 'kategori_id'=>3, 'harga_jual'=>70000],
          ['produk_id'=>48, 'kategori_id'=>1, 'harga_jual'=>66000],['produk_id'=>48, 'kategori_id'=>2, 'harga_jual'=>77000],['produk_id'=>48, 'kategori_id'=>3, 'harga_jual'=>88000],
          ['produk_id'=>49, 'kategori_id'=>1, 'harga_jual'=>187500],['produk_id'=>49, 'kategori_id'=>2, 'harga_jual'=>219000],['produk_id'=>49, 'kategori_id'=>3, 'harga_jual'=>250000],
          ['produk_id'=>50, 'kategori_id'=>1, 'harga_jual'=>90000],
          ['produk_id'=>51, 'kategori_id'=>1, 'harga_jual'=>50000],
          ['produk_id'=>52, 'kategori_id'=>1, 'harga_jual'=>108000],['produk_id'=>52, 'kategori_id'=>2, 'harga_jual'=>128000],['produk_id'=>52, 'kategori_id'=>3, 'harga_jual'=>144000],
          ['produk_id'=>53, 'kategori_id'=>1, 'harga_jual'=>45000],['produk_id'=>53, 'kategori_id'=>2, 'harga_jual'=>52000],['produk_id'=>53, 'kategori_id'=>3, 'harga_jual'=>60000],
          ['produk_id'=>54, 'kategori_id'=>1, 'harga_jual'=>60000],['produk_id'=>54, 'kategori_id'=>2, 'harga_jual'=>70000],['produk_id'=>54, 'kategori_id'=>3, 'harga_jual'=>80000]

        ]);

    }

}
