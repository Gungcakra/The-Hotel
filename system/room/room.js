
document.addEventListener("DOMContentLoaded", function (event) {
  daftarRoom();
});

function daftarRoom() {
  $.ajax({
    url: "daftarRoom.php",
    type: "post",
    data: {
      flagRoom: "daftar",
    },
    beforeSend: function () {

    },
    success: function (data, status) {
      $("#daftarRoom").html(data);
    },
  });
}

function prosesRoom() {
  const formRoom = document.getElementById("formRoom");
  const dataForm = new FormData(formRoom);

  $("#roomModal").modal("hide");

  $("#roomModal").on("hidden.bs.modal", function () {
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
          flagRoom: "delete",
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
function EditRoomModal(room) {
  document.getElementById("roomId").value = room.roomId;
  document.getElementById("roomNumber").value = room.roomNumber;

  const roomTypeSelect = document.getElementById("roomTypeId");
  roomTypeSelect.value = room.roomTypeId;

  document.getElementById("status").value = room.status;
  document.getElementById("flagRoom").value = "update";
}

function loadPage(pageNumber) {
  const limit = $("#limit").val();

  $.ajax({
    type: "POST",
    url: "daftarRoom.php",
    data: {
      flagRoom: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      roomStatus: $("#roomStatus").val(),
      limit: limit, 
    },
    success: function (data) {
      $("#daftarRoom").html(data);
    },
  });
}

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
        flagRoom: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarRoom").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarRoom.php",
      type: "post",
      data: {
        flagRoom: "daftar",
      },
      beforeSend: function () {},
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
