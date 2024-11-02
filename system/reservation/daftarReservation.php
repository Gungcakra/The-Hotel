<?php
session_start();
require_once "../../library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiTanggal.php";

//CEK USER
checkUserSession($db);

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$rentang = isset($_POST['rentang']) ? $_POST['rentang'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($rentang) {
  list($checkInDate, $checkInDate1) = explode(" - ", $rentang);
  $checkInDate = date("Y-m-d", strtotime($checkInDate));
  $checkInDate1 = date("Y-m-d", strtotime($checkInDate1));
}

if ($flag === 'cari') {

  if (!empty($searchQuery)) {
    // Ignore rentang if searchQuery is present
    $rentang = '';
    $conditions = " WHERE guests.name LIKE ?";
    $params[] = "%$searchQuery%";
  } elseif (!empty($rentang)) {
    $searchQuery = '';
    $conditions = " WHERE reservations.checkInDate BETWEEN ? AND ?";
    $params[] = $checkInDate;
    $params[] = $checkInDate1;
  }
}

$totalQuery = "SELECT COUNT(*) as total FROM reservations
    INNER JOIN guests ON reservations.guestId = guests.guestId
    INNER JOIN rooms ON reservations.roomId = rooms.roomId
    INNER JOIN extra ON reservations.extraId = extra.extraId
    INNER JOIN user ON reservations.userInputId = user.userId
    INNER JOIN roomtypes ON rooms.roomTypeId = roomtypes.roomTypeId
    INNER JOIN employees ON user.employeeId = employees.employeeId " . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT 
    reservations.*,
    guests.name AS guestName,
    rooms.roomNumber,
    roomtypes.typeName,
    extra.name AS extraName,
    extra.price AS extraPrice,
    user.username,
    employees.name AS employeeName
FROM 
    reservations
INNER JOIN guests ON reservations.guestId = guests.guestId
INNER JOIN rooms ON reservations.roomId = rooms.roomId
INNER JOIN extra ON reservations.extraId = extra.extraId
INNER JOIN user ON reservations.userInputId = user.userId
INNER JOIN roomtypes ON rooms.roomTypeId = roomtypes.roomTypeId
INNER JOIN employees ON user.employeeId = employees.employeeId" . $conditions .
  " ORDER BY reservations.checkInDate ASC 
LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$reservation = query($query, $params);
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Guest Name</th>
        <th scope="col">Room Number</th>
        <th scope="col">Room Type</th>
        <th scope="col">Extra</th>
        <th scope="col">Adult</th>
        <th scope="col">Child</th>
        <th scope="col">Check In</th>
        <th scope="col">Check Out</th>
        <th scope="col">Total Day(s)</th>
        <th scope="col">Total Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($reservation) {
        $no = $offset + 1; // Update the numbering based on the offset
        foreach ($reservation as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#employeeModal" onclick="editEmployeeModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteEmployee('<?= $rm['reservationId'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['guestName'] ?></td>
            <td><?= $rm['roomNumber'] ?></td>
            <td><?= $rm['typeName'] ?></td>
            <td><?= $rm['extraName'] ?></td>
            <td><?= $rm['adult'] ?></td>
            <td><?= $rm['child'] ?></td>
            <td><?= tanggalTerbilang($rm['checkInDate']) ?></td>
            <td><?= tanggalTerbilang($rm['checkOutDate']) ?></td>
            <td><?= totalHari($rm['checkInDate'], $rm['checkOutDate']) ?></td>
            <td><?= rupiah($rm['totalPrice']) ?></td>
          </tr>
        <?php
          $no++;
        endforeach;
      } else {
        ?>
        <tr>
          <td colspan="20">
            <p class="text-center font-weight-bold">No Result!</p>
          </td>
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

<!-- Modal Edit extra -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employee Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEmployee" method="post">
          <input autocomplete="off" type="hidden" id="employeeId" name="employeeId">
          <input autocomplete="off" type="hidden" id="flag" name="flag" value="update">
          <div class="form-group">
            <label for="extraNumber">Name</label>
            <input autocomplete="off" type="text" name="name" id="name" class="form-control" placeholder="Add Employee Name" autocomplete="off">
          </div>

          <label for="extraNumber">Phone Number</label>
          <input autocomplete="off" type="text" name="phoneNumber" id="phoneNumber" class="form-control" placeholder="Add Employee Phone Number" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="extraNumber">Email</label>
        <input autocomplete="off" type="text" name="email" id="email" class="form-control" placeholder="Add Employee Email" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="extraNumber">Address</label>
        <input autocomplete="off" type="text" name="address" id="address" class="form-control" placeholder="Add Employee Adress" autocomplete="off">
      </div>


      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" onclick="prosesEmployee()">Save changes</button>
    </div>
  </div>
</div>
</div>


<script>
  document.getElementById('flag').value = 'add';
  $('#employeeModal').on('hidden.bs.modal', function() {
    $('#formEmployee')[0].reset();
    document.getElementById('flag').value = 'add';
  });
</script>