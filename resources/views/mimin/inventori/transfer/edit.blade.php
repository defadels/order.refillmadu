@extends('layouts/contentLayoutMaster')

@section('title', $judul)


@section ('tombol_sudut')


@endsection
@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
@endsection



@section('page-script')

<script>

$(document).ready( function() {
    // Format Date Picker
    $('.tanggal').pickadate({
        selectYears: true,
        selectMonths: true,
        format: 'dd-mm-yyyy'
    });

});

</script>

@endsection

@section('content')

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulir Transfer</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($transfer, ['method' => 'PUT','route' => ['mimin.inventori.transfer.update', $transfer->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Gudang Asal</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('gudang_asal_id',$daftar_gudang,old('gudang_asal_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Gudang Tujuan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('gudang_tujuan_id',$daftar_gudang,old('gudang_tujuan_id'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Tanggal</span>
                                                            </div>
                                                            <div class="col-md-4 input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="feather icon-calendar"></i></span>
                                                                </div>
                                                                {{ Form::text('tanggal',old('tanggal')?old('tanggal'):($transfer->tanggal?$transfer->tanggal->format('d-m-Y'):""), ['class' => 'form-control tanggal','required']) }}


                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{ Form::text('keterangan',old('keterangan'), ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                <div class="table-responsive col-12">
                                    <table class="table table-dark mb-2">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th class="text-right" width="30%">Jumlah yang Masuk</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($transfer->daftar_detil as $detil)
                                            <tr>
                                                <th scope="row">{{$detil->produk->nama}}</th>
                                                <td class="text-right">{!!Form::number('kuantitas[]',$detil->kuantitas, ['class' => 'form-control text-right'])!!}</td>
                                                <td>{{$detil->produk->satuan}}</td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                    </table>
                                </div>


                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Simpan</button>
                                                        <a href="{{route('mimin.inventori.transfer.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
