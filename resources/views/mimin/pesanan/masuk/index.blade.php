@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('vendor-style')
@endsection
@section('vendor-script')
@endsection
@section('page-script')
@endsection


@section ('tombol_sudut')
@can('pesanan.masuk.tambah')
<a href="{{route('mimin.pesanan.masuk.create')}}" class="btn-icon btn btn-outline-warning btn-round waves-effect waves-light" aria-haspopup="true" aria-expanded="false"><i class="feather icon-plus"></i> Tambah</a>
@endcan
@endsection
@section('content')
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                 </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th>Pelanggan</th>
                                                <th>Total Pesanan</th>
                                                <th>Pembayaran</th>
                                                <th>Pengiriman</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($daftar_pesanan as $pesanan)
                                            <tr>

                                                <td>
                                                    {{$pesanan->created_at->format('d-m-Y')}}
                                                </td>
                                                <td>
                                                    {{Str::limit($pesanan->daftar_detil->pluck('produk.nama')->implode(", "),30)}}
                                                </td>

                                                <td>
                                                  {{$pesanan->pelanggan->nama}}
                                                </td>

                                                <td class="text-right">
                                                  {{number_format($pesanan->total_pembelian)}}
                                                </td>
                                                <td>
                                                  {{$pesanan->metode_pembayaran->nama}}
                                                </td>
                                                <td class="text-center">

                                                {{$pesanan->metode_pengiriman->nama}}

                                                </td>

                                            <td  style="text-align:center;">
@can('pesanan.masuk.edit')
                                            <a class="btn btn-icon btn-outline-warning btn-sm waves-effect waves-light" href="{{ route('mimin.pesanan.masuk.edit',$pesanan->id) }}"><i class="feather icon-edit"></i></a>
@endcan
                                            <a class="btn btn-icon btn-outline-danger btn-sm waves-effect waves-light" href="{{ route('mimin.pesanan.masuk.show',$pesanan->id) }}"><i class="feather icon-check"></i></a>

                                              </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_pesanan->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
