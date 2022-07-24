@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section ('tombol_sudut')
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
<script>

$('.select2').select2();

</script>
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
                                        {!! Form::open(['route' => ['mimin.inventori.produk.struktur.store',$produk->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">


                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Untuk Menghasilkan:</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $produk->nama }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                        <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_produk',old('qty_produk')?old('qty_produk'):1, ['class' => 'form-control']) }}
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
                                                              <strong>  {{ Form::select('bahan_id',$daftar_bahan,old('bahan_id'), ['class' => 'form-control select2']) }}</strong>
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
                                                                <span>satuan</span>
                                                            </div>
                                                        </div>
                                                    </div>





                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
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
                                        {!! Form::open(['route' => ['mimin.inventori.produk.struktur.store',$produk->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Bahan :</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ Form::select('bahan_id',$daftar_bahan,old('bahan_id'), ['class' => 'form-control select2']) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Sebanyak :</span>
                                                            </div>
                                                            <div class="col-4">
                                                                {{ Form::number('qty_bahan',old('qty_bahan')?old('qty_bahan'):1, ['class' => 'form-control']) }}
                                                            </div>
                                                            <div class="col-4">
                                                                <span>satuan</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-4 text-right">
                                                                <span>Dapat Menghasilkan:</span>
                                                            </div>
                                                            <div class="col-8">
                                                              <strong>  {{ $produk->nama }}</strong>
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
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
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
