@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)


@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>

<script src="{{ asset(mix('js/scripts/jquery.mask.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/numeral.min.js')) }}"></script>

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
    $('.nominal').mask("000.000.000.000.000", {reverse: true});
    $("form").submit(function (event) {
            $('.nominal').unmask();
    });

    numeral.register('locale', 'id', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    abbreviations: {
        thousand: 'rb',
        million: 'jt',
        billion: 'M',
        trillion: 'T'
    },
    ordinal : function (number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: 'Rp'
    }
});

// switch between locales
numeral.locale('id');
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
                                    <h4 class="card-title">Formulir untuk Menambah Kas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::open(['route' => ['mimin.kas.transfer.store'],'class'=>'form form-horizontal','enctype'=>'multipart/form-data']) !!}
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
                                                                {{ Form::text('tanggal',old('tanggal'), ['class' => 'form-control tanggal','required']) }}

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Dari</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            @if($kas_asal)
                                                              {{ Form::select('asal_id',$daftar_asal,$kas_asal->id, ['class' => 'form-control','disabled']) }}
                                                              {{ Form::hidden('asal_id',$kas_asal->id) }}

                                                            @else
                                                            {{ Form::select('asal_id',$daftar_asal,old('asal_id'), ['class' => 'form-control','required']) }}
                                                            @endif
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Ke</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::select('tujuan_id',$daftar_tujuan,old('tujuan_id'), ['class' => 'form-control','required']) }}

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
                                                            {{ Form::text('nominal',old('nominal'), ['class' => 'form-control nominal','required']) }}
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('keterangan',old('keterangan'), ['class' => 'form-control','required']) }}

                                                             </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Oleh</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            {{ Form::text('oleh',old('oleh'), ['class' => 'form-control','required']) }}

                                                             </div>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                                                        <a href="{{route('mimin.kas.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
