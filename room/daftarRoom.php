<?php
require_once "../library/konfigurasi.php";

$room = query("SELECT rooms.*,
                             roomTypes.typeName
                      FROM rooms INNER JOIN roomTypes
                      ON rooms.roomTypeId = roomTypes.roomTypeId");
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
        foreach($room as $rm):
    ?>
    <tr>
      <td><?= $no ?></td>
      <td>
      <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cogs"></i>
                    </button>
                    <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                        <a href="detail_room/?param=<?= $query ?>" class="btn btn-warning btn-sm tombol-dropdown">
                            <i class="fa fa-edit"></i> <strong>EDIT</strong>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteRoom('<?= $rm['roomId'] ?>')">
                            <i class="fa fa-trash"></i> <strong>HAPUS</strong>
                        </button>
      </td>
      <td><?= $rm['roomNumber'] ?></td>
      <td><?= $rm['typeName'] ?></td>
      <td><a class="btn btn-<?= 
      ($rm['status'] ?? '') == 'available' ? 'success' : (($rm['status'] ?? '') == 'maintenance' ? 'info' : (($rm['status'] ?? '') == 'booked' ? 'warning' : ''))  ?>
"><?= $rm['status'] ?></a>  
    </td>
    </tr>
    <?php 
        $no++;
        endforeach; 
    ?>
  </tbody>
</table>
</div>