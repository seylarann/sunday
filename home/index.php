<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $title = __('Home');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>


<?php if(isset($_SESSION['sms'])){ ?>
    <div class="alert  <?php echo $_SESSION['sms']['background']; ?>">
        <?php echo $_SESSION['sms']['data']; ?>
    </div>
<?php } ?>

<?php
    $total_product = $mysql->query("SELECT COUNT(*) as total FROM products")->fetch_object()->total;
    $total_order = $mysql->query("SELECT COUNT(*) as total FROM orders")->fetch_object()->total;
    $total_user = $mysql->query("SELECT COUNT(*) as total FROM users")->fetch_object()->total;
?>

<div class="container">
    <div class="row">
        <div class="col-12 my-3">
            <h2>Welcome <?= $_SESSION['auth_data']->name; ?></h2>
        </div>
        <div class="col-4">
            <div class="border rounded bg-warning p-3 text-center" >
                <h2>Number of Products</h2>
                <h1><?php echo $total_product; ?></h1>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded bg-success text-white p-3 text-center" >
                <h2>Number of Ordered</h2>
                <h1><?php echo $total_order; ?></h1>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded bg-info p-3 text-center" >
                <h2>Number of Users</h2>
                <h1><?php echo $total_user; ?></h1>
            </div>
        </div>
    </div>
</div>





<?php require(folder("layouts/footer.php")); ?>
