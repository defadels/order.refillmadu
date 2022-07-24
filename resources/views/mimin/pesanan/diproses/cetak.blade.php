
<table style="width:100%">
  <tr>

  <td style="width:50%"><b style="font-size:15pt;">REFILLMADU</b></td>
  <td style="width:50%; text-align:right;"><b>LABEL PENGIRIMAN</b></td>

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

                 <td  style="width:10%">
                   {{$detil->kuantitas}}

                 </td >
                 <td  style="width:20%">
                   {{$detil->produk->satuan}}

                 </td>
                 <td  style="width:50%">
                  {{$detil->produk->nama}}
                </td>
</tr>
@endforeach
</table>
