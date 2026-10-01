<?php

include("config/database.php");

include("includes/header.php");

?>

<!-- =========================
     DANH MỤC SẢN PHẨM
========================= -->

<div class="container mt-5">

    <h2 class="text-center mb-5">
        Danh mục sản phẩm
    </h2>

    <div class="row justify-content-center">

        <?php

        // Lấy danh mục từ database
        $sql_category = "SELECT * FROM categories ORDER BY id ASC";
        $result_category = mysqli_query($conn, $sql_category);

        if ($result_category && mysqli_num_rows($result_category) > 0) {

            while ($category = mysqli_fetch_assoc($result_category)) {

                // Xác định ảnh theo tên danh mục
                if ($category['name'] == 'Coffee') {

                    $image = 'maycoffee.png';

                } elseif ($category['name'] == 'Trà sữa') {

                    $image = 'TraSua.png';

                } elseif ($category['name'] == 'Đồ ăn vặt') {

                    $image = 'DoAnVat.png';

                } elseif ($category['name'] == 'Bánh ngọt') {

                    $image = 'bakery.png';

                } else {

                    $image = '';

                }

        ?>

        <div class="col-lg-3 col-md-6 col-6 mb-4">

            <a
                href="user/products.php?category_id=<?php echo $category['id']; ?>"
                class="category-link"
            >

                <div class="category-box">

                    <?php if ($image != '') { ?>

                        <img
                            src="assets/img/categories/<?php echo $image; ?>"
                            alt="<?php echo $category['name']; ?>"
                            class="category-image"
                        >

                    <?php } ?>

                    <h5>
                        <?php echo $category['name']; ?>
                    </h5>

                </div>

            </a>

        </div>

        <?php

            }

        } else {

        ?>

        <div class="col-12 text-center">

            <p>Chưa có danh mục sản phẩm.</p>

        </div>

        <?php

        }

        ?>

    </div>

</div>


<!-- =========================
     SẢN PHẨM NỔI BẬT
========================= -->

<div class="container mt-5">

    <h2 class="text-center mb-5">
        Sản phẩm nổi bật
    </h2>

    <div class="row">

        <?php

        // Lấy 8 sản phẩm mới nhất
        $sql_product = "SELECT * FROM products ORDER BY id DESC LIMIT 8";
        $result_product = mysqli_query($conn, $sql_product);

        if ($result_product && mysqli_num_rows($result_product) > 0) {

            while ($product = mysqli_fetch_assoc($result_product)) {

        ?>

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card product-card h-100">

                <!-- Ảnh sản phẩm -->

                <img
                    src="assets/img/products/<?php echo $product['image']; ?>"
                    class="card-img-top"
                    alt="<?php echo $product['name']; ?>"
                >

                <div class="card-body text-center">

                    <!-- Tên sản phẩm -->

                    <h5 class="card-title">
                        <?php echo $product['name']; ?>
                    </h5>

                    <!-- Mô tả -->

                    <p class="card-text">
                        <?php echo $product['description']; ?>
                    </p>

                    <!-- Giá -->

                    <h4 class="text-danger">

                        <?php

                        echo number_format(
                            $product['price'],
                            0,
                            ',',
                            '.'
                        );

                        ?>đ

                    </h4>

                    <!-- Số lượng -->

                    <p>

                        Còn lại:
                        <?php echo $product['quantity']; ?>

                    </p>

                    <!-- Xem chi tiết -->

                    <a
                        href="user/product_detail.php?id=<?php echo $product['id']; ?>"
                        class="btn btn-warning"
                    >

                        🛒 Xem chi tiết

                    </a>

                </div>

            </div>

        </div>

        <?php

            }

        } else {

        ?>

        <div class="col-12 text-center">

            <p>Hiện chưa có sản phẩm nào.</p>

        </div>

        <?php

        }

        ?>

    </div>

</div>

</body>

</html>