// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarReservation.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarEmployee").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarEmployee:", error));
//   if (document.readyState === "complete") {
//     daftarReservation();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarReservation(); 
});



function daftarReservation() {
  $.ajax({
    url: "daftarReservation.php",
    type: "post",
    data: {
      flag: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarReservation").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesReservation() {
  const formReservation = document.getElementById("formReservation");
  const dataForm = new FormData(formReservation);

  $("#reservationModal").modal("hide");

  $("#reservationModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesReservation.php",
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
        daftarReservation();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteReservation(id) {
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
        url: "prosesReservation.php",
        type: "post",
        data: {
          reservationId: id,
          flag: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarReservation();
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
      url: "daftarReservation.php",
      data: {
          flag: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarReservation').html(data);
      }
  });
}

function editReservationModal(employee) {
  document.getElementById('employeeId').value = employee.employeeId;
  document.getElementById('name').value = employee.name;
  const roleSelect = document.getElementById('roleId');
  roleSelect.value = employee.roleId;
  document.getElementById('phoneNumber').value = employee.phoneNumber;
  document.getElementById('email').value = employee.email;
  document.getElementById('address').value = employee.address;

  document.getElementById('flag').value = 'update';
}





function cariDaftarReservation() {
	const searchQuery = $("#searchQuery").val();
  const limit = $("#limit").val();
  const rentang = $("#rentang").val();
	if (searchQuery || limit || rentang) {
		$.ajax({
			url: "daftarReservation.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				limit: limit,
				rentang: rentang,
				flag: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarReservation").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarReservation.php",
			type: "post",
			data: {
				flag: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarReservation").html(data);
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
