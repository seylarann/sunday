    <?php
        require($_SERVER['DOCUMENT_ROOT'] . '/project/config.php');
        $isActive = 'category';
        $title = __('Category');

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

        $total_row = "SELECT COUNT(*) as total FROM product_categories";


        $query = "SELECT * FROM product_categories";
        if($search){
            $query .= " WHERE name LIKE '%$search%' OR note LIKE '%$search%'";
            $total_row .= " WHERE name LIKE '%$search%' OR note LIKE '%$search%'";
        }
        if(isset($_GET['orderByColumn'])){
            $query .= " ORDER BY $orderByColumn $orderBy";
        }

        $query .= " LIMIT $per_page OFFSET $offset";



        $categories = $mysql->query($query);



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
                        <h2 class="mb-0 text-center"><?= __("Category"); ?></h2>
                    </div>
                    <div class="card-body">
                        <a href="<?= route('categories/create.php'); ?>" class="btn btn-primary">Create</a>
                        <?php if(isset($_SESSION['sms'])){ ?>
                            <div class="my-2 alert  <?php echo $_SESSION['sms']['background']; ?>">
                                <?php echo $_SESSION['sms']['data']; ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-5">
                                <form action="<?= route('categories') . '?orderByColumn=name&orderBy=' . $orderBySearch . '&search=' . $search; ?>" method="GET">
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
                                    <th>
                                        <a href="<?= route('categories') . '?orderByColumn=name&orderBy=' . $orderBy . '&search=' . $search; ?>" style="text-decoration:none;">
                                            <?= __('Name'); ?>
                                            <i class="fas fa-sort"></i></a>
                                    </th>
                                    <th>
                                        <a href="<?= route('categories') . '?orderByColumn=note&orderBy=' . $orderBy . '&search=' . $search;?>" style="text-decoration:none;">
                                            <?= __('Note'); ?> <i class="fas fa-sort"></i>
                                        </a>
                                    </th>
                                    <th><?= __('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php while($category = $categories->fetch_object()){ ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $category->name; ?></td>
                                        <td><?= $category->note; ?></td>
                                        <td>
                                            <a href="<?= route('categories/edit.php') . '?id=' . $category->id; ?>"
                                                class="btn btn-success"
                                            >
                                                Edit
                                            </a>
                                            <button
                                                class="btn btn-danger"
                                                onclick="handleDelete('<?= route('categories/delete.php') . '?id=' . $category->id; ?>',this)"
                                            >
                                                Delete
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
        function handleDelete(route,el){
            if(confirm('Are you sure?')){
                window.location.href = route;
            }
        }
    </script>

    <?php require(folder("layouts/footer.php")); ?>
