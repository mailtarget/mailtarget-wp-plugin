<div class="mtg-form-plugin">
    <div class="wrap">
        <h1 class="wp-heading-inline">MTARGET - WooCommerce</h1>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab" href="">Connect</a>
            <a class="nav-tab" href="">Store Setting</a>
            <a class="nav-tab" href="">Audience Default</a>
            <a class="nav-tab" href="">Audience Setting</a>
            <a class="nav-tab" href="">Sync</a>
            <a class="nav-tab" href="">Logs</a>
        </h2>
        <?php
        if ( class_exists( 'WooCommerce' ) ) {?>
            <div class="success-content">Welcome to MTARGET WooCommerce</div>
            <?php
        } else {
            ?>
            <div class="error-content">You don't appear to have WooCommerce activated, please activate the WooCommerce Plugin.<br> If you have not installed Woocommerce, <a href="https://wordpress.org/plugins/woocommerce/">click here</a> to install and activate.</div>
            <?php
        }
        ?>
    </div>
</div>