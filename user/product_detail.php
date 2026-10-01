<?php

session_start();

require_once "../config/database.php";


// ======================================
// KIỂM TRA ID SẢN PHẨM
// ======================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: products.php");
    exit;

}

$id = (int) $_GET['id'];


// ======================================
// LẤY SẢN PHẨM
// ======================================

$sql = "
    SELECT *
    FROM products
    WHERE id = $id
    LIMIT 1
";

$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) == 0) {

    echo "Không tìm thấy sản phẩm.";
    exit;

}


$product = mysqli_fetch_assoc($result);


// ======================================
// XỬ LÝ THÊM VÀO GIỎ
// ======================================

if (isset($_POST['add_to_cart'])) {


    // ------------------------------
    // KIỂM TRA ĐĂNG NHẬP
    // ------------------------------

    if (!isset($_SESSION['user_id'])) {

        header("Location: login.php");
        exit;

    }


    $user_id = (int) $_SESSION['user_id'];

    $product_id = (int) $_POST['product_id'];

    $quantity = (int) $_POST['quantity'];


    // ------------------------------
    // KIỂM TRA SỐ LƯỢNG
    // ------------------------------

    if ($quantity < 1) {

        $quantity = 1;

    }


    // Không cho mua vượt quá kho

    if ($quantity > $product['quantity']) {

        $quantity = (int) $product['quantity'];

    }


    // ------------------------------
    // TÌM GIỎ HÀNG CỦA USER
    // ------------------------------

    $sql_cart = "
        SELECT id
        FROM cart
        WHERE user_id = $user_id
        LIMIT 1
    ";

    $result_cart = mysqli_query($conn, $sql_cart);


    if ($result_cart && mysqli_num_rows($result_cart) > 0) {

        $cart = mysqli_fetch_assoc($result_cart);

        $cart_id = (int) $cart['id'];

    } else {


        // ------------------------------
        // CHƯA CÓ GIỎ → TẠO GIỎ
        // ------------------------------

        $sql_create_cart = "
            INSERT INTO cart (user_id, created_at)
            VALUES ($user_id, NOW())
        ";

        mysqli_query($conn, $sql_create_cart);

        $cart_id = mysqli_insert_id($conn);

    }


    // ------------------------------
    // KIỂM TRA SẢN PHẨM ĐÃ CÓ TRONG GIỎ
    // ------------------------------

    $sql_check = "
        SELECT id, quantity
        FROM cart_items
        WHERE cart_id = $cart_id
        AND product_id = $product_id
        LIMIT 1
    ";

    $result_check = mysqli_query($conn, $sql_check);


    if ($result_check && mysqli_num_rows($result_check) > 0) {


        // ------------------------------
        // ĐÃ CÓ → CỘNG THÊM SỐ LƯỢNG
        // ------------------------------

        $item = mysqli_fetch_assoc($result_check);

        $new_quantity =
            (int) $item['quantity']
            +
            $quantity;


        // Không vượt quá số lượng kho

        if ($new_quantity > $product['quantity']) {

            $new_quantity = (int) $product['quantity'];

        }


        $cart_item_id = (int) $item['id'];


        $sql_update = "
            UPDATE cart_items
            SET quantity = $new_quantity
            WHERE id = $cart_item_id
        ";

        mysqli_query($conn, $sql_update);


    } else {


        // ------------------------------
        // CHƯA CÓ → THÊM MỚI
        // ------------------------------

        $sql_insert = "
            INSERT INTO cart_items
            (
                cart_id,
                product_id,
                quantity
            )
            VALUES
            (
                $cart_id,
                $product_id,
                $quantity
            )
        ";

        mysqli_query($conn, $sql_insert);

    }


    // ------------------------------
    // CHUYỂN SANG GIỎ HÀNG
    // ------------------------------

    header("Location: cart.php");
    exit;

}


// ======================================
// HEADER + MENU
// ======================================

include("../includes/header.php");



?>



<!-- ======================================
     CHI TIẾT SẢN PHẨM
====================================== -->

<div class="container mt-5 mb-5">

    <div class="row">


        <!-- ==================================
             ẢNH SẢN PHẨM
        =================================== -->

        <div class="col-md-6 mb-4">

            <div class="product-detail-image">

                <img
                    src="/food&shop/assets/img/products/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                >

            </div>

        </div>



        <!-- ==================================
             THÔNG TIN SẢN PHẨM
        =================================== -->

        <div class="col-md-6">


            <h1 class="mb-3">

                <?php

                echo htmlspecialchars($product['name']);

                ?>

            </h1>



            <!-- GIÁ -->

            <h2 class="text-danger mb-4">

                <?php

                echo number_format(
                    $product['price'],
                    0,
                    ',',
                    '.'
                );

                ?>đ

            </h2>



            <!-- MÔ TẢ -->

            <h5>

                Mô tả sản phẩm

            </h5>


            <p class="product-description">

                <?php

                echo htmlspecialchars(
                    $product['description']
                );

                ?>

            </p>



            <!-- SỐ LƯỢNG KHO -->

            <p class="mt-4">

                Số lượng còn lại:

                <strong>

                    <?php

                    echo $product['quantity'];

                    ?>

                </strong>

            </p>



            <?php if ($product['quantity'] > 0) { ?>


                <!-- ==================================
                     FORM THÊM GIỎ HÀNG
                =================================== -->

                <form
                    action="product_detail.php?id=<?php echo $product['id']; ?>"
                    method="POST"
                >


                    <!-- ID SẢN PHẨM -->

                    <input
                        type="hidden"
                        name="product_id"
                        value="<?php echo $product['id']; ?>"
                    >



                    <!-- SỐ LƯỢNG -->

                    <div class="mb-3">

                        <label class="form-label">

                            Số lượng mua

                        </label>


                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="<?php echo $product['quantity']; ?>"
                            class="form-control"
                            style="max-width:150px;"
                        >

                    </div>



                    <!-- NÚT THÊM -->

                    <button
                        type="submit"
                        name="add_to_cart"
                        class="btn btn-warning btn-lg"
                    >

                        🛒 Thêm vào giỏ hàng

                    </button>


                </form>


            <?php } else { ?>


                <!-- HẾT HÀNG -->

                <button
                    class="btn btn-secondary btn-lg"
                    disabled
                >

                    Hết hàng

                </button>


            <?php } ?>



            <!-- QUAY LẠI -->

            <div class="mt-4">

                <a
                    href="/food&shop/user/products.php"
                    class="btn btn-outline-secondary"
                >

                    ← Quay lại sản phẩm

                </a>

            </div>


        </div>

    </div>

</div>



<?php

include("../includes/footer.php");

?>