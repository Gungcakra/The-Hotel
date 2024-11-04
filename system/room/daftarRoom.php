<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);
// CEK STATUS ROOM
$currentDate = date("Y-m-d");

// 
$flagRoom = isset($_POST['flagRoom']) ? $_POST['flagRoom'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$roomStatus = isset($_POST['roomStatus']) ? $_POST['roomStatus'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1; 
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagRoom === 'cari') {
  if (!empty($roomStatus)) {
    $searchQuery = '';
    $conditions .= " WHERE rooms.status = ?";
    $params[] = $roomStatus;
  }

  if (!empty($searchQuery)) {
    $roomStatus = '';
    $conditions .= " WHERE rooms.roomNumber = ?";
    $params[] = $searchQuery;
  }
}

$totalQuery = "SELECT COUNT(*) as total FROM rooms INNER JOIN roomtypes ON rooms.roomTypeId = roomtypes.roomTypeId" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit); 

$query = "SELECT rooms.*, roomtypes.typeName 
          FROM rooms 
          INNER JOIN roomtypes ON rooms.roomTypeId = roomtypes.roomTypeId" . $conditions . " ORDER BY rooms.roomNumber ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset; 
$room = query($query, $params);
$roomType = query("SELECT * FROM roomtypes");
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
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
      if($room){
      $no = $offset + 1; 
      foreach ($room as $rm):
      ?>
        <tr>
          <td><?= $no ?></td>
          <td>
            <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cogs"></i>
            </button>
            <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
              <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#roomModal" onclick="EditRoomModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                <i class="fa fa-edit"></i> <strong>EDIT</strong>
              </button>
              <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteRoom('<?= $rm['roomId'] ?>')">
                <i class="fa fa-trash"></i> <strong>DELETE</strong>
              </button>
            </div>
          </td>
          <td><?= $rm['roomNumber'] ?></td>
          <td><?= $rm['typeName'] ?></td>
          <td><a class="btn btn-<?= ($rm['status'] ?? '') == 'Available' ? 'success' : (($rm['status'] ?? '') == 'Maintenance' ? 'info' : (($rm['status'] ?? '') == 'Booked' ? 'warning' : ''))  ?>"><?= $rm['status'] ?></a></td>
        </tr>
      <?php
        $no++;
      endforeach;
    } else {
      ?>
        <tr>
      <td colspan="10"> <p class="text-center font-weight-bold">No Result!</p></td>
     </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- Pagination Controls -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <button class="page-link" onclick="loadPage(<?= $page - 1 ?>)">Previous</button>
        </li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <button class="page-link" onclick="loadPage(<?= $i ?>)"><?= $i ?></button>
        </li>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <li class="page-item">
          <button class="page-link" onclick="loadPage(<?= $page + 1 ?>)">Next</button>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<!-- Modal Edit Room -->
<div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Room Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRoom" method="post">
          <input type="hidden" id="roomId" name="roomId">
          <input type="hidden" id="flagRoom" name="flagRoom" > 
          <div class="form-group">
            <label for="roomNumber">Room Number</label>
            <input type="number" name="roomNumber" id="roomNumber" class="form-control" placeholder="Add Room Number" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="roomType">Room Type</label>
            <select class="custom-select" id="roomTypeId" name="roomTypeId">
              <option value="">Choose...</option>
              <?php foreach ($roomType as $rt): ?>
                <option value="<?= $rt["roomTypeId"] ?>"><?= $rt["typeName"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="custom-select" id="status" name="status">
              <option value="Available">Available</option>
              <option value="Maintenance">Maintenance</option>
              <option value="Booked">Booked</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesRoom()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  
// Reset modal on close
document.getElementById('flagRoom').value = 'add';
$('#roomModal').on('hidden.bs.modal', function () {
  $('#formRoom')[0].reset();
  document.getElementById('flagRoom').value = 'add';
});
</script>