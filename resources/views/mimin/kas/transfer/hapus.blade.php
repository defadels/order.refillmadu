@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)



@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Yakin mau menghapus Transaksi ini??</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($transfer_kas,['method'=>'delete','route' => ['mimin.kas.transfer.destroy',$transfer_kas->id],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}
                                            <div class="form-body">
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
                                                                </div>
                                                                {{ Form::text('tanggal',$transfer_kas->debet->tanggal->format("d F Y"), ['class' => 'form-control','disabled']) }}

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dari</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::select('asal_id',$daftar,$transfer_kas->kredit->kas->id, ['class' => 'form-control','disabled']) }}

                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ke</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('tujuan_id',$daftar,$transfer_kas->debet->kas->id, ['class' => 'form-control','disabled']) }}

                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Jumlah</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">
                                                            <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                                </div>
                                                            {{ Form::text('nominal',number_format($transfer_kas->debet->nominal), ['class' => 'form-control nominal','disabled']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('keterangan',$transfer_kas->debet->keterangan, ['class' => 'form-control','disabled']) }}

                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('oleh',old('oleh'), ['class' => 'form-control','disabled']) }}

                                                             </div>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-danger mr-1 mb-1">Hapus</button>
                                                        <a href="{{route('mimin.kas.transfer.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
