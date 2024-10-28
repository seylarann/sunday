<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'user';
    $title = __('Create User');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- fetch category data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!$name || !$username || !$password){
            $_SESSION['sms'] = [
                'status' => 'error',
                'background' => 'alert-danger',
                'data' => __("Required Input Data")
            ];


            $route = route("users");

            header("Location: $route");
            exit();
        }

        if($_FILES['photo']){

            $file_name = $_FILES['photo']['name'];
            $file_tmp = $_FILES['photo']['tmp_name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_name = 'assets/images/' . time() . '.' . $file_ext;

            $file_name_path = folder($new_name);

            if(move_uploaded_file($file_tmp,$file_name_path)){
               echo "Success";
            }else{
                echo "File not uploaded";

            }


            $mysql->query("INSERT INTO users (name,username,password,photo) VALUES ('$name','$username','$password','$new_name')");
        } else {
            $mysql->query("INSERT INTO users (name,username,password) VALUES ('$name','$username','$password')");
        }

        $_SESSION['sms'] = [
            'status' => 'success',
            'background' => 'alert-success',
            'data' => __("Insert Successfully")
        ];


        $route = route("users");

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
                    <h2 class="mb-0 text-center"><?= __("Create User"); ?></h2>
                </div>
                <div class="card-body">
                    <a href="<?= route('users'); ?>" class="btn btn-danger mb-3">Back</a>
                    <form action="<?= route('users/create.php'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username"><?= __('Username'); ?></label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password"><?= __('Password'); ?></label>
                            <input type="text" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo"><?= __('Photo'); ?></label>
                            <input type="file" accept="image/*" class="form-control" name="photo">
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
