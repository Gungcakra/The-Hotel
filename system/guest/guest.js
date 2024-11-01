// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarGuest.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarGuest").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarGuest:", error));
//   if (document.readyState === "complete") {
//     daftarGuest();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarGuest(); 
});


function daftarGuest() {
  $.ajax({
    url: "daftarGuest.php",
    type: "post",
    data: {
      flag: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarGuest").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesGuest() {
  const formGuest = document.getElementById("formGuest");
  const dataForm = new FormData(formGuest);

  $("#guestModal").modal("hide");

  $("#guestModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesGuest.php",
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
        daftarGuest();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteGuest(id) {
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
        url: "prosesGuest.php",
        type: "post",
        data: {
          guestId: id,
          flag: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarGuest();
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

function loadPage(pageNumber) {
  const limit = $('#limit').val();
  $.ajax({
      type: "POST",
      url: "daftarGuest.php",
      data: {
          flag: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarGuest').html(data);
      }
  });
}

function editGuestModal(guest) {
  document.getElementById('guestId').value = guest.guestId;
  document.getElementById('name').value = guest.name;
  document.getElementById('phoneNumber').value = guest.phoneNumber;
  document.getElementById('email').value = guest.email;
  document.getElementById('address').value = guest.address;

  document.getElementById('flag').value = 'update';
}





function cariDaftarGuest() {
	const searchQuery = $("#searchQuery").val();
  const limit = $("#limit").val();
	if (searchQuery || limit) {
		$.ajax({
			url: "daftarGuest.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				limit: limit,
				flag: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarGuest").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarGuest.php",
			type: "post",
			data: {
				flag: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarGuest").html(data);
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
