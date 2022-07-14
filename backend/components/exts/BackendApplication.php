<?php
/**
 * author: atx
 * Date: 08.01.19
 * Time: 17:03
 */

namespace backend\components\exts;

use common\models\Whitelist;
use common\components\helpers\IpHelper;
use yii\helpers\ArrayHelper;
use yii\web\Application;

class BackendApplication extends Application
{
    public function run()
    {
        $ip = null;

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ipItem) {
                    $ipItem = trim($ipItem);
                    if (filter_var($ipItem, FILTER_VALIDATE_IP, YII_ENV == 'prod' ? FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE : FILTER_FLAG_NO_PRIV_RANGE) !== false) {
                        $ip = $ipItem;
                    }
                }
            }
        }

        if (!IpHelper::ipInList($ip, ArrayHelper::map(Whitelist::find()->all(), 'id', 'ip'))) {
            $this->response->statusCode = 403;
            $this->end();
        }

        parent::run();
    }
}