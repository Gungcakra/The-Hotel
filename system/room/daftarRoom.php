<?php
require_once "../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$roomStatus = isset($_POST['roomStatus']) ? $_POST['roomStatus'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1; // Get current page number
$offset = ($page - 1) * $limit; // Calculate offset for SQL query
$conditions = '';
$params = [];

if ($flag === 'cari') {
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

// Count total records
$totalQuery = "SELECT COUNT(*) as total FROM rooms INNER JOIN roomTypes ON rooms.roomTypeId = roomTypes.roomTypeId" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit); // Calculate total pages

$query = "SELECT rooms.*, roomTypes.typeName 
          FROM rooms 
          INNER JOIN roomTypes ON rooms.roomTypeId = roomTypes.roomTypeId" . $conditions . " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset; // Add offset to params
$room = query($query, $params);
$roomType = query("SELECT * FROM roomTypes");
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
      $no = $offset + 1; // Update the numbering based on the offset
      foreach ($room as $rm):
      ?>
        <tr>
          <td><?= $no ?></td>
          <td>
            <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cogs"></i>
            </button>
            <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
              <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#roomModal" onclick="populateEditRoomModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
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
      ?>
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Room</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRoom" method="post">
          <input type="hidden" id="roomId" name="roomId">
          <input type="hidden" id="flag" name="flag" value="update"> <!-- Hidden action field -->
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
