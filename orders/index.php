<?php
        require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
        $isActive = 'order';
        $title = __('Order');

        require(folder("layouts/header.php"));
        require(folder("layouts/sidebar.php"));
    ?>

    <!-- fetch category data  -->
    <?php
        $orderBySearch = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'ASC';
        $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'ASC';
        $orderByColumn = isset($_GET['orderByColumn']) ? $_GET['orderByColumn'] : 'id';
        $search = isset($_GET['search']) ? $_GET['search'] : '';


        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 3;

        $offset = $page == 0 ? 0 : ($page * $per_page) - $per_page;

        $total_row = "SELECT COUNT(*) as total FROM orders";


        $query = "SELECT orders.* FROM orders";
        if($search){
            $query .= " WHERE grand_total LIKE '%$search%'";
            $total_row .= " WHERE grand_total LIKE '%$search%'";
        }
        if(isset($_GET['orderByColumn'])){
            $query .= " ORDER BY $orderByColumn $orderBy";
        }

        $query .= " LIMIT $per_page OFFSET $offset";



        $orders = $mysql->query($query);



        $total_row = $mysql->query($total_row)->fetch_object()->total;
        $total_page = ceil($total_row / $per_page);

        $orderBy = $orderBy == 'ASC' ? 'DESC' : 'ASC';

    ?>



    <div class="container my-4">
        <div class="row">
            <div class="col"></div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0 text-center"><?= __("Order"); ?></h2>
                    </div>
                    <div class="card-body">
                        <a href="<?= route('orders/create.php'); ?>" class="btn btn-primary"><?php echo __('Create'); ?></a>
                        <?php if(isset($_SESSION['sms'])){ ?>
                            <div class="my-2 alert  <?php echo $_SESSION['sms']['background']; ?>">
                                <?php echo $_SESSION['sms']['data']; ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-5">
                                <form action="<?= route('products') . '?orderByColumn=grand_total&orderBy=' . $orderBySearch . '&search=' . $search; ?>" method="GET">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><?= __('Search'); ?></span>
                                        <input type="search" value="<?= $search; ?>" name="search" class="form-control form-control-sm">
                                        <button type="submit" class="btn btn-sm btn-dark"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-hover text-center" style="vertical-align:middle;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Date</th>
                                    <th>
                                        <a href="<?= route('orders') . '?orderByColumn=id&orderBy=' . $orderBy . '&search=' . $search; ?>" style="text-decoration:none;">
                                            <?= __('Code'); ?>
                                            <i class="fas fa-sort"></i></a>
                                    </th>
                                    <th>
                                        <a href="<?= route('orders') . '?orderByColumn=grand_total&orderBy=' . $orderBy . '&search=' . $search; ?>" style="text-decoration:none;">
                                            <?= __('Grand Total'); ?>
                                            <i class="fas fa-sort"></i></a>
                                    </th>
                                    <th><?= __('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php while($order = $orders->fetch_object()){ ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= date('d-m-Y h:i A', strtotime($order->order_date . "+7 hours")); ?></td>
                                        <td><?= sprintf("%04d",$order->id); ?></td>
                                        <td>$<?= number_format($order->grand_total,2); ?></td>
                                        <td>
                                            <?php
                                                $btnDelete = '<div><a class="btn btn-danger" href="'. route('orders/delete.php') . '?id=' . $order->id .'">'. __('Yes') .'</a>' . '<span class="btn btn-dark ms-1">'. __('No') .'</span>' .'</div>';
                                            ?>

                                            <a target="_blank" href="<?= route('orders/print.php') . '?order_id=' . $order->id; ?>"
                                            class="btn btn-dark">
                                                <i class="fa fa-print"></i>
                                                Print
                                            </a>

                                            <button
                                                type="button"
                                                class="btn btn-md btn-danger over"
                                                data-bs-toggle="popover"
                                                data-bs-trigger="focus"
                                                data-bs-title="<?= __('Are you sure ?');?>"
                                                data-bs-html="true"
                                                data-bs-content='<?= $btnDelete; ?>'
                                            >
                                                <?= __('Delete'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-12">
                                <nav aria-label="Page navigation ">
                                    <ul class="pagination justify-content-end">
                                        <li class="page-item <?php echo $page > 1 ? '' : 'disabled'; ?>">
                                            <a class="page-link" href="<?= $page > 1 ? route('orders') . '?' . 'search=' . $search . "&page=" . ($page - 1) : '#'; ?>">Previous</a>
                                        </li>
                                        <?php for($i=1;$i <= $total_page; $i++){ ?>
                                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?= route('orders') . "?page=" . $i; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php } ?>
                                        <li class="page-item <?php echo $page + 1 <= $total_page ? '' : 'disabled'; ?>">
                                            <a class="page-link" href="<?= $page + 1 <= $total_page ? route('orders') . '?' . 'search=' . $search . "&page=" . ($page + 1) : '#'; ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col"></div>
        </div>
    </div>





    <script>
        function handleDelete(route,el){
            if(confirm('Are you sure?')){
                window.location.href = route;
            }
        }


        $(function () {
            $('.over').popover({
                container : 'body',
            })
        })

    </script>

    <?php require(folder("layouts/footer.php")); ?>
