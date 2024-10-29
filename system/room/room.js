// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarRoom.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarRoom").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarRoom:", error));
//   if (document.readyState === "complete") {
//     daftarRoom();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarRoom(); 
});


function daftarRoom() {
  $.ajax({
    url: "daftarRoom.php",
    type: "post",
    data: {
      flag: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarRoom").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesRoom() {
  const formRoom = document.getElementById("formRoom");
  const dataForm = new FormData(formRoom);

  // Hide the modal first
  $("#roomModal").modal("hide");

  // Wait for the modal to completely hide before sending the AJAX request
  $("#roomModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesRoom.php",
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
        daftarRoom();
        // $("#formAddRoom")[0].reset(); // Uncomment if you want to reset the form
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteRoom(id) {
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
        url: "prosesRoom.php",
        type: "post",
        data: {
          roomId: id,
          flag: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarRoom();
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
// Function to populate the modal for editing room details
function populateEditRoomModal(room) {
  document.getElementById('roomId').value = room.roomId;
  document.getElementById('roomNumber').value = room.roomNumber;

  const roomTypeSelect = document.getElementById('roomTypeId');
  roomTypeSelect.value = room.roomTypeId;

  document.getElementById('status').value = room.status;
  document.getElementById('flag').value = 'update';
}

// Reset modal on close
document.getElementById('flag').value = 'add';
$('#roomModal').on('hidden.bs.modal', function () {
  $('#formRoom')[0].reset();
  document.getElementById('flag').value = 'add';
});

// Function to load the specified page for pagination
function loadPage(pageNumber) {
  // Get the limit value from the #limit element
  const limit = $('#limit').val();

  $.ajax({
      type: "POST",
      url: "daftarRoom.php",
      data: {
          flag: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          roomStatus: $('#roomStatus').val(),
          limit: limit // Add the limit to the data being sent
      },
      success: function (data) {
          $('#daftarRoom').html(data);
      }
  });
}




// function updateRoom(roomId) {
//   // $("#editRoomModal" + roomId).modal("hide");

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
//     url: "prosesRoom.php",
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
//       daftarRoom();
//       $("#editRoomModal").modal("hide");

//     },
//     error: function (jqXHR, textStatus, errorThrown) {
//       console.error("Error:", textStatus, errorThrown);
//     },
//   });
// }

function cariDaftarRoom() {
	const searchQuery = $("#searchQuery").val();
  const roomStatus = $("#roomStatus").val();
  const limit = $("#limit").val();
	if (searchQuery || roomStatus || limit) {
		$.ajax({
			url: "daftarRoom.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				roomStatus: roomStatus,
				limit: limit,
				flag: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRoom").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarRoom.php",
			type: "post",
			data: {
				flag: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRoom").html(data);
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
