<?php
        require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
        $isActive = 'order';
        $title = __('Order');

        require(folder("layouts/header.php"));
        require(folder("layouts/sidebar.php"));
    ?>

    <?php
        $myOrders = [];
        if(isset($_GET['order_id']) && $_GET['order_id'] > 0){
            $order_id = $_GET['order_id'];
            $order = $mysql->query("SELECT * FROM orders WHERE id = '$order_id'")->fetch_object();
            if($order){
                $find = $mysql->query("
                    SELECT
                        order_details.*,
                        products.name as product_name
                    FROM order_details
                    INNER JOIN
                        products ON products.id = order_details.product_id
                    WHERE order_details.order_id = '$order_id'
                ");

                $myOrders = $find;

            } else {

                $_SESSION['sms'] = [
                    'status' => 'error',
                    'background' => 'alert-danger',
                    'data' => __("Not Found")
                ];
                header("Location: " . route('orders/index.php'));
                exit();
            }
        }

    ?>


    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0 text-center"><?= __("Invoice"); ?> <?php echo sprintf("%04d",$order->id); ?></h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['sms'])){ ?>
                            <div class="my-2 alert d-print-none   <?php echo $_SESSION['sms']['background']; ?>">
                                <?php echo $_SESSION['sms']['data']; ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-dark mb-2 d-print-none" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                                <table class="table table-sm table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>PRODUCT</th>
                                            <th>QTY</th>
                                            <th>PRICE</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        <?php while($orderDetail = $myOrders->fetch_object()){ ?>
                                            <tr>
                                                <td><?= ++$i; ?></td>
                                                <td><?= $orderDetail->product_name; ?></td>
                                                <td><?= $orderDetail->qty; ?></td>
                                                <td>$<?= number_format($orderDetail->price,2); ?></td>
                                                <td>$<?= number_format($orderDetail->total,2); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="4" class="text-end">
                                                Grand Total
                                            </td>
                                            <td>
                                                $<?= number_format($order->grand_total,2); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        window.print();
    </script>



    <?php require(folder("layouts/footer.php")); ?>
