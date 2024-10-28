<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
    $isActive = 'order';
    $title = __('Create Order');

    require(folder("layouts/header.php"));
    require(folder("layouts/sidebar.php"));
?>

<?php
if(!isset($_SESSION['orders'])){
    $_SESSION['orders'] = [];
}
//delete
if(isset($_GET['delete_index']) && $_GET['delete_index'] >= 0){
    $delete_index = $_GET['delete_index'];
    $newSession = [];
    foreach ($_SESSION['orders'] as $index => $session_order) {
        if($index != $delete_index){
            array_push($newSession, $session_order);
        }
    }

    $_SESSION['orders'] = $newSession;

    header('Location: ' . route('orders/create.php'));
}

// add
if(isset($_GET['product_id']) && isset($_GET['qty']) && $_GET['product_id'] > 0 && $_GET['qty'] > 0){
    $product_id = $_GET['product_id'];
    $qty = $_GET['qty'];

    $find = $mysql->query("SELECT * FROM products WHERE id = '$product_id' limit 1");
    if($product = $find->fetch_object()){
        $newOrder = [
            'product_id' => $product_id,
            'qty' => $qty
        ];
        $isExist = false;
        foreach ($_SESSION['orders'] as $index => $orderProduct) {
           if($orderProduct['product_id'] == $product_id){
                $isExist = true;
                $_SESSION['orders'][$index]['qty'] = $_SESSION['orders'][$index]['qty'] + $qty;
           }
        }
        if($isExist == false){
            array_push($_SESSION['orders'],$newOrder);
        }
    }
    header('Location: ' . route('orders/create.php'));
}

//increment
if(isset($_GET['increment_index']) && $_GET['increment_index'] >= 0){
    $increment_index = $_GET['increment_index'];
    if(isset($_SESSION['orders'][$increment_index])){
        $_SESSION['orders'][$increment_index]['qty']++;
    }
    header('Location: ' . route('orders/create.php'));
}

//decrement
if(isset($_GET['decrement_index']) && $_GET['decrement_index'] >= 0){
    $decrement_index = $_GET['decrement_index'];
    if(isset($_SESSION['orders'][$decrement_index])){
        $_SESSION['orders'][$decrement_index]['qty']--;
        if($_SESSION['orders'][$decrement_index]['qty'] == 0){
            $newSession = [];
            foreach ($_SESSION['orders'] as $index => $session_order) {
                if($index != $decrement_index){
                    array_push($newSession, $session_order);
                }
            }

            $_SESSION['orders'] = $newSession;
        }
    }
    header('Location: ' . route('orders/create.php'));
}


//submit
if(isset($_GET['submit']) && $_GET['submit'] == 1){
    if(count($_SESSION['orders']) > 0){
        $total_amount = 0;
        foreach ($_SESSION['orders'] as $key => $order) {
            $pro_id = $order['product_id'];
            $pro = $mysql->query("SELECT price FROM products WHERE id = '$pro_id' LIMIT 1")->fetch_object();
            $total_amount += $order['qty'] * $pro->price;
        }
        $date = date('Y-m-d H:i:s');

        // insert into orders
        $mysql->query("
            INSERT INTO orders
            (order_date,grand_total)
            VALUES ('$date','$total_amount')
        ");

        // last record
        $last_record = $mysql->query("SELECT * FROM orders ORDER BY id DESC limit 1")->fetch_object();
        $order_id = $last_record->id;
        foreach ($_SESSION['orders'] as $key => $order) {
            $pro_id = $order['product_id'];
            $pro = $mysql->query("SELECT price FROM products WHERE id = '$pro_id' LIMIT 1")->fetch_object();
            $qty = $order['qty'];
            $price = $pro->price;
            $total = $pro->price * $qty;
            $mysql->query("
                INSERT INTO order_details
                (order_id,product_id,qty,price,total)
                VALUES ('$order_id', '$pro_id','$qty','$price','$total')
            ");
        }
    } else{
        $_SESSION['sms'] = [
            'status' => 'error',
            'background' => 'alert-danger',
            'data' => __("Empty Order")
        ];
        header('Location: ' . route('orders/create.php'));
        exit();
    }

    $_SESSION['orders'] = [];
    $_SESSION['sms'] = [
        'status' => 'success',
        'background' => 'alert-success',
        'data' => __("Order Successfully")
    ];


    header('Location: ' . route('orders/print.php') . "?order_id=" . $order_id);
    exit();
}



$products = $mysql->query("SELECT * FROM products");

$myOrders = $_SESSION['orders'];
?>



<div class="container my-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 text-center"><?= __("Create Order Invoice"); ?></h2>
                </div>
                <div class="card-body">
                        <?php if(isset($_SESSION['sms'])){ ?>
                            <div class="my-2 alert <?php echo $_SESSION['sms']['background']; ?>">
                                <?php echo $_SESSION['sms']['data']; ?>
                            </div>
                        <?php } ?>
                    <form action="<?php echo route('orders/create.php'); ?>" method="GET">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <label for="product"><?= __('Product'); ?></label>
                                <select name="product_id" id="product" class="form-control" required>
                                    <option value=""><?= __('Please Select'); ?></option>
                                    <?php while($product = $products->fetch_object()){ ?>
                                        <option value="<?= $product->id; ?>"><?= $product->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="qty"><?= __('Qty'); ?></label>
                                <div class="input-group">
                                    <input type="number" min="1" name="qty" class="form-control" required>
                                    <span class="input-group-text p-0">
                                        <button class="btn btn-primary rounded-0 rounded-end"><i class="fa fa-plus-circle"></i> <?= __('Add'); ?></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>N</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>QTY</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="vertical-align:middle;" class="text-center">
                                        <?php $grand_total_amount = 0; ?>
                                        <?php foreach ($myOrders as $index => $myorder) { ?>
                                            <?php
                                                $product_id = $myorder['product_id'];
                                                $products = $mysql->query("SELECT * FROM products WHERE id = '$product_id' LIMIT 1");

                                                while ($product = $products->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?= $product->name; ?></td>
                                                    <td>$<?= number_format($product->price,2); ?></td>
                                                    <td>
                                                        <a href="<?= route('orders/create.php') . '?decrement_index=' . $index; ?>" class="btn btn-danger btn-sm me-2">-</a>
                                                        <?= $myorder['qty']; ?>
                                                        <a href="<?= route('orders/create.php') . '?increment_index=' . $index; ?>" class="btn btn-success btn-sm ms-2">+</a>
                                                    </td>
                                                    <td>$<?= number_format($myorder['qty'] * $product->price,2); ?></td>
                                                    <td>
                                                        <a href="<?php echo route('orders/create.php') . '?delete_index=' . $index; ?>"
                                                            class="btn btn-danger btn-sm"
                                                        >
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $grand_total_amount += $myorder['qty'] * $product->price; ?>
                                            <?php }?>
                                        <?php }?>
                                        <?php if(count($_SESSION['orders']) > 0){ ?>
                                            <tr>
                                                <td colspan="4" class="text-end">Grand Total</td>
                                                <td>
                                                    $<?= number_format($grand_total_amount,2); ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6">No Data</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="<?php echo route('orders/create.php') . '?submit=1'?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-box"></i> Submit Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>





<?php require(folder("layouts/footer.php")); ?>
