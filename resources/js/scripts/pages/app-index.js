
$('.confirm-delete').on('click', function (e) {

    Swal.fire({
      title: 'Yakin ingin menghapus '+ e.target.getAttribute('data-nama')+'?',
      text: "Setelah dihapus akan hilang selamanya!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      confirmButtonClass: 'btn btn-danger',
      cancelButtonClass: 'btn btn-primary ml-1',
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {

        var token =  $('meta[name="csrf-token"]').attr('content');
        var id=3;
        $.ajax({
            url: e.target.getAttribute('data-aksi'),
            type: 'DELETE',
            data: {
                 "_token": token,
            },
            success: function(result) {
                // Do something with the result
                Swal.fire(
                  {
                    type: result.success?"success":"warning",
                    title: result.judul,
                    text:  result.pesan,
                    confirmButtonClass: 'btn btn-success',
                  }
                ).then(function (a) {
                   if (result.success){
                    location.href = result.redirect;
                   }
                });
            }
        });

      }

    });
  });
