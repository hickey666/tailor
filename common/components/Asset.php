<?php
namespace common\components;
use yii\web\AssetManager;
use yii\helpers\Url;
use Yii;


class Asset extends AssetManager
{

    public $version = true;

    /**
     * 方法重写,静态资源文件后面增加版本号 ?v=1.23
     *
     * @param [type] $bundle
     * @param [type] $asset
     * @return void
     */
    public function getAssetUrl($bundle, $asset)
    {
        if (($actualAsset = $this->resolveAsset($bundle, $asset)) !== false) {
            if (strncmp($actualAsset, '@web/', 5) === 0) {
                $asset = substr($actualAsset, 5);
                $basePath = Yii::getAlias('@webroot');
                $baseUrl = Yii::getAlias('@web');
            } else {
                $asset = Yii::getAlias($actualAsset);
                $basePath = $this->basePath;
                $baseUrl = $this->baseUrl;
            }
        } else {
            $basePath = $bundle->basePath;
            $baseUrl = $bundle->baseUrl;
        }

        if (!Url::isRelative($asset) || strncmp($asset, '/', 1) === 0) {
            return $asset;
        }

        if ($this->version && $version = Yii::$app->params['version']) {
            return "$baseUrl/$asset?v=$version";
        }

        return "$baseUrl/$asset";
    }
}
