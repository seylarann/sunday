<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'product';
    $title = __('Create Product');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- fetch category data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $note = $_POST['note'];
        $product_category_id = $_POST['product_category_id'];

        if(!$name || !$price || !$product_category_id){
            $_SESSION['sms'] = [
                'status' => 'error',
                'background' => 'alert-danger',
                'data' => __("Required Input Data")
            ];


            $route = route("products");

            header("Location: $route");
            exit();
        }

        $mysql->query("INSERT INTO products (name,note,price,product_category_id) VALUES ('$name','$note','$price','$product_category_id')");

        $_SESSION['sms'] = [
            'status' => 'success',
            'background' => 'alert-success',
            'data' => __("Insert Successfully")
        ];


        $route = route("products");

        header("Location: $route");
        exit();
   }


   $product_categories = $mysql->query('SELECT * FROM product_categories');
?>



<div class="container my-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 text-center"><?= __("Create Product"); ?></h2>
                </div>
                <div class="card-body">
                    <a href="<?= route('products'); ?>" class="btn btn-danger mb-3">Back</a>
                    <form action="<?= route('products/create.php'); ?>" method="POST">
                        <div class="mb-3">
                            <label for="product_category_id"><?= __('Category'); ?> <span class="text-danger">*</span></label>
                            <select name="product_category_id" id="product_category_id" class="form-select" required>
                                <option value=""><?= __('Please Select'); ?></option>
                                <?php while($product_category = $product_categories->fetch_object()){ ?>
                                    <option value="<?= $product_category->id; ?>"><?= $product_category->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price"><?= __('Price'); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="name"><?= __('Note'); ?></label>
                            <input type="text" class="form-control" name="note">
                        </div>
                        <button class="btn btn-primary float-end"><?= __('Submit'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>





<?php require(folder("layouts/footer.php")); ?>
