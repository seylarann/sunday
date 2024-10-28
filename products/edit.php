<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'product';
    $title = __('Create Product');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- update data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $note = $_POST['note'];
        $price = $_POST['price'];
        $product_category_id = $_POST['product_category_id'];
        $note = $_POST['note'];
        $id = $_GET['id'];

        $mysql->query("UPDATE products SET name = '$name', note = '$note', price = '$price', product_category_id = '$product_category_id' WHERE id = '$id'");

        $_SESSION['sms'] = [
            'status' => 'success',
            'background' => 'alert-success',
            'data' => __("Insert Successfully")
        ];


        $route = route("products");

        header("Location: $route");
        exit();
   }
?>

<!-- fetch category data  -->
<?php
    $id = $_GET['id'];
    $product = $mysql->query("SELECT * FROM products WHERE id = '$id' limit 1");
    $product = $product->fetch_object();

    $product_categories = $mysql->query("SELECT * FROM product_categories");
?>


<div class="container my-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 text-center"><?= __("Create Category"); ?></h2>
                </div>
                <div class="card-body">
                    <a href="<?= route('products'); ?>" class="btn btn-danger mb-3">Back</a>
                    <form action="<?= route('products/edit.php') . "?id=" . $product->id; ?>" method="POST">
                        <div class="mb-3">
                            <label for="product_category_id"><?= __('Category'); ?> <span class="text-danger">*</span></label>
                            <select name="product_category_id" id="product_category_id" class="form-select">
                                <option value=""><?= __('Please Select'); ?></option>
                                <?php while($product_category = $product_categories->fetch_object()){ ?>
                                    <option value="<?= $product_category->id; ?>" <?= $product_category->id == $product->product_category_id ? 'selected' : '' ?>><?= $product_category->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= $product->name; ?>" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" value="<?= $product->price; ?>" name="price" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="name"><?= __('Note'); ?></label>
                            <input type="text" class="form-control" value="<?= $product->note; ?>"  name="note">
                        </div>
                        <button class="btn btn-primary float-end"><?= __('Update'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>





<?php require(folder("layouts/footer.php")); ?>
