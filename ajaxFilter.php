<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  AjaxFilter開始  ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// Ajax処理
//================================

if (!empty($_POST)) {
  debug('ajaxPostされた値確認:' . print_r($_POST, true));
  $ajaxRst = getProductList($_POST['c_id'], $_POST['sort'], $_POST['price']);
  // debug('ajaxRstの値確認:' . print_r($ajaxRst, true));
  // $_SESSION['ajax_filter_product'] = $ajaxRst;
  echo json_encode($ajaxRst);
}
