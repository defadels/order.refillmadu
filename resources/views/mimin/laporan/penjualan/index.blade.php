@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('content')
<div class="row">
                    <div class="col-md-6 col-sm-12">

                    <a href="{{route('mimin.laporan.penjualan.laporan1')}}">
                            <div class="card border-warning text-center bg-transparent">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 alt="element 05" width="100" class="mb-1"><i  style="font-size: 3rem;"  class="feather icon-bar-chart-2"></i></h4>
                                        <h4 class="card-title">Penjualan Produk</h4>
                                        <p class="card-text">Laporan penjualan produk per item</p>

                                    </div>
                                </div>
                            </div>
                            </a>
                    </div>
                    <div class="col-md-6 col-sm-12">


                    <a href="{{route('mimin.laporan.penjualan.laporan2')}}">
                            <div class="card border-warning text-center bg-transparent">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 alt="element 05" width="100" class="mb-1"><i style="font-size: 3rem;" class="feather icon-bar-chart"></i></h4>
                                        <h4 class="card-title">Penjualan Produk Per Tanggal</h4>
                                        <p class="card-text">Laporan penjualan produk per item per tanggal</p>
                                    </div>
                                </div>
                            </div>
</a>
                    </div>
                    <div class="col-md-6 col-sm-12">


                        <a href="{{route('mimin.laporan.penjualan.laporan3')}}">
                            <div class="card border-warning text-center bg-transparent">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 alt="element 05" width="100" class="mb-1"><i style="font-size: 3rem;"  class="feather icon-shopping-cart"></i></h4>
                                        <h4 class="card-title">Transaksi Penjualan</h4>
                                        <p class="card-text">Laporan penjualan per transaksi</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-12">


<a href="{{route('mimin.laporan.penjualan.laporan4')}}">
    <div class="card border-warning text-center bg-transparent">
        <div class="card-content">
            <div class="card-body">
                <h4 alt="element 05" width="100" class="mb-1"><i style="font-size: 3rem;"  class="feather icon-users"></i></h4>
                <h4 class="card-title">Penjualan Pelanggan</h4>
                <p class="card-text">Laporan penjualan per pelanggan</p>
            </div>
        </div>
    </div>
</a>
</div>

<div class="col-md-6 col-sm-12">


<a href="{{route('mimin.laporan.penjualan.laporan5')}}">
    <div class="card border-warning text-center bg-transparent">
        <div class="card-content">
            <div class="card-body">
                <h4 alt="element 05" width="100" class="mb-1"><i style="font-size: 3rem;"  class="feather icon-users"></i></h4>
                <h4 class="card-title">Penjualan Pelanggan</h4>
                <p class="card-text">Laporan Per 3 bulan</p>
            </div>
        </div>
    </div>
</a>
</div>
</div>
<!-- Bordered table end -->

@endsection
