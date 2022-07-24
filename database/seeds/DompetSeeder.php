<?php

use Illuminate\Database\Seeder;
use App\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
class DompetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      $daftar_saldo = collect(
        [
          [
            'user_id' => '43',
            'saldo' => '-468000'
          ],
          [
            'user_id' => '44',
            'saldo' => '-225000'
          ],
          [
            'user_id' => '45',
            'saldo' => '-856000'
          ],
          [
            'user_id' => '46',
            'saldo' => '-300000'
          ],
          [
            'user_id' => '47',
            'saldo' => '-150000'
          ],
          [
            'user_id' => '6',
            'saldo' => '-155000'
          ],
          [
            'user_id' => '7',
            'saldo' => '-645000'
          ],
          [
            'user_id' => '8',
            'saldo' => '-86000'
          ],
          [
            'user_id' => '9',
            'saldo' => '-190000'
          ],
          [
            'user_id' => '10',
            'saldo' => '-2677000'
          ],
          [
            'user_id' => '11',
            'saldo' => '-70000'
          ],
          [
            'user_id' => '12',
            'saldo' => '-360000'
          ],
          [
            'user_id' => '13',
            'saldo' => '-1015000'
          ],
          [
            'user_id' => '14',
            'saldo' => '-600000'
          ],
          [
            'user_id' => '15',
            'saldo' => '-157000'
          ],
          [
            'user_id' => '16',
            'saldo' => '-135000'
          ],
          [
            'user_id' => '17',
            'saldo' => '-506000'
          ],
          [
            'user_id' => '18',
            'saldo' => '-120000'
          ],
          [
            'user_id' => '19',
            'saldo' => '-1094000'
          ],
          [
            'user_id' => '20',
            'saldo' => '-260000'
          ],
          [
            'user_id' => '21',
            'saldo' => '-312000'
          ],
          [
            'user_id' => '22',
            'saldo' => '-1000000'
          ],
          [
            'user_id' => '23',
            'saldo' => '-41600'
          ],
          [
            'user_id' => '24',
            'saldo' => '-105000'
          ],
          [
            'user_id' => '25',
            'saldo' => '-2304000'
          ],
          [
            'user_id' => '26',
            'saldo' => '-659000'
          ],
          [
            'user_id' => '27',
            'saldo' => '-256000'
          ],
          [
            'user_id' => '28',
            'saldo' => '-712000'
          ],
          [
            'user_id' => '29',
            'saldo' => '-95000'
          ],
          [
            'user_id' => '30',
            'saldo' => '-2222000'
          ],
          [
            'user_id' => '31',
            'saldo' => '-1062000'
          ],
          [
            'user_id' => '32',
            'saldo' => '-300000'
          ],
          [
            'user_id' => '33',
            'saldo' => '-10000'
          ],
          [
            'user_id' => '34',
            'saldo' => '-732000'
          ],
          [
            'user_id' => '35',
            'saldo' => '-65000'
          ],
          [
            'user_id' => '36',
            'saldo' => '-380000'
          ],
          [
            'user_id' => '37',
            'saldo' => '-1560000'
          ],
          [
            'user_id' => '38',
            'saldo' => '-4933000'
          ],
          [
            'user_id' => '39',
            'saldo' => '-644000'
          ],
          [
            'user_id' => '40',
            'saldo' => '-5000000'
          ],
          [
            'user_id' => '41',
            'saldo' => '-999000'
          ],
          [
            'user_id' => '42',
            'saldo' => '-1595000'
          ],
        ]
      );



        DB::table('dompet')->insert(

          [
            [
              'user_id' => '43',
              'tanggal' => '2020-08-11',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Dimas Alfian @Rp.120000 dan ongkir =Rp.7000',
              'debet_kredit' => 'kredit',
              'nominal' => '132000',
              'saldo_berjalan' => '-132000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '43',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Lela Dahnia @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '125000',
              'saldo_berjalan' => '-257000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '43',
              'tanggal' => '2020-08-25',
              'keterangan' => 'Pembelian M.S Waras 1 pcs a.n Ahmad Rifai @Rp.150000 dan ongkir =Rp.7000',
              'debet_kredit' => 'kredit',
              'nominal' => '162000',
              'saldo_berjalan' => '-419000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '43',
              'tanggal' => '2020-08-29',
              'keterangan' => 'Pembelian MH 300gr 1 pcs a.n Bagus @Rp.42000 dan ongkir =Rp.7000',
              'debet_kredit' => 'kredit',
              'nominal' => '49000',
              'saldo_berjalan' => '-468000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '44',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 500gr 3 pcs a.n al ikhlas @Rp.70000 dan ongkir =Rp.15000',
              'debet_kredit' => 'kredit',
              'nominal' => '225000',
              'saldo_berjalan' => '-225000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '45',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 8 pcs a.n Alfah @Rp.105000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '856000',
              'saldo_berjalan' => '-856000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '46',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Alfian MT @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '100000',
              'saldo_berjalan' => '-100000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '46',
              'tanggal' => '2020-08-28',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Alfian Mt @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '200000',
              'saldo_berjalan' => '-300000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '47',
              'tanggal' => '2020-08-29',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Amir @Rp.150000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '150000',
              'saldo_berjalan' => '-150000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '6',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n andi @Rp.115000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '115000',
              'saldo_berjalan' => '-115000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '6',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian Jahe Merah 1 pcs a.n andi @Rp.40000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '40000',
              'saldo_berjalan' => '-155000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '7',
              'tanggal' => '2020-08-20',
              'keterangan' => 'Pembelian MH 1kg 5 pcs a.n Andre Lubis @Rp.105000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '525000',
              'saldo_berjalan' => '-525000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '7',
              'tanggal' => '2020-08-20',
              'keterangan' => 'Pembelian Khalas 500gr 2 pcs a.n Andre Lubis @Rp.60000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '120000',
              'saldo_berjalan' => '-645000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '8',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 300gr 1 pcs a.n Bang Bag @Rp.36000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '36000',
              'saldo_berjalan' => '-36000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '8',
              'tanggal' => '2020-08-20',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Bang Bag @Rp.50000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '50000',
              'saldo_berjalan' => '-86000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '9',
              'tanggal' => '2020-08-5',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n bang jendral @Rp.95000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '190000',
              'saldo_berjalan' => '-190000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '10',
              'tanggal' => '2020-08-5',
              'keterangan' => 'Pembelian MH 500gr 10 pcs a.n bang razak @Rp.55000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '550000',
              'saldo_berjalan' => '-550000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '10',
              'tanggal' => '2020-08-6',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n bg razak @Rp.95000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '190000',
              'saldo_berjalan' => '-740000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '10',
              'tanggal' => '2020-08-15',
              'keterangan' => 'Pembelian MH 1kg 15 pcs a.n bg Razak @Rp.95000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '1425000',
              'saldo_berjalan' => '-2165000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '10',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 500gr 10 pcs a.n Bang Razak @Rp.50000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '512000',
              'saldo_berjalan' => '-2677000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '11',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Fadhil @Rp.70000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '70000',
              'saldo_berjalan' => '-70000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '12',
              'tanggal' => '2020-08-31',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Fauzan @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '360000',
              'saldo_berjalan' => '-360000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '13',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n Galih Brader Kopi @Rp.100000 dan ongkir =Rp.15000',
              'debet_kredit' => 'kredit',
              'nominal' => '1015000',
              'saldo_berjalan' => '-1015000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '14',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Ibnu @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '240000',
              'saldo_berjalan' => '-240000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '14',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Ibnu @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '360000',
              'saldo_berjalan' => '-600000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '15',
              'tanggal' => '2020-08-5',
              'keterangan' => 'Pembelian MH 500gr 2 pcs a.n Ibu Lubis @Rp.70000 dan ongkir =Rp.17000',
              'debet_kredit' => 'kredit',
              'nominal' => '157000',
              'saldo_berjalan' => '-157000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '16',
              'tanggal' => '2020-08-31',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Ibu Yatinah @Rp.120000 dan ongkir =Rp.10000',
              'debet_kredit' => 'kredit',
              'nominal' => '135000',
              'saldo_berjalan' => '-135000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '17',
              'tanggal' => '2020-08-5',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Icha @Rp.120000 dan ongkir =Rp.13000',
              'debet_kredit' => 'kredit',
              'nominal' => '253000',
              'saldo_berjalan' => '-253000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '17',
              'tanggal' => '2020-08-6',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n icha @Rp.120000 dan ongkir =Rp.13000',
              'debet_kredit' => 'kredit',
              'nominal' => '253000',
              'saldo_berjalan' => '-506000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '18',
              'tanggal' => '2020-08-29',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Icha TDA @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '120000',
              'saldo_berjalan' => '-120000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '19',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 9 pcs a.n Saufa @Rp.120000 dan ongkir =Rp.14000',
              'debet_kredit' => 'kredit',
              'nominal' => '1094000',
              'saldo_berjalan' => '-1094000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '20',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Ilham Saputra @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '120000',
              'saldo_berjalan' => '-120000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '20',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 500gr 2 pcs a.n Ilham Saputra @Rp.70000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '140000',
              'saldo_berjalan' => '-260000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '21',
              'tanggal' => '2020-08-6',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Dedi @Rp.70000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '82000',
              'saldo_berjalan' => '-82000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '21',
              'tanggal' => '2020-08-10',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Muhammad Yunus @Rp.70000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '87000',
              'saldo_berjalan' => '-169000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '21',
              'tanggal' => '2020-08-31',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Wahyu Selian @Rp.120000 dan ongkir =Rp.18000',
              'debet_kredit' => 'kredit',
              'nominal' => '143000',
              'saldo_berjalan' => '-312000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '22',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n jemy pohan @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '1000000',
              'saldo_berjalan' => '-1000000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '23',
              'tanggal' => '2020-08-20',
              'keterangan' => 'Pembelian GS 450gr 1 pcs a.n Kak tata @Rp.41600 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '41600',
              'saldo_berjalan' => '-41600',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '24',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Laode azhar @Rp.105000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '105000',
              'saldo_berjalan' => '-105000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '25',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 8 pcs a.n M. Randy @Rp.105000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '852000',
              'saldo_berjalan' => '-852000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '25',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 500gr 4 pcs a.n M. Randy @Rp.60000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '240000',
              'saldo_berjalan' => '-1092000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '25',
              'tanggal' => '2020-08-13',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n m. randy @Rp.120000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '1212000',
              'saldo_berjalan' => '-2304000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '26',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Arif Solihin Martua @Rp.105000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '327000',
              'saldo_berjalan' => '-327000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '26',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Evi yusrina @Rp.105000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '332000',
              'saldo_berjalan' => '-659000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '27',
              'tanggal' => '2020-08-6',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n mufidatul husna @Rp.120000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '256000',
              'saldo_berjalan' => '-256000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '28',
              'tanggal' => '2020-08-12',
              'keterangan' => 'Pembelian MH 500gr 10 pcs a.n Herlina @Rp.70000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '712000',
              'saldo_berjalan' => '-712000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '29',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n NAIL COFFIE @Rp.95000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '95000',
              'saldo_berjalan' => '-95000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '30',
              'tanggal' => '2020-08-10',
              'keterangan' => 'Pembelian MH 1kg 7 pcs a.n niswa @Rp.105000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '751000',
              'saldo_berjalan' => '-751000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '30',
              'tanggal' => '2020-08-10',
              'keterangan' => 'Pembelian MH 500gr 6 pcs a.n niswa @Rp.60000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '360000',
              'saldo_berjalan' => '-1111000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '30',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 1kg 7 pcs a.n niswa @Rp.105000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '751000',
              'saldo_berjalan' => '-1862000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '30',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 500gr 6 pcs a.n niswa @Rp.60000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '360000',
              'saldo_berjalan' => '-2222000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '31',
              'tanggal' => '2020-08-18',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n nouri @Rp.105000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '1062000',
              'saldo_berjalan' => '-1062000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '32',
              'tanggal' => '2020-08-31',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Pohan @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '100000',
              'saldo_berjalan' => '-100000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '32',
              'tanggal' => '2020-08-31',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Pohan @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '200000',
              'saldo_berjalan' => '-300000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '33',
              'tanggal' => '2020-08-5',
              'keterangan' => 'Pembelian madu mesir 700gr 1 pcs a.n Risa Nurul ihsani @Rp. dan ongkir =Rp.10000',
              'debet_kredit' => 'kredit',
              'nominal' => '10000',
              'saldo_berjalan' => '-10000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '34',
              'tanggal' => '2020-08-11',
              'keterangan' => 'Pembelian MH 1kg 6 pcs a.n rstika novi @Rp.120000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '732000',
              'saldo_berjalan' => '-732000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '35',
              'tanggal' => '2020-08-10',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n bang rosadi @Rp.65000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '65000',
              'saldo_berjalan' => '-65000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '36',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Sofian @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '240000',
              'saldo_berjalan' => '-240000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '36',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 500gr 2 pcs a.n Sofian @Rp.70000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '140000',
              'saldo_berjalan' => '-380000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '37',
              'tanggal' => '2020-08-24',
              'keterangan' => 'Pembelian MH 1,2kg 10 pcs a.n Souvenir coffee @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '1200000',
              'saldo_berjalan' => '-1200000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '37',
              'tanggal' => '2020-08-26',
              'keterangan' => 'Pembelian MH 1,2kg 3 pcs a.n Souvenir coffee @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '360000',
              'saldo_berjalan' => '-1560000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Teti Hartono @Rp.50000 dan ongkir =Rp.5000',
              'debet_kredit' => 'kredit',
              'nominal' => '55000',
              'saldo_berjalan' => '-55000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 4 pcs a.n AISYAH @Rp.105000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '420000',
              'saldo_berjalan' => '-475000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Sumarni Tedjo @Rp.105000 dan ongkir =Rp.5000',
              'debet_kredit' => 'kredit',
              'nominal' => '110000',
              'saldo_berjalan' => '-585000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-7',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n teh usee @Rp.105000 dan ongkir =Rp.20000',
              'debet_kredit' => 'kredit',
              'nominal' => '1070000',
              'saldo_berjalan' => '-1655000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-11',
              'keterangan' => 'Pembelian MH 500gr 15 pcs a.n teh usee @Rp.105000 dan ongkir =Rp.20000',
              'debet_kredit' => 'kredit',
              'nominal' => '1595000',
              'saldo_berjalan' => '-3250000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-24',
              'keterangan' => 'Pembelian MH 1kg 10 pcs a.n Teh Usee @Rp.100000 dan ongkir =Rp.20000',
              'debet_kredit' => 'kredit',
              'nominal' => '1020000',
              'saldo_berjalan' => '-4270000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '38',
              'tanggal' => '2020-08-26',
              'keterangan' => 'Pembelian MH 1kg 6 pcs a.n Hissa Nahara @Rp.100000 dan ongkir =Rp.63000',
              'debet_kredit' => 'kredit',
              'nominal' => '663000',
              'saldo_berjalan' => '-4933000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '39',
              'tanggal' => '2020-08-13',
              'keterangan' => 'Pembelian MH 1kg 5 pcs a.n Trisna Pardede @Rp.120000 dan ongkir =Rp.39000',
              'debet_kredit' => 'kredit',
              'nominal' => '644000',
              'saldo_berjalan' => '-644000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '40',
              'tanggal' => '2020-08-19',
              'keterangan' => 'Pembelian MH 1kg 50 pcs a.n Ust Hamdi Langsa @Rp.100000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '5000000',
              'saldo_berjalan' => '-5000000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '41',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Van @Rp.100000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '312000',
              'saldo_berjalan' => '-312000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '41',
              'tanggal' => '2020-08-21',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Van @Rp.120000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '372000',
              'saldo_berjalan' => '-684000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '41',
              'tanggal' => '2020-08-27',
              'keterangan' => 'Pembelian MH 1kg 3 pcs a.n Van @Rp.105000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '315000',
              'saldo_berjalan' => '-999000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1ltr 1 pcs a.n Attia Ulfa @Rp.140000 dan ongkir =Rp.9000',
              'debet_kredit' => 'kredit',
              'nominal' => '149000',
              'saldo_berjalan' => '-149000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-3',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Khairunnisa Nst @Rp.120000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '256000',
              'saldo_berjalan' => '-405000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-4',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Pak Ponco @Rp.120000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '132000',
              'saldo_berjalan' => '-537000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-6',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Khairunnisa Nasution @Rp.120000 dan ongkir =Rp.16000',
              'debet_kredit' => 'kredit',
              'nominal' => '256000',
              'saldo_berjalan' => '-793000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-11',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Buk wana @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '120000',
              'saldo_berjalan' => '-913000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-15',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n Dedek @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '240000',
              'saldo_berjalan' => '-1153000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-26',
              'keterangan' => 'Pembelian MH 1kg 1 pcs a.n Silviana Iriani @Rp.120000 dan ongkir =Rp.12000',
              'debet_kredit' => 'kredit',
              'nominal' => '132000',
              'saldo_berjalan' => '-1285000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-26',
              'keterangan' => 'Pembelian MH 500gr 1 pcs a.n Silviana Iriani @Rp.70000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '70000',
              'saldo_berjalan' => '-1355000',
              'cabang_id' => '1'
            ],
            [
              'user_id' => '42',
              'tanggal' => '2020-08-27',
              'keterangan' => 'Pembelian MH 1kg 2 pcs a.n khairunnisa @Rp.120000 dan ongkir =Rp.0',
              'debet_kredit' => 'kredit',
              'nominal' => '240000',
              'saldo_berjalan' => '-1595000',
              'cabang_id' => '1'
            ],
          ]
        );


        foreach ($daftar_saldo as $saldo){

          $user = User::findOrFail($saldo['user_id']);
          $user->saldo = $saldo['saldo'];
          $user->save();

        }
}
}
