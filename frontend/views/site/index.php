<picture>
    <source srcset='/img/body.webp' type='image/webp'>
    <!--<source srcset='/img/body.jp2' type='image/jp2'>-->
    <img  class="background-img" srcset='/img/body.jpg' alt='myimage'>
</picture>
<div class="item-flex">
    <div class="text-area">
        <h1>Hellow world</h1>
    </div>
    <div class="phone-area">
        <div class="phone-hand">
            <div class="slide-area">
                <div class="slide active" data-slide="1">
                    <p><?= Yii::t('frontend', 'To start you need only find our bot in Telegram by {name} name and press', ['name' => 'titile']) ?>  <span>Start</span></p>
                </div>
                <div class="slide" data-slide="2">
                    <p>Some text 2</p>
                </div>
                <div class="slide" data-slide="3">
                    <p>Some text 3</p>
                </div>
            </div>
        </div>
    </div>
</div>