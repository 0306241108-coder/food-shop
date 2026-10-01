<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   KIỂM TRA ID
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$id = (int)$_GET['id'];


/* =========================
   LẤY THÔNG TIN TÀI KHOẢN
========================= */

$sql = "
    SELECT
        id,
        fullname,
        email,
        role,
        created_at
    FROM users
    WHERE id = $id
    LIMIT 1
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Không tìm thấy tài khoản.");
}

$user = mysqli_fetch_assoc($result);

?>

<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        👤 Chi tiết tài khoản
    </h2>


    <div class="card">

        <div class="card-header bg-dark text-white">

            📋 Thông tin tài khoản

        </div>


        <div class="card-body">

            <div class="row">

                <!-- ID -->

                <div class="col-md-6 mb-3">

                    <strong>
                        ID:
                    </strong>

                    <br>

                    <?php echo $user['id']; ?>

                </div>


                <!-- HỌ TÊN -->

                <div class="col-md-6 mb-3">

                    <strong>
                        Họ tên:
                    </strong>

                    <br>

                    <?php

                    echo htmlspecialchars(
                        $user['fullname']
                    );

                    ?>

                </div>


                <!-- EMAIL -->

                <div class="col-md-6 mb-3">

                    <strong>
                        Email:
                    </strong>

                    <br>

                    <?php

                    echo htmlspecialchars(
                        $user['email']
                    );

                    ?>

                </div>


                <!-- VAI TRÒ -->

                <div class="col-md-6 mb-3">

                    <strong>
                        Vai trò:
                    </strong>

                    <br>

                    <?php

                    if ($user['role'] == 'admin') {

                        echo '
                        <span class="badge bg-danger">
                            👑 Admin
                        </span>';

                    } else {

                        echo '
                        <span class="badge bg-primary">
                            👤 Người dùng
                        </span>';

                    }

                    ?>

                </div>


                <!-- NGÀY ĐĂNG KÝ -->

                <div class="col-md-6 mb-3">

                    <strong>
                        Ngày đăng ký:
                    </strong>

                    <br>

                    <?php

                    echo date(
                        "d/m/Y H:i",
                        strtotime(
                            $user['created_at']
                        )
                    );

                    ?>

                </div>

            </div>


            <hr>


            <!-- NÚT -->

            <a
                href="user_edit.php?id=<?php echo $user['id']; ?>"
                class="btn btn-warning"
            >
                ✏️ Sửa tài khoản
            </a>


            <a
                href="users.php"
                class="btn btn-secondary"
            >
                ← Quay lại
            </a>

        </div>

    </div>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>