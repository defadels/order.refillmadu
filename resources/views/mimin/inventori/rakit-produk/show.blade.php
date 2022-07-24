@extends('layouts/contentLayoutMaster')

@section('title', $judul)


@section ('tombol_sudut')


@endsection
@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                             <div class="form-body">
                                                <div class="row">

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Produk yang Dibuat</span>
                                                            </div>
                                                            <div class="col-6 col-md-8">
                                                                <strong>{{$rakit_produk->produk->nama}}</strong>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Stok yang dihasilkan</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ number_format($rakit_produk->stok_hasil) }} {{$rakit_produk->produk->satuan}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Gudang</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->gudang->nama }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Tanggal Transaksi</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                            @if($rakit_produk->tanggal)
                                                                {{ $rakit_produk->tanggal->format('d-m-Y') }}
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->keterangan }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Dibuat oleh</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->dibuat_oleh->nama }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Dibuat pada</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->created_at->format('d-m-Y H:i:s') }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Diposting oleh</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->diposting_oleh->nama }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-6 col-md-4">
                                                                <span>Diposting pada</span>
                                                            </div>
                                                            <div class="col-6 col-md-4">
                                                                {{ $rakit_produk->posted_at->format('d-m-Y H:i:s') }}
                                                            </div>
                                                        </div>
                                                    </div>


                                <div class="table-responsive col-12">
                                    <table class="table table-dark mb-2">
                                        <thead>
                                            <tr>
                                                <th>Nama Bahan</th>
                                                <th class="text-right" width="30%">Kuantitas</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($rakit_produk->daftar_detil as $detil)
                                            <tr>
                                                <th scope="row">{{$detil->bahan->nama}}</th>
                                                <td class="text-right">{{number_format($detil->kuantitas)}}</td>
                                                <td>{{$detil->bahan->satuan}}</td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                    </table>
                                </div>


                                                    <div class="col-md-8 offset-md-4">
                                                        <a href="{{route('mimin.inventori.rakit-produk.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

@endsection
