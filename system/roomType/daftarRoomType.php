<?php
session_start();
require_once "../../library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";


//CEK USER
checkUserSession($db);

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1; // Get current page number
$offset = ($page - 1) * $limit; // Calculate offset for SQL query
$conditions = '';
$params = [];

if ($flag === 'cari') {


  if (!empty($searchQuery)) {
    $conditions .= " WHERE typeName LIKE ?";
    $params[] = "%$searchQuery%";
  }
}

// Count total records
$totalQuery = "SELECT COUNT(*) as total FROM roomTypes" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit); // Calculate total pages

$query = "SELECT * FROM roomTypes" . $conditions . " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset; // Add offset to params
$roomType = query($query, $params);
?>

<div class="card shadow mb-2 w-100">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Action</th>
        <th scope="col">Room Type</th>
        <th scope="col">Room Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($roomType) {
        $no = $offset + 1;
        foreach ($roomType as $rm):
      ?>
          <tr>
            <td><?= $no ?></td>
            <td>
              <button type="button" id="dropdownMenuButton" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs"></i>
              </button>
              <div class="dropdown-menu menu-aksi" aria-labelledby="dropdownMenuButton">
                <button type="button" class="btn btn-warning btn-sm tombol-dropdown-last" data-toggle="modal" data-target="#roomTypeModal" onclick="editRoomTypeModal(<?= htmlspecialchars(json_encode($rm)) ?>)">
                  <i class="fa fa-edit"></i> <strong>EDIT</strong>
                </button>
                <button type="button" class="btn btn-danger btn-sm tombol-dropdown-last" onclick="deleteRoomType('<?= $rm['roomTypeId'] ?>')">
                  <i class="fa fa-trash"></i> <strong>DELETE</strong>
                </button>
              </div>
            </td>
            <td><?= $rm['typeName'] ?></td>
            <td><?= rupiah($rm['price']) ?></td>
          </tr>
        <?php
          $no++;
        endforeach;
      } else {
        ?>
        <tr>
          <td colspan="10">
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

<!-- Modal Edit Room -->
<div class="modal fade" id="roomTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Room Type Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRoomType" method="post">
          <input type="hidden" id="roomTypeId" name="roomTypeId">
          <input type="hidden" id="flag" name="flag" value="update"> <!-- Hidden action field -->
          <div class="form-group">
            <label for="roomNumber">Type Name</label>
            <input type="text" name="typeName" id="typeName" class="form-control" placeholder="Add Room Type" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="roomNumber">Price</label>
            <input type="text" name="price" id="price" class="form-control" placeholder="Add Type Price" autocomplete="off">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="prosesRoomType()">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
  // Reset modal on close
  document.getElementById('flag').value = 'add';
  $('#roomTypeModal').on('hidden.bs.modal', function() {
    $('#formRoomType')[0].reset();
    document.getElementById('flag').value = 'add';
  });
</script>