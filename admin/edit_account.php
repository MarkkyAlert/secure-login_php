<?php
include('main.php');

$roles = array('Admin', 'Member');

if (isset($_GET['user_id'])) {
    $select_stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $select_stmt->bindParam(':user_id', $_GET['user_id']);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($_POST['delete'])) {
        $delete_stmt = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
        $delete_stmt->bindParam(':user_id', $_POST['user_id']);
        $delete_stmt->execute();
        header('location: index.php');
        exit;
    }
    else if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $activation_code = $_POST['activation_code'];
        $remember = $_POST['remember'];
        $role = $_POST['role'];
        if (empty($_POST['password'])) {
            $password = $row['password'];
        }
        else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $update_stmt = $db->prepare("UPDATE users SET email = :email, password = :password, activation_code = :activation_code, remember = :remember, role = :role WHERE user_id = :user_id");
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':password', $password);
        $update_stmt->bindParam(':activation_code', $activation_code);
        $update_stmt->bindParam(':remember', $remember);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':user_id', $_GET['user_id']);
        $update_stmt->execute();
        header('location: index.php');
        exit;
    }
}
?>

<?php admin_header("แก้ไขบัญชี"); ?>
               
<div class="container-fluid">
    <h1>แก้ไขบัญชี</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="edit-form" action="" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" id="exampleInputEmail1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Activation code</label>
                    <input type="text" class="form-control" name="activation_code" value="<?php echo $row['activation_code']; ?>" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Remember me code</label>
                    <input type="text" class="form-control" name="remember" value="<?php echo $row['remember']; ?>" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">บทบาท</label>
                    <select class="form-control" name="role" aria-label="Default select example">
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?php echo $role; ?>" <?php if ($role == $row['role']) {echo "selected";} ?>><?php echo $role; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <button type="submit" name="submit" class="btn btn-primary w-100 mt-3">บันทึก</button>
                <button type="button" class="btn btn-danger w-100 mt-3" data-toggle="modal" data-target="#exampleModal">ลบ</button>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">แจ้งเตือน</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                คุณต้องการลบรายการนี้ใช่หรือไม่
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" name="delete" class="btn btn-primary">ตกลง</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php admin_footer(); ?>