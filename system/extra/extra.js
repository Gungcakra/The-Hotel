document.addEventListener("DOMContentLoaded", function (event) {
  daftarExtra();
});

function daftarExtra() {
  $.ajax({
    url: "daftarExtra.php",
    type: "post",
    data: {
      flagExtra: "daftar",
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarExtra").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesExtra() {
  const formExtraRoom = document.getElementById("formExtraRoom");
  const dataForm = new FormData(formExtraRoom);

  $("#extraModalEdit").modal("hide");

  $("#extraModalEdit").on("hidden.bs.modal", function () {
    $.ajax({
      url: "prosesExtra.php",
      type: "post",
      enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      data: dataForm,
      dataType: "json",
      success: function (data) {
        const { status, pesan } = data;
        notifikasi(status, pesan);
        daftarExtra();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}

function deleteExtra(id) {
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
        url: "prosesExtra.php",
        type: "post",
        data: {
          extraId: id,
          flagExtra: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarExtra();
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

function extraModalEdit(extra) {
  document.getElementById("extraIds").value = extra.extraId;
  document.getElementById("name").value = extra.name;
  document.getElementById("price").value = extra.price;
  document.getElementById("flagExtra").value = "update";
}

function loadPage(pageNumber) {
  const limit = $("#limit").val();
  $.ajax({
    type: "POST",
    url: "daftarExtra.php",
    data: {
      flagExtra: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarExtra").html(data);
    },
  });
}

function cariDaftarExtra() {
  const searchQuery = $("#searchQuery").val();
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarExtra.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagExtra: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarExtra").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarExtra.php",
      type: "post",
      data: {
        flagExtra: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarExtra").html(data);
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
