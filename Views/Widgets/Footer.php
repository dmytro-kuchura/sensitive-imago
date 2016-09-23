<footer class="pageFooter">
    <div class="pageSize">
        <div class="grid grid--justify-around grid--xl-justify-between">
            <div class="grid__cell _mr-x2 _mb-x2">
                <a href="index.html" class="logo__link">
                    <div class="logo logo--small">
                        <div class="logo__image">
                            <svg>
                                <use xlink:href="<?php echo Core\HTML::media('sprite.svg#logo'); ?>"></use>
                            </svg>
                        </div>
                        <div class="logo__text">
                            <big><?php echo __('Сенситив Имаго'); ?></big>
                            <small><?php echo __('Медицинское оборудование'); ?></small>
                            <span
                                class="logo__copyright"><?php echo Core\Config::get('basic.copy-' . \I18n::$lang); ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="grid__cell grid__cell--grow">
                <div class="grid grid--2 grid--md-4 grid--space">
                    <div class="grid__cell">
                        <div class="columnCaption"><?php echo __('Меню'); ?></div>
                        <div class="columnContent">
                            <?php if (Core\Arr::get($menu, 1, [])): ?>
                                <ul>
                                    <?php foreach ($menu[2] AS $key => $value): ?>
                                        <li>
                                            <a href="<?php echo Core\HTML::link($value->url); ?>"><?php echo $value->name; ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="grid__cell">
                        <div class="columnCaption"><?php echo __('Адрес'); ?></div>
                        <div
                            class="columnContent"><?php echo str_replace(['<p>', '</p>'], '', $contacts->location); ?></div>
                    </div>
                    <div class="grid__cell">
                        <div class="columnCaption"><?php echo __('Время работы'); ?></div>
                        <div
                            class="columnContent"><?php echo str_replace(['<p>', '</p>'], '', $contacts->time); ?></div>
                    </div>
                    <div class="grid__cell">
                        <div class="columnCaption"><?php echo __('Наши контакты'); ?></div>
                        <div class="columnContent">
                            <?php if (\I18n::$lang == 'ru'): ?>
                                <a href="tel:+38 (068) 201-ХХ-ХХ">+38 (068) 201-ХХ-ХХ</a><br>
                                <a href="tel:+38 (044) 227-ХХ-ХХ">+38 (044) 227-ХХ-ХХ</a><br>
                            <?php endif; ?>
                            <a href="mailto:ХХХХХХХ@gmail.com">ХХХХХХХ@gmail.com</a><br>
                            Skype: ХХХХХХ<br>
                            <a class="inverseLink showContacts__link _color-white"
                               href="#"><?php echo __('Показать контакты'); ?></a>
                            <button
                                class="button button--inverse-white button--expand _mt"><?php echo __('Обратная связь'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>