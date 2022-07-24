@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('judul', $judul)


@section('tombol_sudut')
<a href="{{route('mimin.kas.index')}}" class="btn-icon btn btn-primary btn-round" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-left"></i> Kambali</a>

@endsection

@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
{!! Form::open(['method'=>'get','route' => ['mimin.kas.show',$kas->id],'class'=>'form form-inline']) !!}
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
                                                <th width="1%"></th>
                                                <th>Tanggal</th>
                                                <th>keterangan</th>
                                                <th>Jumlah</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        @php
                                             $saldo = $saldo_awal;
                                        @endphp
                                        <tbody>
                                        @foreach ($daftar_transaksi as $trx)
                                            <tr>
                                              <td>
                                                @if($trx->debet_kredit == "k")
                                                  @php
                                                          $saldo -= $trx->nominal;
                                                  @endphp
                                                <div class="badge badge-lg badge-danger">
                                                    <i class="feather icon-arrow-up"></i>
                                                </div>
                                                @else
                                                  @php
                                                          $saldo += $trx->nominal;
                                                  @endphp
                                                <div class="badge badge-lg badge-success">
                                                    <i class="feather icon-arrow-down"></i>
                                                </div>
                                                @endif

                                              </td>
                                                <td>{{$trx->tanggal->format('d-m-Y')}}
                                                </td>
                                                <td>{{$trx->keterangan}}</td>


                                                <td style="text-align:right;">
                                                {{number_format($trx->nominal)}}
                                                </td>

                                                <td style="text-align:right;">
                                               {{number_format($saldo)}}
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="card-footer">

                            {{ $daftar_transaksi->appends(request()->input())->links() }}
                          </div>

                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
