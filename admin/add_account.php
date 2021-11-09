<?php
include('main.php');

$roles = array('Admin', 'Member');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $activation_code = $_POST['activation_code'];
    $remember = $_POST['remember'];
    $role = $_POST['role'];

    $insert_stmt = $db->prepare("INSERT INTO users (email, password, activation_code, remember, role) VALUES (:email, :password, :activation_code, :remember, :role)");
    $insert_stmt->bindParam(':email', $email);
    $insert_stmt->bindParam(':password', $password_hash);
    $insert_stmt->bindParam(':activation_code', $activation_code);
    $insert_stmt->bindParam(':remember', $remember);
    $insert_stmt->bindParam(':role', $role);
    $insert_stmt->execute();
    header('location: index.php');
    exit;
}
?>

<?php admin_header("สร้างบัญชี"); ?>

<div class="container-fluid">
    <h1>สร้างบัญชี</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="edit-form" action="" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" name="email"  id="exampleInputEmail1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Activation code</label>
                    <input type="text" class="form-control" name="activation_code"  id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Remember me code</label>
                    <input type="text" class="form-control" name="remember"  id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">บทบาท</label>
                    <select class="form-control" name="role" aria-label="Default select example">
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100 mt-3">บันทึก</button>
            </form>
        </div>
    </div>
</div>
  
 <?php admin_footer(); ?>