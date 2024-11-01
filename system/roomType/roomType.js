// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarRoomType.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarRoom").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarRoom:", error));
//   if (document.readyState === "complete") {
//     daftarRoomType();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarRoomType(); 
});


function daftarRoomType() {
  $.ajax({
    url: "daftarRoomType.php",
    type: "post",
    data: {
      flag: "daftar"
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

  // Hide the modal first
  $("#roomTypeModal").modal("hide");

  // Wait for the modal to completely hide before sending the AJAX request
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
        // $("#formAddRoom")[0].reset(); // Uncomment if you want to reset the form
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
          flag: "delete",
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
  document.getElementById('flag').value = 'update';
}


// Function to load the specified page for pagination
function loadPage(pageNumber) {
  // Get the limit value from the #limit element
  const limit = $('#limit').val();

  $.ajax({
      type: "POST",
      url: "daftarRoomType.php",
      data: {
          flag: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          roomStatus: $('#roomStatus').val(),
          limit: limit // Add the limit to the data being sent
      },
      success: function (data) {
          $('#daftarRoomType').html(data);
      }
  });
}




// function updateRoomType(roomId) {
//   // $("#editRoomTypeModal" + roomId).modal("hide");

//   const formRoom = document.getElementById("formEditRoom");
//   const dataForm = new FormData(formRoom);

//   const roomNumber = $("#roomNumber" + roomId).val();
//   const roomTypeId = $("#roomTypeId" + roomId).val();
//   const status = $("#status" + roomId).val();
//   dataForm.append("roomId", roomId);
//   dataForm.append("roomNumber", roomNumber);
//   dataForm.append("roomTypeId", roomTypeId);
//   dataForm.append("status", status);
//   dataForm.append("flag", "update");
  
//   $.ajax({
//     url: "prosesRoomType.php",
//     type: "post",
//     data: dataForm,
//     processData: false,
//     contentType: false,
//     dataType: "json",
//     beforeSend: function () {},
//     success: function (data) {
//       console.log(data);
//       const { status, pesan } = data;
//       notifikasi(status, pesan);
//       daftarRoomType();
//       $("#editRoomTypeModal").modal("hide");

//     },
//     error: function (jqXHR, textStatus, errorThrown) {
//       console.error("Error:", textStatus, errorThrown);
//     },
//   });
// }

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
				flag: "cari",
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
				flag: "daftar",
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
