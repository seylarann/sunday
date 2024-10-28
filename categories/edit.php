<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'category';
    $title = __('Create Category');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- update data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $note = $_POST['note'];
        $id = $_GET['id'];

        $mysql->query("UPDATE product_categories SET name = '$name', note = '$note' WHERE id = '$id'");

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

<!-- fetch category data  -->
<?php
    $id = $_GET['id'];
    $category = $mysql->query("SELECT * FROM product_categories WHERE id = '$id' limit 1");
    $category = $category->fetch_object();
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
                    <form action="<?= route('categories/edit.php') . "?id=" . $category->id; ?>" method="POST">
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= $category->name; ?>" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="name"><?= __('Note'); ?></label>
                            <input type="text" class="form-control" value="<?= $category->note; ?>"  name="note">
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
