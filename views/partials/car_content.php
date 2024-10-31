<?php
/**
 * @var $data array
 */

$customizationOptions = get_option(SEDOX_VDB_MAIN_MENU_SLUG . '_customization' );
$showPhotos           = boolval( $customizationOptions['show_vehicle_photos'] ?? true );
$showDynochart        = boolval( $customizationOptions['show_dynochart'] ?? true );
$useUnbrandedPhotos   = boolval($customizationOptions['use_unbranded_photos'] ?? false);
$primaryColor         = esc_html($customizationOptions['primary_color']);
$secondaryColor       = esc_html($customizationOptions['secondary_color']);
$companyName          = esc_html($customizationOptions['company_name']);
$defaultTorqueUnits   = $customizationOptions['default_torque_units'] ?
    esc_html($customizationOptions['default_torque_units']) : 'nm';
?>

<div class="">
    <div class="vehicle-panel">
        <div class="make_model_info">
            <div class="logo_and_text">
                <div class="logo">
                    <img src="<?= $data['manufacturer']['brandLogo'] ?>" alt="" style="width:100px;">
                </div>
                <div class="make_model_text">
                    <p class="brand"><?= $data['manufacturer']['name'] ?></p>
                    <p class="model" style="color: <?= $primaryColor ?>">
                        <?= $data['model']['name'] . ' ' . $data['generation']['name'] .
                        ($data['generation']['year'] ?
                            (' (' . $data['generation']['year'] . ($data['generation']['yearEnd'] ?
                                    '-'.$data['generation']['yearEnd'] : '+')) :
                            '') . ')' ?>
                    </p>
                    <p class="engine"><?= $data['engine']['name'] ?></p>
                </div>
            </div>
            <?php if ( $showPhotos ) { ?>
                <div class="image">
                    <img src="<?= $data['generation']['photo'] ?>" alt="">
                </div>
            <?php } ?>
        </div>
        <div class="tuning-data">
            <div class="box-tuning data-box">
                <div class="box-tuning-header">
                    <h3>
                        <?= $companyName ?? esc_html__( 'Sedox Performance tuning', SEDOX_VDB_TEXT_DOMAIN ) ?>
                    </h3>
                    <span class="box-tuning-unit-switch">
                        <span class="switch-label">Nm</span>
                        <span class="ui-toggle">
                            <input type="checkbox" id="sedox-inp-tuning-units"
                                   name="imperial" <?= $defaultTorqueUnits === 'ftlb' ? 'checked' : null ?>>
                            <label for="sedox-inp-tuning-units" class="s-unit-switcher"><div></div></label>
                        </span>
                        <span class="switch-label">ft-lb</span>
                    </span>
                </div>
                <table class="tbl-tuning">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?= esc_html__( 'Original', SEDOX_VDB_TEXT_DOMAIN ) ?></th>
                        <?php for ( $i = 0; $i < count( $data['engine']['remapStages'] ?? 0 ); $i += 1 ) { ?>
                            <th style="color: <?= $primaryColor ?>"><?= sprintf( esc_html__( 'Stage %1$s' ), $i + 1 ) ?></th>
                            <th><?= esc_html__( 'Increase', SEDOX_VDB_TEXT_DOMAIN ) ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="power">
                        <td>
                            <?= esc_html__( 'Power', SEDOX_VDB_TEXT_DOMAIN ) ?>
                            <br> (<?= esc_html__( 'hp', SEDOX_VDB_TEXT_DOMAIN ) ?>)
                        </td>
                        <td><?= $data['engine']['powerHP'] ?></td>
                        <?php for ( $i = 0; $i < count( $data['engine']['remapStages'] ?? 0 ); $i += 1 ) { ?>
                            <td style="background-color: <?= $primaryColor ?>"><?= $data['engine']['remapStages'][ $i ]['powerHP'] ?></td>
                            <td style="color: <?= $primaryColor ?>">+<?= $data['engine']['remapStages'][ $i ]['powerIncreaseHP'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="torque">
                        <td>
                            <?= esc_html__( 'Torque', SEDOX_VDB_TEXT_DOMAIN ) ?>
                            <br>
                            (<span data-nm="<?= esc_html__('Nm', SEDOX_VDB_TEXT_DOMAIN) ?>" data-ftlb="<?= esc_html__('ft-lb', SEDOX_VDB_TEXT_DOMAIN) ?>"><?=
                                esc_html__( $defaultTorqueUnits === 'nm' ? 'Nm' : 'ft-lb', SEDOX_VDB_TEXT_DOMAIN )
                                ?></span>)
                        </td>
                        <td data-nm="<?= $data['engine']['torqueNm'] ?>" data-ftlb="<?= $data['engine']['torqueFtlb'] ?>">
                            <?= $defaultTorqueUnits === 'nm' ? $data['engine']['torqueNm'] : $data['engine']['torqueFtlb'] ?>
                        </td>
                        <?php for ( $i = 0; $i < count( $data['engine']['remapStages'] ?? 0 ); $i += 1 ) { ?>
                            <td data-nm="<?= $data['engine']['remapStages'][$i]['torqueNm'] ?>" data-ftlb="<?= $data['engine']['remapStages'][$i]['torqueFtlb'] ?>"
                                style="background-color: <?= $secondaryColor ?>">
                                <?= $defaultTorqueUnits === 'nm' ? $data['engine']['remapStages'][ $i ]['torqueNm'] : $data['engine']['remapStages'][ $i ]['torqueFtlb'] ?>
                            </td>
                            <td  data-nm="<?= $data['engine']['remapStages'][$i]['torqueIncreaseNm'] ?>" data-ftlb="<?= $data['engine']['remapStages'][$i]['torqueIncreaseFtlb'] ?>"
                                 style="color: <?= $secondaryColor ?>">
                                +<?= $defaultTorqueUnits === 'nm' ? $data['engine']['remapStages'][ $i ]['torqueIncreaseNm'] : $data['engine']['remapStages'][ $i ]['torqueIncreaseFtlb'] ?>
                            </td>
                        <?php } ?>
                    </tr>
                    </tbody>
                </table>
                <?php foreach($data['engine']['remapStages'] as $i => $stage) {
                    if($stage['modifications']) { ?>
                        <p class="modifications">
                            <span class="font-weight-bold">
                                <?= esc_html__( 'Stage', SEDOX_VDB_TEXT_DOMAIN ) .
                                ' ' . ($i + 1) . ' ' .
                                esc_html__( 'Modifications required', SEDOX_VDB_TEXT_DOMAIN ) . ':' ?>
                            </span>
                             <?= esc_html($stage['modifications'])
                            ?>
                        </p>
                    <?php
                    }
                    ?>
                <?php } ?>
            </div>
            <div class="box-specs data-box data-box--end">
                <h3>
                    <?= esc_html__( 'Engine specifications', SEDOX_VDB_TEXT_DOMAIN ) ?>:
                </h3>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Engine type', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['type'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Fuel', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['fuelName'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Capacity', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['capacity'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Cylinders', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['cylinders'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Engine code', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['engineCode'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'ECU', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['ecu'] ?? '-' ?></span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'TCU', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value"><?= $data['engine']['tcu'] ?? '-' ?></span>
                </p>
            </div>
        </div>

        <div class="tuning-data bordered-top">
            <div class="available-options data-box">
                <h3>
                    <?= esc_html__( 'Available options', SEDOX_VDB_TEXT_DOMAIN ) ?>:
                </h3>
                <?php if($data['engine']['options']) { ?>
                <ul>
                    <?php foreach ($data['engine']['options'] as $option) { ?>
                    <li class="">
                        <div class="image border">
                            <img src="<?= $option['icon'] ?>" alt="">
                        </div>
                        <div class="name"><?= $option['name'] ?></div>
                    </li>
                    <?php } ?>
                </ul>
                <?php } else { ?>
                    <p>-</p>
                <?php } ?>
            </div>
            <div class="tuning-tools data-box data-box--end">
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Work method', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value">
                        <?= $data['engine']['readMethods'] ? implode(', ', $data['engine']['readMethods']) : '-' ?>
                    </span>
                </p>
                <p class="data_et">
                    <span class="valname"><?= esc_html__( 'Tools', SEDOX_VDB_TEXT_DOMAIN ) ?>:</span>
                    <span class="value">
                        <?= $data['engine']['readTools'] ? implode(', ', $data['engine']['readTools']) : '-' ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
    <?php if ( $showDynochart ) { ?>
        <div class="dynochart">
            <h3>
                <?= esc_html__( 'Dynochart', SEDOX_VDB_TEXT_DOMAIN ) ?>:
            </h3>
            <canvas id="sedox-catalog-dynochart"
                    class="chart-canvas"
                    style="max-height: 400px;"></canvas>
        </div>
    <?php } ?>

</div>
