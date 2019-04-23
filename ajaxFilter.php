<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  AjaxFilter開始  ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


if (!empty($_POST)) {
  debug('ajaxPostされた値確認:' . print_r($_POST, true));
  $dbProductData = getProductList($_POST['c_id'], $_POST['sort'], $_POST['price'], $_POST['currentMin']);
  debug('ajaxRst:' . print_r($dbProductData, true));
  $currentMinNum = $_POST['currentMin'];
  $currentPageNum = $_POST['currentPage'];
}

?>

<!-- Main -->
<section id="main">

  <div class="search-title" id="js-ajax-search-title">
    <div class="search-left">
      <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の商品が見つかりました
    </div>
    <div class="search-right">
      <span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum + 1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum + count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
    </div>
  </div>

  <div class="panel-list" id='js-ajax-panel-list'>
    <?php
    foreach ($dbProductData['data'] as $key => $val) :
      ?>
      <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">
        <div class="panel-head">
          <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>">
        </div>
        <div class="panel-body">
          <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
        </div>
      </a>
    <?php
  endforeach;
  ?>
  </div>
  <div class="js-ajax-pagination">
    <?php pagination($currentPageNum, $dbProductData['total_page']); ?>
  </div>
</section>