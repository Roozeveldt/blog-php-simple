<!-- пост-ссылка -->
<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= htmlspecialchars($url); ?>" title="Перейти по ссылке" target="_blank">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($url); ?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                    <h3><?= htmlspecialchars($title); ?></h3>
                    <p><?= htmlspecialchars($url); ?></p>
                </div>
            </div>
        </a>
    </div>
</div>
