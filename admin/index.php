<?php
include('main.php');

$select_stmt = $db->prepare("SELECT * FROM users");
$select_stmt->execute();
?>

<?php admin_header("หน้าแรก"); ?>
            
<div class="container-fluid">
    <h1>บัญชี</h1>
    <hr>
    <a href="add_account.php" class="btn btn-primary mb-4 mt-2">สร้างบัญชี</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">รายการบัญชีทั้งหมด</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>อีเมล</th>
                            <th>รหัสผ่าน</th>
                            <th>Activation Code</th>
                            <th>บทบาท</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ลำดับ</th>
                            <th>อีเมล</th>
                            <th>รหัสผ่าน</th>
                            <th>Activation Code</th>
                            <th>บทบาท</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($select_stmt as $row) { ?>
                        <tr onclick="location.href='edit_account.php?user_id=<?php echo $row['user_id']; ?>'" style="cursor: pointer;">
                            <?php $i++; ?>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['activation_code']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

 <?php admin_footer(); ?>