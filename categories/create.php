<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'category';
    $title = __('Create Category');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- fetch category data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $note = $_POST['note'];

        $mysql->query("INSERT INTO product_categories (name,note) VALUES ('$name','$note')");

        $_SESSION['sms'] = [
            'status' => 'success',
            'background' => 'alert-success',
            'data' => __("Insert Successfully")
        ];


        $route = route("categories");

        header("Location: $route");
        exit();
   }
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
                    <a href="<?= route('categories'); ?>" class="btn btn-danger mb-3">Back</a>
                    <form action="<?= route('categories/create.php'); ?>" method="POST">
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
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
