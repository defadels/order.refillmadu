@extends('layouts/pelanggan')

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
                                        {!! Form::open(['route' => ['mimin.dompet.transaksi.kurangi'],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                            @if($dompet->debet_kredit == "debet")
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
                                                              <strong>  {{$dompet->user->nama}}</strong>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <strong>    {{$dompet->tanggal->format('d F Y')}} </strong>
                                                            </div>
                                                        </div>
                                                </div>


                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Jumlah</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           Rp <strong>    {{number_format($dompet->nominal)}} </strong>
                                                            </div>
                                                        </div>
                                                </div>



                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                            @if($dompet->debet_kredit == "debet")

                                                            <span>Tujuan Kas</span>
                                                                @else

                                                                <span>Asal Kas</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-8">
                                                                <strong>{{$dompet->transaksi_kas->kas->nama}}</strong>
                                                             </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                          <strong>{{$dompet->keterangan}}</strong>

                                                          </div>
                                                    </div>
</div>
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Dibayar Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            <strong>{{$dompet->dibayar_oleh}}</strong>

                                                          </div>
                                                    </div>
</div>
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Dibayar Kepada</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$dompet->dibayar_kepada}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>

                                                    @if(isset($dompet->created_at))
                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Diposting</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$dompet->created_at->format('d-m-Y H:i:s')}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>
                                                    @endif

                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Cabang</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                          <strong>{{$dompet->cabang->nama}}</strong>

                                                       </div>
                                                          </div>
                                                    </div>





                                                    <div class="col-md-8 offset-md-4">
                                                        <a href="{{route('mimin.dompet.transaksi')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
