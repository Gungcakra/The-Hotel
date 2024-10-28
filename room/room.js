// document.addEventListener("readystatechange", function (event) {
//     if (document.readyState === "complete") {
//         daftarRoom();
//     }
// });

document.addEventListener("DOMContentLoaded", function() {
    fetch('daftarRoom.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('daftarRoom').innerHTML = data;
        })
        .catch(error => console.error('Error loading daftarRoom:', error));
        if (document.readyState === "complete") {
            daftarRoom();
        }
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
            //console.log(data);
            $("#daftarRoom").html(data);
            $(".overlay").hide();
        },
    });
}

function addRoom() {
    const formBlog = document.getElementById("formAddRoom");
    const dataForm = new FormData(formBlog);
    
    // Menambahkan flag ke FormData
    dataForm.append('flag', 'add');

    $.ajax({
        url: "prosesRoom.php",
        type: "post",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        data: dataForm,
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            const { status, pesan } = data;
                notifikasi(status, pesan);
                daftarRoom();
            $('#addRoomModal').modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
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
                    notifikasi(status, pesan); // Show success/error notification
                    daftarRoom(); // Refresh the list after deletion
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    Swal.fire("Error", "Something went wrong!", "error"); // Error handling
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Canceled", "Proses Canceled!", "error");
        }
    });
}


function notifikasi(status, pesan) {
    if (status === true) {
        toastr.success(pesan);
    } else {
        toastr.error(pesan);
    }
}
