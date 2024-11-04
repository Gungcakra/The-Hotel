document.addEventListener("DOMContentLoaded", function (event) {
  daftarRole(); 
});


function daftarRole() {
  $.ajax({
    url: "daftarRole.php",
    type: "post",
    data: {
      flagRole: "daftar"
    },
    beforeSend: function () {
      $(".overlay").show();
    },
    success: function (data, status) {
      $("#daftarRole").html(data);
      $(".overlay").hide();
    },
  });
}

function prosesRole() {
  const formRole = document.getElementById("formRole");
  const dataForm = new FormData(formRole);

  $("#roleModal").modal("hide");

  $("#roleModal").on('hidden.bs.modal', function () {
    $.ajax({
      url: "prosesRole.php",
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
        daftarRole();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  });
}


function deleteRole(id) {
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
        url: "prosesRole.php",
        type: "post",
        data: {
          roleId: id,
          flagRole: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarRole();
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
      url: "daftarRole.php",
      data: {
          flagRole: 'cari',
          page: pageNumber,
          searchQuery: $('#searchQuery').val(),
          limit: limit 
      },
      success: function (data) {
          $('#daftarRole').html(data);
      }
  });
}

function editRoleModal(role) {
  document.getElementById('roleId').value = role.roleId;
  document.getElementById('roleName').value = role.roleName;
  document.getElementById('salary').value = role.salary;
  document.getElementById('flagRole').value = 'update';
}





function cariDaftarRole() {
	const searchQuery = $("#searchQuery").val();
  const limit = $("#limit").val();
	if (searchQuery || limit) {
		$.ajax({
			url: "daftarRole.php",
			type: "post",
			data: {
				searchQuery: searchQuery,
				limit: limit,
				flagRole: "cari",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRole").html(data);
			},
		});
	}else  {
		$.ajax({
			url: "daftarRole.php",
			type: "post",
			data: {
				flagRole: "daftar",
			},
			beforeSend: function () {
			
			},
			success: function (data, status) {
				$("#daftarRole").html(data);
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
