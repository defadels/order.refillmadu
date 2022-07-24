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
                                        {!! Form::model($struktur,['method'=>'patch','route' => ['mimin.inventori.produk.struktur.update',$produk->id,$struktur->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Bahan :</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $struktur->bahan->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_bahan',old('qty_bahan'), ['class' => 'form-control']) }}
                                                            </div>
                                                            <div class="col-4">
                                                                <span>{{$struktur->bahan->satuan}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Akan Menghasilkan:</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $struktur->produk->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                        <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_produk',old('qty_produk'), ['class' => 'form-control']) }}
                                                            </div>
                                                            <div class="col-4">
                                                                {{$produk->satuan}}
                                                            </div>
                                                        </div>
                                                    </div>






                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('mimin.inventori.produk.show',$produk->id)}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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

<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($struktur,['method'=>'patch','route' => ['mimin.inventori.produk.struktur.update',$produk->id,$struktur->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Untuk Menghasilkan:</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $struktur->produk->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                        <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_produk',old('qty_produk'), ['class' => 'form-control']) }}
                                                            </div>
                                                            <div class="col-4">
                                                                {{$produk->satuan}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Dibutuhkan Bahan :</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $struktur->bahan->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_bahan',old('qty_bahan'), ['class' => 'form-control']) }}
                                                            </div>
                                                            <div class="col-4">
                                                                <span>{{$struktur->bahan->satuan}}</span>
                                                            </div>
                                                        </div>
                                                    </div>







                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('mimin.inventori.produk.show',$produk->id)}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
