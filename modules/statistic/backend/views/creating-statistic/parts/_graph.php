<div class="circulation-statistic-index">
    <div style="border-bottom: 5px solid #367fa9; margin-bottom: 25px">
        <?=
        \miloschuman\highcharts\Highcharts::widget([
            'options' => [
                'title'       => ['text' => $title],
                'plotOptions' => [
                    'pie' => [
                        'cursor' => 'pointer',
                    ],
                ],
                'series'      => [
                    [// new opening bracket
                        'type' => 'pie',
                        'name' => Yii::t('backend', 'Users'),
                        'data' => [
                            [Yii::t('backend', 'Start'), $model->start],
                            [Yii::t('backend', 'Completed'), $model->completed],
                            [Yii::t('backend', 'Email'), $model->email],
                            [Yii::t('backend', 'Email Verify Start'), $model->email_verify_start],
                            [Yii::t('backend', 'Email Verify End'), $model->email_verify_end],
                            [Yii::t('backend', 'Card'), $model->card],
                            [Yii::t('backend', 'Card Verify Start'), $model->card_verify_start],
                            [Yii::t('backend', 'Card Verify End'), $model->card_verify_end],
                            [Yii::t('backend', 'Canceled Preorder'), $model->canceled_preorder],
                            [Yii::t('backend', 'Canceled Continued'), $model->canceled_continued],
                        ],
                    ] // new closing bracket
                ],
            ],
        ]);
        ?>
    </div>

</div>