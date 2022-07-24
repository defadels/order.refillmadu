@extends('layouts/contentLayoutMaster')

@section('title', $judul)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/daterangepicker/daterangepicker.css')) }}">
@endsection
@section('vendor-script')
@endsection
@section('page-script')

<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/moment.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/daterangepicker/daterangepicker.js')) }}"></script>

@endsection


@section ('tombol_sudut')
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
                                                <th>Kurir</th>
                                                <th class="text-right">Hutang</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @forelse ($daftar_pengiriman as $pengiriman)
                                            <tr>



                                                <td>
                                                  {{$pengiriman->nama}}
                                                </td>

                                                <td class="text-right">
                                                {{number_format($pengiriman->saldo)}}
                                                </td>


                                            </tr>
                                        @empty
                                        <tr>

                                        <td colspan="9">Tidak ada hutang </td>
                                        </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="card-footer">

                            {{ $daftar_pengiriman->appends(request()->input())->links() }}
                           </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->
@endsection
