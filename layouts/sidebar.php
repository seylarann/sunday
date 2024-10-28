<nav class="navbar navbar-expand-lg bg-secondary d-print-none">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="<?php echo route('home'); ?>">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item <?php echo $isActive == 'home' ? 'bg-white rounded' : ''; ?>">
          <a class="nav-link <?php echo $isActive == 'home' ? 'text-dark' : 'text-white'; ?> " href="<?php echo route('home'); ?>"><?php echo __('Home'); ?></a>
        </li>
        <li class="nav-item <?php echo $isActive == 'category' ? 'bg-white rounded' : ''; ?>">
          <a class="nav-link <?php echo $isActive == 'category' ? 'text-dark' : 'text-white'; ?> " href="<?php echo route('categories'); ?>"><?php echo __('Category'); ?></a>
        </li>
        <li class="nav-item <?php echo $isActive == 'product' ? 'bg-white rounded' : ''; ?>">
          <a class="nav-link <?php echo $isActive == 'product' ? 'text-dark' : 'text-white'; ?> " href="<?php echo route('products'); ?>"><?php echo __('Product'); ?></a>
        </li>
        <li class="nav-item <?php echo $isActive == 'order' ? 'bg-white rounded' : ''; ?>">
          <a class="nav-link <?php echo $isActive == 'order' ? 'text-dark' : 'text-white'; ?> " href="<?php echo route('orders'); ?>"><?php echo __('Order'); ?></a>
        </li>
        <li class="nav-item <?php echo $isActive == 'user' ? 'bg-white rounded' : ''; ?>">
          <a class="nav-link <?php echo $isActive == 'user' ? 'text-dark' : 'text-white'; ?> " href="<?php echo route('users'); ?>"><?php echo __('User'); ?></a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo asset($_SESSION['auth_data']->photo); ?>" width="35" height="35" class="rounded-circle border border-primary" alt=""> <?php echo $_SESSION['auth_data']->name; ?>
            </button>
            <ul class="dropdown-menu" style="left:auto;right:0">

                <li><a class="dropdown-item" href="<?php echo route('change_language.php'); ?>"><?php echo $_SESSION['language'] == 'en' ? __('Engligh - Khmer') : __('Khmer - English'); ?></a></li>

                <li><a class="dropdown-item" href="<?php echo route('auth/action_logout.php'); ?>"><?php echo  __('Logout'); ?></a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


<?php

if(!isset($_SESSION['auth']) || $_SESSION['auth'] != 1){
    $path = route('auth/login.php');
    header("Location: $path");
} else {

    $u_auth = $_SESSION['auth_data'];
    if($u_auth){
        $u_id = $u_auth->id;
        $check = $mysql->query("SELECT * FROM users WHERE id = '$u_id'")->fetch_object();
        if(!$check){
            $path = route('auth/action_logout.php');
            header("Location: $path");
        }
    }
}

?>
