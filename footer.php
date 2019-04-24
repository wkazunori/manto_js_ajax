<footer id="footer">
  Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>. All Rights Reserved.
</footer>

<script src="js/vendor/jquery-2.2.2.min.js"></script>
<script>
  $(function() {

    // フッターを最下部に固定
    var $ftr = $('#footer');
    if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
      $ftr.attr({
        'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
      });
    }

    // メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if (msg.replace(/^[\s　]+|[\s　]+$/g, "").length) {
      $jsShowMsg.slideToggle('slow');
      setTimeout(function() {
        $jsShowMsg.slideToggle('slow');
      }, 5000);
    }

    // 画像ライブプレビュー
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover', function(e) {
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
    });
    $dropArea.on('dragleave', function(e) {
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });
    $fileInput.on('change', function(e) {
      $dropArea.css('border', 'none');
      var file = this.files[0], // 2. files配列にファイルが入っています
        $img = $(this).siblings('.prev-img'), // 3. jQueryのsiblingsメソッドで兄弟のimgを取得
        fileReader = new FileReader(); // 4. ファイルを読み込むFileReaderオブジェクト

      // 5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
        // 読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };

      // 6. 画像読み込み
      fileReader.readAsDataURL(file);

    });

    // テキストエリアカウント
    var $countUp = $('#js-count'),
      $countView = $('#js-count-view');
    $countUp.on('keyup', function(e) {
      $countView.html($(this).val().length);
    });

    // 画像切替
    var $switchImgSubs = $('.js-switch-img-sub'),
      $switchImgMain = $('#js-switch-img-main');
    $switchImgSubs.on('click', function(e) {
      $switchImgMain.attr('src', $(this).attr('src'));
    });

    // お気に入り登録・削除
    var $like,
      likeProductId;
    $like = $('.js-click-like') || null; //nullというのはnull値という値で、「変数の中身は空ですよ」と明示するためにつかう値
    likeProductId = $like.data('productid') || null;
    // 数値の0はfalseと判定されてしまう。product_idが0の場合もありえるので、0もtrueとする場合にはundefinedとnullを判定する
    if (likeProductId !== undefined && likeProductId !== null) {
      $like.on('click', function() {
        var $this = $(this);
        $.ajax({
          type: "POST",
          url: "ajaxLike.php",
          data: {
            productId: likeProductId
          }
        }).done(function(data) {
          console.log('Ajax Success');
          // クラス属性をtoggleでつけ外しする
          $this.toggleClass('active');
        }).fail(function(msg) {
          console.log('Ajax Error');
        });
      });
    }

    //----フィルター機能(ajax)---
    //フィルターに必要な要素を持つクラスを作成
    var filter_items = function() {
      this.category_select = $("#js-category").val();
      this.sort_select = $("#js-sort").val();
      this.price_select = $("#js-price").val();
      this.currentPageNum = '<?php echo $currentPageNum; ?>';
      this.currentMinNum = '<?php echo $currentMinNum; ?>';
    };

    //カテゴリフィルター
    $("#js-category").change(function() {

      var items = new filter_items();

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "html",
        data: {
          c_id: items.category_select,
          sort: items.sort_select,
          price: items.price_select,
          currentPage: items.currentPageNum,
          currentMin: items.currentMinNum
        }
      }).then(function(rst, status) {
        console.log(rst);
        console.log(status);

        var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var paginationHTML = $('#js-ajax-pagination', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        $('#js-ajax-panel-list').html(panelListHTML);
        $('#js-ajax-search-title').html(seachTitleHTML);
        $('#js-ajax-pagination').html(paginationHTML);


      });
    });

    //ソートフィルター
    $("#js-sort").change(function() {

      var items = new filter_items();

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "html",
        data: {
          c_id: items.category_select,
          sort: items.sort_select,
          price: items.price_select,
          currentPage: items.currentPageNum,
          currentMin: items.currentMinNum
        }
      }).then(function(rst, status) {
        console.log(rst);
        console.log(status);

        var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var paginationHTML = $('#js-ajax-pagination', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        $('#js-ajax-panel-list').html(panelListHTML);
        $('#js-ajax-search-title').html(seachTitleHTML);
        $('#js-ajax-pagination').html(paginationHTML);


      });
    });

    //価格フィルター
    $("#js-price").change(function() {

      var items = new filter_items();

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "html",
        data: {
          c_id: items.category_select,
          sort: items.sort_select,
          price: items.price_select,
          currentPage: items.currentPageNum,
          currentMin: items.currentMinNum
        }
      }).then(function(rst, status) {
        console.log(rst);
        console.log(status);

        var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        var paginationHTML = $('#js-ajax-pagination', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
        $('#js-ajax-panel-list').html(panelListHTML);
        $('#js-ajax-search-title').html(seachTitleHTML);
        $('#js-ajax-pagination').html(paginationHTML);

      });
    });

    //----end---


  });
</script>
</body>

</html>