document.addEventListener("DOMContentLoaded", function (event) {
  daftarRoomType(); 
});


function daftarRoomType() {
  $.ajax({
    url: "daftarRoomType.php",
    type: "post",
    data: {
      flagRoomType: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarRoomType").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesRoomType() {
  const formRoomType = document.getElementById("formRoomType");
  const dataForm = new FormData(formRoomType);

  $("#roomTypeModal").modal("hide");

  $("#roomTypeModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesRoomType.php",
      type: "post",
      enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      data: dataForm,
      dataType: "json",
      success: function (data) {
        console.log(data);
        const { status, pesan } = data;
        notifikasi(status, pesan);
        daftarRoomType();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteRoomType(id) {
  Swal.fire({
    title: "Are You Sure?",
    text: "Setelah dibatalkan, proses tidak dapat diulangi!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes!",
    cancelButtonText: "Cancel!",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: "prosesRoomType.php",
        type: "post",
        data: {
          roomTypeId: id,
          flagRoomType: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarRoomType();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error:", textStatus, errorThrown);
          Swal.fire("Error", "Something went wrong!", "error");
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Canceled", "Proses Canceled!", "error");
    }
  });
}
function editRoomTypeModal(roomType) {
  document.getElementById('roomTypeId').value = roomType.roomTypeId;
  document.getElementById('typeName').value = roomType.typeName;
  document.getElementById('price').value = roomType.price;
  document.getElementById('flagRoomType').value = 'update';
}


function loadPage(pageNumber) {
  const limit = $('#limit').val();

  $.ajax({
      type: "POST",
      url: "daftarRoomType.php",
      data: {
          flagRoomType: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          roomStatus: $('#roomStatus').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarRoomType').html(data);
      }
  });
}





function cariDaftarRoomType() {
	const searchQuery = $("#searchQuery").val();
  const limit = $("#limit").val();
	if (searchQuery || limit) {
		$.ajax({
			url: "daftarRoomType.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				limit: limit,
				flagRoomType: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRoomType").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarRoomType.php",
			type: "post",
			data: {
				flagRoomType: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRoomType").html(data);
			},
		});
	}
}
function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
