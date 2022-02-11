<!-- пост-ссылка -->
<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= h($url); ?>" title="Перейти по ссылке" target="_blank">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="../img/logo-vita.jpg" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?= h($title); ?></h3>
                    <p>Переходи по ссылке</p>
                    <span><?= h($url); ?></span>
                </div>
                <svg class="post-link__arrow" width="11" height="16">
                    <use xlink:href="#icon-arrow-right-ad"></use>
                </svg>
            </div>
        </a>
    </div>
</div>
