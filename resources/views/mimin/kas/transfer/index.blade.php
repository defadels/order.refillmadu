@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)


@section('tombol_sudut')
<a href="{{route('mimin.kas.transfer.create')}}" class="btn-icon btn btn-primary btn-round" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i>Tambah</a>

@endsection

@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                {!! Form::open(['method'=>'get','route' => ['mimin.kas.transfer.index'],'class'=>'form form-inline']) !!}
&nbsp;
{!!Form::select('bulan',$daftar_bulan,$bulan,['class'=>'form-control'])!!}
&nbsp;
{!!Form::select('tahun',$daftar_tahun,$tahun,['class'=>'form-control'])!!}
&nbsp;
  <button type="submit" class="btn btn-primary">Lihat</button>

{!!Form::close()!!}
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Dari</th>
                                                <th>Ke</th>
                                                <th>Jumlah</th>
                                                <th>Oleh</th>
                                                @can('transferkas-delete')
                                                <th></th>
                                                @endcan
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_transfer_kas as $trx)
                                            <tr>
                                                <td>{{$trx->kredit->tanggal->format('d-m-Y')}}
                                                </td>
                                                <td>{{$trx->kredit->kas->nama}}</td>
                                                <td>{{$trx->debet->kas->nama}}</td>
                                                <td style="text-align:right">{{number_format($trx->kredit->nominal)}}</td>
                                                <td>{{$trx->oleh}}</td>
                                                <td style="text-align:center;">
                                                <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light" href="{{route('mimin.kas.transfer.edit',$trx->id)}}"><i class="feather icon-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="card-footer">

                            {{ $daftar_transfer_kas->appends(request()->input())->links() }}
                          </div>

                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
