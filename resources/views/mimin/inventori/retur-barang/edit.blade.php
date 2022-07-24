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

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/jquery.mask.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/numeral.min.js')) }}"></script>
@endsection
@section('page-script')

<script>
function updateForm(){


var bayar_dengan = $("input[name='bayar_dengan']:checked").val();

if (bayar_dengan == "dompet"){

  $('.daftar_kas').hide();
} else {
  $('.daftar_kas').show();
}

}

$("input[name='bayar_dengan']").change(function() {
  updateForm();
});


$('.select2').select2();

$(document).ready( function() {
  updateForm();
  // Loading remote data
$('.caripelanggan').select2({
    placeholder: 'Cari dan Pilih Pelanggan...',
    ajax: {
      url: '{{route('mimin.inventori.retur-barang.cari-pelanggan')}}',
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
                                    <h4 class="card-title">Formulir Retur Barang</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! Form::model($retur_barang, ['method' => 'PUT','route' => ['mimin.inventori.retur-barang.update', $retur_barang->id],'class'=>'form form-horizontal']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Pelanggan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('pelanggan_id',$daftar_pelanggan,old('pelanggan_id'), ['class' => 'form-control caripelanggan']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Gudang Tujuan</span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{ Form::select('gudang_id',$daftar_gudang,old('gudang_id'), ['class' => 'form-control']) }}
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

                                                                {{ Form::text('tanggal',old('tanggal')?old('tanggal'):($retur_barang->tanggal?$retur_barang->tanggal->format('d-m-Y'):""), ['class' => 'form-control tanggal','required']) }}


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
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Nominal yang dikembalikan</span>
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
                                                                <span>Bayar dengan</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                            <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="bayar_dengan" {{$retur_barang->bayar_dengan =='dompet'?'checked':''}} value="dompet">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Dompet
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="bayar_dengan"{{$retur_barang->bayar_dengan =='cash'?'checked':''}}  value="cash">

                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Cash
                                                                    </div>
                                                                </fieldset>
                                                            </li>

                                                        </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 daftar_kas">
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <span>Kas Asal</span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                {{ Form::select('kas_id',$daftar_kas,old('kas_id')?old('kas_id'):$retur_barang->kas_asal_id, ['class' => 'form-control']) }}
                                                            </div>
                                                        </div>
                                                    </div>


                                <div class="table-responsive col-12">
                                    <table class="table table-dark mb-2">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th class="text-right" width="30%">Jumlah yang Dikembalikan</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($retur_barang->daftar_detil as $detil)
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
                                                        <a href="{{route('mimin.inventori.retur-barang.index')}}" class="btn btn-outline-warning mr-1 mb-1">Kembali</a>

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
