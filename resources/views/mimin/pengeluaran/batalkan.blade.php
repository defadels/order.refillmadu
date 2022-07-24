@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)

@section('tombol_sudut')
<div class="dropdown">
        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{route('mimin.pengeluaran.batalkan',$pengeluaran->id)}}"><i class="feather icon-trash"></i>Batalkan</a>
        </div>
</div>
@endsection

@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Apakah kamu yakin untuk membatalkan transaksi pengeluaran ini?</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::open(['method'=>'delete','route' => ['mimin.pengeluaran.destroy',$pengeluaran->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">



                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            <strong>             {{$pengeluaran->tanggal->format('d F Y')}}</strong>
                                                             </div>
                                                        </div>
                                                    </div>







                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Jumlah</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> Rp.{{number_format($pengeluaran->nominal)}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kategori</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->kategori->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dari Kas</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->transaksi_kas->kas->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dari Dana</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->transaksi_dana->dana->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>
                                            @if($pengeluaran->transaksi_dana->dana->kegiatan !="[]")
                                                @if($pengeluaran->rab!="[]")
                                                  <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>RAB</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->rab->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>
                                                  @endif
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kegiatan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->transaksi_dana->dana->kegiatan->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Program</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                           <strong> {{$pengeluaran->transaksi_dana->dana->kegiatan->program->nama}} </strong>

                                                             </div>
                                                        </div>
                                                    </div>
                                            @endif


                                            <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->keterangan}} </strong>
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->dibayar_oleh}} </strong>
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dibayar Kepada</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->dibayar_kepada}} </strong>
                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Diinput Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->diinput_oleh->nama }} </strong>
                                                             </div>
                                                        </div>
                                                    </div>




                                            <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nomor Invoice</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->nomor_invoice}} </strong>
                                                             </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nomor Transaksi</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                         <strong>   {{$pengeluaran->nomor_transaksi}} </strong>
                                                             </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" name="batalkan" value="1" class="btn btn-danger mr-1 mb-1"><i class="feather icon-x"></i> Batalkan</button>

                                                        <a href="{{route('mimin.pengeluaran.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
