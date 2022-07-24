@extends('layouts/contentLayoutMaster')

@section('title', $judul)
@section('judul', $judul)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

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

// Loading remote data
$('.caripelanggan').select2({
    placeholder: 'Cari dan Pilih Pelanggan...',
    ajax: {
      url: '{{route('mimin.point.pelanggan.cari')}}',
      dataType: 'json',
      delay: 250,
      data: function (params) {
          return {
            cari: params.term,
            page: params.page || 1
          };
        },
      processResults: function (data) {
        return {
          results:  $.map(data.results, function (item) {
            return {
              text: item.nama +" ["+item.email+"]",
              id: item.id
            }
          }),
          pagination: data.pagination
        };
      },
      cache: true
    }
  });


});



$('.select2').select2();
// Loading remote data

</script>

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


                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::open(['route' => ['mimin.point.transaksi.kurangi'],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{  Form::select('pelanggan_id', $daftar_pelanggan, old('pelanggan_id'), ['class' => 'form-control caripelanggan']) }}
                                                             </div>
                                                        </div>
                                                    </div>
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
                                                                <span>Jumlah</span>
                                                            </div>
                                                            <div class="col-md-8 input-group">

                                                            {{ Form::text('nominal',old('nominal'), ['class' => 'form-control nominal','required']) }}
                                                            <div class="input-group-append">
                                                                    <span class="input-group-text" id="basic-addon1">poin</span>
                                                                </div>
                                                             </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                                <span>Keterangan</span>
                                                            </div>
                                                            <div class="col-md-8">

                                                            {{ Form::text('keterangan',old('keterangan'), ['class' => 'form-control', 'placeholder'=>'Keterangan']) }}
</div>
                                                          </div>
                                                    </div>


                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-danger mr-1 mb-1"><i class="feather icon-minus"></i> Kurangi</button>
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
