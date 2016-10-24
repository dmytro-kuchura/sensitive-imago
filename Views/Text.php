<?php
use Core\Route;
use Core\Widgets;
?>
<!DOCTYPE html>
<html lang="<?php echo Core\HTML::langHead(\I18n::$lang); ?>" dir="ltr" class="no-js">
<head>
    <?php echo Widgets::get('Head'); ?>
    <?php foreach ($_seo['scripts']['head'] as $script): ?>
        <?php echo $script; ?>
    <?php endforeach ?>
    <?php echo $GLOBAL_MESSAGE; ?>
</head>
<body class="innerPage">
<div class="pageWrapper">
    <?php echo Widgets::get('Header'); ?>
    <div class="pageContainer">
        <div class="pageSection">
            <div class="pageSize">
                <div class="grid grid--md-nowrap">
                    <?php
                    switch (Route::controller()) {
                        case 'contact':
                            echo Widgets::get('Page_AsideContacts');
                            break;
                        case 'gallery':
                            echo Widgets::get('Page_AsideGallery');
                            break;
                        case 'video':
                            echo Widgets::get('Page_AsideGallery');
                            break;
                        case 'equipment':
                            echo Widgets::get('Page_AsideEquipment');
                            break;
                        case 'features':
                            echo Widgets::get('Page_AsideEquipment');
                            break;
                        case 'models':
                            echo Widgets::get('Page_AsideEquipment');
                            break;
                        default:
                            echo Widgets::get('Page_Aside');
                            break;
                    }
                    ?>
                    <div class="grid__cell grid__cell--grow grid__cell--order-start grid__cell--md-noorder">
                        <section class="pageCenter">
                            <?php echo $_breadcrumbs; ?>
                            <div
                                class="sectionTitle sectionTitle--inner"><?php echo Core\Arr::get($_seo, 'h1'); ?></div>
                            <?php echo $_content; ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Widgets::get('Page_Devices'); ?>
    </div>
    <?php echo Widgets::get('Footer'); ?>
</div>
<?php echo Widgets::get('HiddenData'); ?>
</body>
</html>
