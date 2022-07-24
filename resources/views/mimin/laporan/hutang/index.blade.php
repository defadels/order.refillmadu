@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('content')
<div class="row">
                    <div class="col-md-6 col-sm-12">

                    <a href="{{route('mimin.laporan.hutang.dompet')}}">
                            <div class="card border-warning text-center bg-transparent">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 alt="element 05" width="100" class="mb-1"><i  style="font-size: 3rem;"  class="fa fa-money"></i></h4>
                                        <h4 class="card-title">Dompet Pelanggan</h4>
                                        <p class="card-text">Hutang kepada pelanggan / Kurir Toko</p>

                                    </div>
                                </div>
                            </div>
                            </a>
                    </div>
                    <div class="col-md-6 col-sm-12">


                    <a href="{{route('mimin.laporan.hutang.pengiriman')}}">
                            <div class="card border-warning text-center bg-transparent">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 alt="element 05" width="100" class="mb-1"><i style="font-size: 3rem;" class="feather icon-mail"></i></h4>
                                        <h4 class="card-title">Hutang Jasa Pengiriman</h4>
                                        <p class="card-text">Hutang kepada jasa pengiriman</p>
                                    </div>
                                </div>
                            </div>
</a>
                    </div>
</div>
<!-- Bordered table end -->

@endsection
