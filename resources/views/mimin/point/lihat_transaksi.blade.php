@extends('layouts/contentLayoutMaster')

@section('title', $judul)
@section('judul', $judul)

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
                                        {!! Form::open(['route' => ['mimin.point.transaksi.kurangi'],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                            @if($point->debet_kredit == "debet")
                                            <div class="badge badge-lg badge-success">
                                                    <i class="feather icon-arrow-down"></i>
                                                    Transaksi Masuk
                                            </div>
                                            @else
                                            <div class="badge badge-lg badge-danger">
                                                    <i class="feather icon-arrow-up"></i>
                                                    Transaksi Keluar
                                            </div>

                                            @endif
</div></div></div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                              <strong>  {{$point->user->nama}}</strong>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <strong>    {{$point->tanggal->format('d F Y')}} </strong>
                                                            </div>
                                                        </div>
                                                </div>


                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Jumlah</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           Rp <strong>    {{number_format($point->nominal)}} </strong>
                                                            </div>
                                                        </div>
                                                </div>





                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                          <strong>{{$point->keterangan}}</strong>

                                                          </div>
                                                    </div>
</div>
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Nomor Pesanan</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            <strong>{{$point->pesanan_id}}</strong>

                                                          </div>
                                                    </div>
</div>

                                                    @if(isset($point->created_at))
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Diposting</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$point->created_at->format('d-m-Y H:i:s')}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>
                                                    @endif






                                                    <div class="col-md-8 offset-md-4">
                                                        <a href="{{route('mimin.point.transaksi')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>

@endsection
