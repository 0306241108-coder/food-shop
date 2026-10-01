<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   KIỂM TRA ID SẢN PHẨM
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: inventory.php");
    exit;
}

$id = (int)$_GET['id'];

$message = "";


/* =========================
   LẤY SẢN PHẨM
========================= */

$sql = "
    SELECT *
    FROM products
    WHERE id = $id
    LIMIT 1
";

$result = mysqli_query($conn, $sql);

$product = mysqli_fetch_assoc($result);


if (!$product) {
    die("Không tìm thấy sản phẩm.");
}


/* =========================
   CẬP NHẬT KHO
========================= */

if (isset($_POST['update'])) {

    $quantity = (int)$_POST['quantity'];

    if ($quantity < 0) {

        $message =
            "❌ Số lượng không được nhỏ hơn 0.";

    } else {

        $update = mysqli_query(
            $conn,
            "
            UPDATE products
            SET quantity = $quantity
            WHERE id = $id
            "
        );


        if (!$update) {

            $message =
                "❌ Không thể cập nhật số lượng.";

        } else {

            header("Location: inventory.php");
            exit;
        }
    }
}

?>

<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        ✏️ Điều chỉnh tồn kho
    </h2>


    <!-- THÔNG BÁO -->

    <?php if ($message != "") { ?>

        <div class="alert alert-danger">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- THÔNG TIN SẢN PHẨM -->

    <div class="card">

        <div class="card-header bg-dark text-white">

            📦 Điều chỉnh số lượng sản phẩm

        </div>


        <div class="card-body">


            <h5>

                <?php
                echo htmlspecialchars(
                    $product['name']
                );
                ?>

            </h5>


            <p class="mt-3">

                Số lượng hiện tại:

                <strong class="text-primary">

                    <?php
                    echo $product['quantity'];
                    ?>

                </strong>

            </p>


            <hr>


            <!-- FORM -->

            <form method="POST">


                <label class="form-label">

                    Số lượng tồn mới

                </label>


                <input
                    type="number"
                    name="quantity"
                    class="form-control mb-3"
                    min="0"
                    value="<?php echo $product['quantity']; ?>"
                    required
                >


                <button
                    type="submit"
                    name="update"
                    class="btn btn-primary"
                >

                    💾 Cập nhật

                </button>


                <a
                    href="inventory.php"
                    class="btn btn-secondary"
                >

                    ← Quay lại

                </a>


            </form>

        </div>

    </div>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>