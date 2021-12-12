<?php
    session_start();

    $cart_item_count = 0;
    // get # of items in cart to display in cart icon
    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $event_id => $ticket_ids) {
                $cart_item_count += count($ticket_ids);
            }
        }
    }
    $is_logged_in = isset($_SESSION['loggedin']);
?>

<nav class="topnav">
    <a href="/arena350/home.php">Home</a>
    <a href="/arena350/profile.php">Profile</a>
    <a href="/arena350/about.php">About</a>

    <?php if ($is_logged_in) : ?>
    <div class="navbar-end">
        <a class="cart-link" href="/arena350/cart.php">

            <?php if ($cart_item_count > 0) : ?>
                <span><?=$cart_item_count?></span>
            <?php endif; ?>
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 x="0px" y="0px"
                 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M435.892,124.541H332.108V76.108C332.108,34.142,297.966,0,256,0s-76.108,34.142-76.108,76.108v48.432H76.108L62.27,512
			H449.73L435.892,124.541z M221.405,76.108c0-19.075,15.519-34.595,34.595-34.595c19.076,0,34.595,15.519,34.595,34.595v48.432
			h-69.189V76.108z M336.561,320.736L256,401.297l-80.561-80.561c-16.392-16.392-16.392-42.969,0-59.36
			c16.392-16.392,42.968-16.392,59.36,0l21.201,21.2l21.201-21.201c16.392-16.392,42.969-16.392,59.36,0
			C352.953,277.767,352.953,304.344,336.561,320.736z"/>
    </g>
</g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
</svg>
            </svg>
        </a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <?php endif; ?>
</nav>
