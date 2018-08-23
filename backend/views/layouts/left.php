<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/assets/images/touxiang.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '菜单', 'options' => ['class' => 'header']],
                    ['label' => '首页', 'icon' => 'home', 'url' => ['/']],
                    ['label' => '客户', 'icon' => 'users', 'url' => ['/customer']],

                ],
            ]
        ) ?>

    </section>

</aside>
