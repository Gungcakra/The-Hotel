<?php
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$kataKunciData = isset($_POST['kataKunciData']) ? $_POST['kataKunciData'] : '';

if ($flag === 'daftar') {
    $room = query("SELECT rooms.*, roomTypes.typeName FROM rooms INNER JOIN roomTypes ON rooms.roomTypeId = roomTypes.roomTypeId");
  } else if($flag === 'cari'){
    $room = query("SELECT rooms.*, roomTypes.typeName FROM rooms INNER JOIN roomTypes ON rooms.roomTypeId = roomTypes.roomTypeId WHERE roomNumber = $kataKunciData");
}

$roomType = query("SELECT * FROM roomTypes");

?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead class="">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Room Number</th>
        <th scope="col">Room Type</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($room as $rm):
      ?>
        <tr>
          <td><?= $no ?></td>
          <td>
            <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cogs"></i>
            </button>
            <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">

              <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#editRoomModal<?= $rm['roomId'] ?>">
                <i class="fa fa-edit"></i> <strong>EDIT</strong>
              </button>

              <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteRoom('<?= $rm['roomId'] ?>')">
                <i class="fa fa-trash"></i> <strong>DELETE</strong>
              </button>
          </td>
          <td><?= $rm['roomNumber'] ?></td>
          <td><?= $rm['typeName'] ?></td>
          <td><a class="btn btn-<?=
                                ($rm['status'] ?? '') == 'available' ? 'success' : (($rm['status'] ?? '') == 'maintenance' ? 'info' : (($rm['status'] ?? '') == 'booked' ? 'warning' : ''))  ?>
"><?= $rm['status'] ?></a>
          </td>
          <div class="modal fade" id="editRoomModal<?= $rm['roomId'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Room</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="formEditRoom<?= $rm['roomId'] ?>" method="post"> <!-- ID unik untuk form -->
                    <input type="hidden" value="<?= $rm['roomId'] ?>" id="idRoom<?= $rm['roomId'] ?>" name="idRoom">
                    <div class="form-group">
                      <label for="roomNumber">Room Number</label>
                      <input type="number" name="roomNumber" id="roomNumber<?= $rm['roomId'] ?>" class="form-control" placeholder="Add Room Number" autocomplete="off" value="<?= $rm['roomNumber'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="roomType">Room Type</label>
                      <select class="custom-select" id="roomTypeId<?= $rm['roomId'] ?>" name="roomTypeId">
                        <option value="">Choose...</option>
                        <?php foreach ($roomType as $rt): ?>
                          <option value="<?= $rt["roomTypeId"] ?>" <?= $rt["roomTypeId"] == $rm["roomTypeId"] ? "selected" : "" ?>>
                            <?= $rt["typeName"] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="custom-select" id="status<?= $rm['roomId'] ?>" name="status">
                        <option value="available" <?= $rm['status'] == "available" ? "selected" : "" ?>>Available</option>
                        <option value="maintenance" <?= $rm['status'] == "maintenance" ? "selected" : "" ?>>Maintenance</option>
                        <option value="booked" <?= $rm['status'] == "booked" ? "selected" : "" ?>>Booked</option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="updateRoom(<?= $rm['roomId'] ?>)">Save changes</button> <!-- Pass roomId -->
                  </form>
                </div>
              </div>
            </div>
          </div>

</div>

<?php
        $no++;
      endforeach;
?>
</tbody>
</table>


