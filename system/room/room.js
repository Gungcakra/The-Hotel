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
  daftarRoom(); // Panggil daftarRoom segera setelah DOM sepenuhnya dimuat
});


function daftarRoom() {
  $.ajax({
    url: "daftarRoom.php",
    type: "post",
    data: {
      flag: "daftar",
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

function addRoom() {
  const formBlog = document.getElementById("formAddRoom");
  const dataForm = new FormData(formBlog);

  // Menambahkan flag ke FormData
  dataForm.append("flag", "add");

  $.ajax({
    url: "prosesRoom.php",
    type: "post",
    enctype: "multipart/form-data",
    processData: false,
    contentType: false,
    data: dataForm,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      const { status, pesan } = data;
      notifikasi(status, pesan);
      daftarRoom();
      $("#addRoomModal").modal("hide");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
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

function updateRoom(roomId) {
  const formRoom = document.getElementById("formEditRoom" + roomId);

  const roomNumber = $("#roomNumber" + roomId).val();
  const roomTypeId = $("#roomTypeId" + roomId).val();
  const status = $("#status" + roomId).val();

  const dataForm = new FormData(formRoom);
  dataForm.append("roomId", roomId);
  dataForm.append("roomNumber", roomNumber);
  dataForm.append("roomTypeId", roomTypeId);
  dataForm.append("status", status);
  dataForm.append("flag", "update");

  $.ajax({
    url: "prosesRoom.php",
    type: "POST",
    data: dataForm,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      console.log(data);
      const { status, pesan } = data;
      notifikasi(status, pesan);
      $(".modal-backdrop").remove();
      daftarRoom();
      $("#addRoomModal").modal("hide");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
}

function cariDaftarRoom() {
	const kataKunciData = $("#searchId").val();
	if (kataKunciData) {
		$.ajax({
			url: "daftarRoom.php",
			type: "post",
			data: {
				kataKunciData: kataKunciData,
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
