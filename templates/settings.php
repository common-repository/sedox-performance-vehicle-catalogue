<div id="sedox-tuningfiles" class="wrap sedox-vdb">
    <div class="plugin_title">
        <img src="<?= plugin_dir_url( __DIR__ ) . 'assets/images/SedoxVDB_WP_Plugin_Title.png' ?>" alt="">
    </div>
    <h1>Sedox Performance Vehicle Catalog Settings</h1>

    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-configuration">Configuration</a></li>
        <li class=""><a href="#tab-customize">Customize</a></li>
        <li class=""><a href="#tab-support">Support</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-configuration-panel" class="tab-pane active">
            <form method="post" action="options.php" id="sedox-settings-form">
                <?php
                settings_fields( 'sedox_catalog_configuration' );
                do_settings_sections( 'sedox_catalog_configuration' );
                ?>

                <p style="font-weight: 600; font-size: 14px;">Usage:</p>
                <p class="important">
                    Copy and paste this shortcode directly into the page, post or widget you'd like to
                    display the Sedox Performance Vehicle Catalog:
                </p>
                <div class="shortcode">[sedox-catalogue]</div>

                <p class="submit">
                    <button class="button button-primary" type="submit">
                        <span class="d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Save</span>
                    </button>
                </p>
            </form>
        </div>

        <div id="tab-customize-panel" class="tab-pane">
            <form method="post" action="options.php">
                <?php
                settings_fields( 'sedox_catalog_customization' );
                do_settings_sections( 'sedox_catalog_customization' );
                ?>
                <p class="submit">
                    <button class="button button-primary" type="submit">
                        <span class="d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Save</span>
                    </button>
                </p>
            </form>
        </div>

        <div id="tab-support-panel" class="tab-pane tab-support">
            <br>
            <p class="color-sedoxred" style="font-weight: 500">This plugin is a part of the Sedox Performance API access
                packages!</p>
            <p>Purchase and initialization should be set through your account on www.tuningfiles.com.</p>
            <p>Please register and purchase API access <a href="https://tuningfiles.com" target="_blank"
                                                          class="color-sedoxred">HERE</a> or contact us on one of the
                emails below:</p>

            <div class="logos-image">
                <img src="<?= plugin_dir_url( __DIR__ ) . 'assets/images/sp_tf_logos.png' ?>" alt="">
            </div>
            <div class="contactbox">
                <p class="cont_title">Sedox Performance AS</p>
                <p class="cont_text">
                    Knud Holms Gate 1, 4005 Stavanger,<br>
                    NORWAY
                </p>

                <p class="cont_title">General enquiries:</p>
                <p class="cont_text">
                    <a href="mailto:info@sedox.com">info@sedox.com</a>
                </p>

                <p class="cont_title">Tech support:</p>
                <p class="cont_text">
                    <a href="mailto:support@sedox.com">support@sedox.com</a>
                </p>

                <p class="cont_title">Commercial enquiries:</p>
                <p class="cont_text">
                    <a href="mailto:sales@sedox.com">sales@sedox.com</a>
                </p>

                <p class="cont_title">
                    <a href="https://www.tuningfiles.com" target="_blank">www.tuningfiles.com</a>
                </p>
            </div>
        </div>
    </div>
</div>
