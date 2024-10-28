<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'user';
    $title = __('Edit User');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<!-- update data  -->
<?php
   if(count($_POST) > 0){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $id = $_GET['id'];

        $oldUser = $mysql->query("SELECT * FROM users WHERE id = '$id'")->fetch_object();

        $password = $_POST['password'] ? $_POST['password'] : $oldUser->password;


        if($_FILES['photo']['tmp_name']){

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

            if(file_exists(folder($oldUser->photo))){
                unlink(folder($oldUser->photo));
            }


            $mysql->query("UPDATE users SET name = '$name', username = '$username', photo = '$new_name', password = '$password' WHERE id = '$id'");

        } else {
            $mysql->query("UPDATE users SET name = '$name', username = '$username', password = '$password' WHERE id = '$id'");
        }


        if($_SESSION['auth_data']->id == $id){
            $_SESSION['auth_data'] = $mysql->query("SELECT * FROM users WHERE id = '$id'")->fetch_object();
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

<!-- fetch category data  -->
<?php
    $id = $_GET['id'];
    $user = $mysql->query("SELECT * FROM users WHERE id = '$id' limit 1");
    $user = $user->fetch_object();
?>


<div class="container my-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 text-center"><?= __("Edit User"); ?></h2>
                </div>
                <div class="card-body">
                    <a href="<?= route('users'); ?>" class="btn btn-danger mb-3">Back</a>
                    <form action="<?= route('users/edit.php') . "?id=" . $user->id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name"><?= __('Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= $user->name; ?>" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username"><?= __('Username'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="<?= $user->username; ?>"  name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password"><?= __('Password'); ?></label>
                            <input type="text" class="form-control"  name="password">
                        </div>
                        <div class="mb-3">
                            <label for="photo"><?= __('Photo'); ?></label>
                            <input type="file" accept="image/*" class="form-control" name="photo">
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
