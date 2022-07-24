
<table style="width:100%">
  <tr>

  <td style="width:50%"><b style="font-size:15pt;">REFILLMADU</b></td>
  <td style="width:50%; text-align:right;"><b>INVOICE</b></td>

  </tr>

  <tr>

  <td colspan="2" style="text-align:center;">
  <hr>
  Nomor Invoice : {{$pesanan->no_invoice?$pesanan->no_invoice:$pesanan->id}}</td>
  </tr>


</table>
<hr>
<table style="width:100%">
<tr>
  <td style="width:20%; vertical-align:top;">Kepada</td>
  <td>

  {{$pesanan->dikirim_kepada}} <br>
  {{$pesanan->nomor_hp_tujuan}} <br>
  {{$pesanan->alamat_tujuan}}
<hr>
  </td>
  </tr>
  <tr>
  <td style="width:20%; vertical-align:top;">Dari</td>
  <td>
  {{$pesanan->dikirim_oleh}} <br>
  {{$pesanan->nomor_hp_pengirim}}

  </td>
  </tr>
</table>
<hr>
<table style="width:100%">
@foreach($pesanan->daftar_detil as $detil)
<tr>
                <td  style="width:50%">
                  {{$detil->produk->nama}}
                </td>
                 <td  style="width:10%">
                   {{$detil->kuantitas}}

                 </td >
                 <td  style="width:20%">
                   {{$detil->produk->satuan}}
                 </td>
                 <td  style="width:20%">
                   {{number_format($detil->harga)}}
                 </td>
                 <td  style="width:20%">
                   {{number_format($detil->harga*$detil->kuantitas)}}
                 </td>
</tr>
@endforeach
<tr>
  <td colspan="5"><hr></td>
</tr>
<tr>
                <td colspan="4">
                  Total
                </td>

                 <td >
                   {{number_format($pesanan->nominal_pembelian)}}
                 </td>
</tr>
<tr>
  <td colspan="5"><hr></td>
</tr>
<tr>
                <td colspan="4">
                  Biaya Tambahan
                </td>

                 <td >
                   {{number_format($pesanan->biaya_tambahan)}}
                 </td>
</tr>
<tr>
                <td colspan="4">
                 Ongkos Kurir
                </td>

                 <td >
                   {{number_format($pesanan->ongkos_kurir)}}
                 </td>
</tr>
<tr>
                <td colspan="4">
                  Ongkos Kirim
                </td>

                 <td >
                   {{number_format($pesanan->ongkos_kirim)}}
                 </td>
</tr>
<tr>
  <td colspan="5"><hr></td>
</tr>
<tr>
                <td colspan="4">
                  Grand Total
                </td>

                 <td >
                   {{number_format($pesanan->grand_total)}}
                 </td>
</tr>

<tr>
                <td colspan="4">
                  Sudah Bayar
                </td>

                 <td >
                   {{number_format($pesanan->nominal_yang_dibayar)}}
                 </td>
</tr>
<tr>
  <td colspan="5"><hr></td>
</tr>
</table>
