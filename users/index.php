<?php
        require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
        $isActive = 'user';
        $title = __('User');

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

        $total_row = "SELECT COUNT(*) as total FROM users";


        $query = "SELECT * FROM users";
        if($search){
            $query .= " WHERE name LIKE '%$search%' OR username LIKE '%$search%'";
            $total_row .= " WHERE name LIKE '%$search%' OR username LIKE '%$search%'";
        }
        if(isset($_GET['orderByColumn'])){
            $query .= " ORDER BY $orderByColumn $orderBy";
        }

        $query .= " LIMIT $per_page OFFSET $offset";



        $users = $mysql->query($query);



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
                        <h2 class="mb-0 text-center"><?= __("User"); ?></h2>
                    </div>
                    <div class="card-body">
                        <a href="<?= route('users/create.php'); ?>" class="btn btn-primary"><?= __('Create');?></a>
                        <?php if(isset($_SESSION['sms'])){ ?>
                            <div class="my-2 alert  <?php echo $_SESSION['sms']['background']; ?>">
                                <?php echo $_SESSION['sms']['data']; ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-5">
                                <form action="<?= route('users') . '?orderByColumn=name&orderBy=' . $orderBySearch . '&search=' . $search; ?>" method="GET">
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
                                    <th><?= __('Photo'); ?></th>
                                    <th>
                                        <a href="<?= route('users') . '?orderByColumn=name&orderBy=' . $orderBy . '&search=' . $search; ?>" style="text-decoration:none;">
                                            <?= __('Name'); ?>
                                            <i class="fas fa-sort"></i></a>
                                    </th>
                                    <th>
                                        <a href="<?= route('users') . '?orderByColumn=username&orderBy=' . $orderBy . '&search=' . $search; ?>" style="text-decoration:none;">
                                            <?= __('Username'); ?>
                                            <i class="fas fa-sort"></i></a>
                                    </th>
                                    <th><?= __('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php while($user = $users->fetch_object()){ ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td>
                                            <img src="<?= asset($user->photo); ?>" alt="" width="80" class="rounded">
                                        </td>
                                        <td><?= $user->name; ?></td>
                                        <td><?= $user->username; ?></td>
                                        <td>
                                            <a href="<?= route('users/edit.php') . '?id=' . $user->id; ?>"
                                                class="btn btn-success"
                                            >
                                                Edit
                                            </a>
                                            <?php
                                                $btnDelete = '<div><a class="btn btn-danger" href="'. route('users/delete.php') . '?id=' . $user->id .'">'. __('Yes') .'</a>' . '<span class="btn btn-dark ms-1">'. __('No') .'</span>' .'</div>';
                                            ?>

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
                                            <a class="page-link" href="<?= $page > 1 ? route('categories') . '?' . 'search=' . $search . "&page=" . ($page - 1) : '#'; ?>">Previous</a>
                                        </li>
                                        <?php for($i=1;$i <= $total_page; $i++){ ?>
                                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                                <a class="page-link" href="<?= route('categories') . "?page=" . $i; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php } ?>
                                        <li class="page-item <?php echo $page + 1 <= $total_page ? '' : 'disabled'; ?>">
                                            <a class="page-link" href="<?= $page + 1 <= $total_page ? route('categories') . '?' . 'search=' . $search . "&page=" . ($page + 1) : '#'; ?>">Next</a>
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
        $(function () {
            $('.over').popover({
                container : 'body',
            })
        })
    </script>

    <?php require(folder("layouts/footer.php")); ?>
