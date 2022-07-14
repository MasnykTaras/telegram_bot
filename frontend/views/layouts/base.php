<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@frontend/views/layouts/_clear.php')
?>
<div class="flexbox-item header">
    <div class="container">
<!--        <a href="https://365cash.co" class="logo"><img src="/img/logo.png" alt=""></a>-->
    </div>
</div>
<div class="flexbox-item fill-area content flexbox-item-grow">
    <?php echo $content ?>
</div>
<div class="scroll-icon"></div>
<footer class="flexbox-item footer">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-xs-12">
<!--                <img src="/img/logo.png" class="img-responsive">
                <p><a href="mailto:hello@365cash.co">hello@365cash.co</a></p>-->
            </div>
            <div id="footer-menu" class="col-md-8 col-xs-12">
                <ul class="nav navbar-nav">
                    <li><a href="https://365cash.co/page/security">Политика конфиденциальности</a></li>
                    <li><a href="https://365cash.co/page/rules">Правила</a></li>
                    <li><a href="https://365cash.co/page/agreement">Условия и положения</a></li>
                    <!--                    <li>--><!--</li>-->
                </ul>
            </div>
            <div class="col-md-2 col-xs-12 text-right">
    <!--                <img src="/img/visamc.png?t=1">
                    <a href="https://www.bestchange.ru/365cash-exchanger.html" target="_blank"><img src="/img/bestchange.png" title="Обмен QIWI, Bitcoin, Tether, AdvCash" alt="Мониторинг обменных пунктов BestChange" width="120" height="25" border="0" style="margin: 10px 0px 0px 0px"></a>-->
            </div>
        </div>
        <div class="row"><div class="col-md-12 text-center"><p>© 2015 - <?= date('Y') ?></p></div></div>
    </div>

</footer>
<?php $this->endContent() ?>