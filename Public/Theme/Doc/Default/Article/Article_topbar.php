<header class="am-topbar am-topbar-fixed-top">
    <div class="">
        <div class="am-collapse am-topbar-collapse am-topbar-hover " id="doc-topbar-collapse">

            <ul class="am-nav am-nav-pills am-topbar-nav pes-article-nav">
                <li class="am-dropdown">
                    <a href="/"><?= $system['siteTitle'] ?></a>
                </li>

                <li class="am-dropdown">
                    <a class="am-dropdown-toggle" href="<?= $label->url('Doc-Article-index', ['id' => $doc['doc_id']]) ?>"><?= $doc['doc_title'] ?></a>
                    <div class="am-dropdown-layer">
                        <ul class="am-dropdown-content">
                            <?php foreach(\Model\Doc::getDocList() as $item): ?>
                                <li class="<?= $item['doc_id'] == $doc['doc_id'] ? 'am-active' : '' ?>">
                                    <a href="<?= $label->url('Doc-Article-index', ['id' => $item['doc_id']]) ?>"><?= $item['doc_title'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>

                <?php if(!empty($article_title)): ?>
                <li>
                    <a class="pes-article-nav-title" href="javascript:;"><?= $article_title ?></a>
                </li>
                <?php endif; ?>

            </ul>


            <?php require_once THEME_PATH.'/Topbar_login.php'?>
        </div>
    </div>
</header>