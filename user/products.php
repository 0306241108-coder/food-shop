<?php

include("../config/database.php");

include("../includes/header.php");



// =========================
// LẤY DANH MỤC
// =========================

$sql_category = "SELECT * FROM categories ORDER BY id ASC";

$result_category = mysqli_query($conn, $sql_category);


// =========================
// LẤY SẢN PHẨM
// =========================

$category_id = isset($_GET['category_id'])
    ? (int)$_GET['category_id']
    : 0;


if ($category_id > 0) {

    $sql_product = "
        SELECT *
        FROM products
        WHERE category_id = $category_id
        ORDER BY id DESC
    ";

} else {

    $sql_product = "
        SELECT *
        FROM products
        ORDER BY id DESC
    ";

}

$result_product = mysqli_query($conn, $sql_product);


// =========================
// TÊN DANH MỤC ĐANG CHỌN
// =========================

$category_name = "Tất cả sản phẩm";

if ($category_id > 0) {

    $sql_name = "
        SELECT name
        FROM categories
        WHERE id = $category_id
    ";

    $result_name = mysqli_query($conn, $sql_name);

    if ($result_name && mysqli_num_rows($result_name) > 0) {

        $category = mysqli_fetch_assoc($result_name);

        $category_name = $category['name'];
    }
}

?>

<div class="container mt-5">

    <!-- TIÊU ĐỀ -->

    <h2 class="text-center mb-4">

        <?php echo $category_name; ?>

    </h2>


    <!-- =========================
         DANH MỤC
    ========================= -->

    <div class="row justify-content-center mb-5">

        <?php

        if ($result_category && mysqli_num_rows($result_category) > 0) {

            while ($category = mysqli_fetch_assoc($result_category)) {

        ?>

        <div class="col-lg-3 col-md-6 col-6 mb-4">

            <a
                href="/food&shop/user/products.php?category_id=<?php echo $category['id']; ?>"
                class="category-link"
            >

                <div class="category-box">

                    <?php

                    if ($category['name'] == 'Coffee') {

                        $image = 'maycoffee.png';

                    } elseif ($category['name'] == 'Trà sữa') {

                        $image = 'TraSua.png';

                    } elseif ($category['name'] == 'Đồ ăn vặt') {

                        $image = 'DoAnVat.png';

                    } elseif ($category['name'] == 'Bánh ngọt') {

                        $image = 'bakery.png';

                    } else {

                        $image = 'default.png';

                    }

                    ?>

                    <img
                        src="/food&shop/assets/img/categories/<?php echo $image; ?>"
                        class="category-image"
                        alt="<?php echo $category['name']; ?>"
                    >

                    <h5>
                        <?php echo $category['name']; ?>
                    </h5>

                </div>

            </a>

        </div>

        <?php

            }

        }

        ?>

    </div>


    <!-- =========================
         DANH SÁCH SẢN PHẨM
    ========================= -->

    <h3 class="mb-4">

        Sản phẩm

    </h3>


    <div class="row">

        <?php

        if ($result_product && mysqli_num_rows($result_product) > 0) {

            while ($product = mysqli_fetch_assoc($result_product)) {

        ?>

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card product-card h-100">


                <!-- ẢNH -->

                
                <img
    src="../assets/img/products/<?php echo $product['image']; ?>"
    class="card-img-top"
    alt="<?php echo $product['name']; ?>"
>


                <!-- THÔNG TIN -->

                <div class="card-body text-center">

                    <h5 class="card-title">

                        <?php echo $product['name']; ?>

                    </h5>


                    <p class="card-text">

                        <?php echo $product['description']; ?>

                    </p>


                    <h4 class="text-danger">

                        <?php

                        echo number_format(
                            $product['price'],
                            0,
                           
                        );

                        ?>đ

                    </h4>


                    <p>

                        Còn lại:
                        <strong>
                            <?php echo $product['quantity']; ?>
                        </strong>

                    </p>


                    <!-- XEM CHI TIẾT -->

                    <a
                        href="/food&shop/user/product_detail.php?id=<?php echo $product['id']; ?>"
                        class="btn btn-warning"
                    >

                        Xem chi tiết

                    </a>

                </div>

            </div>

        </div>

        <?php

            }

        } else {

        ?>

        <?php

        }

        ?>

    </div>

</div>


<?php

include("../includes/footer.php");

?>