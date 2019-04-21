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

    //カテゴリフィルター
    $("#js-category").change(function() {
      var sort_select = $("#js-sort").val();
      var category_select = $(this).val();
      var price_select = $("#js-price").val();
      var currentMinNum = '<?php echo $currentMinNum; ?>';

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "json",
        data: {
          c_id: category_select,
          sort: sort_select,
          price: price_select,
          current: currentMinNum
        }
      }).then(function(rst, status) {

        console.log(rst);
        console.log(status);

        $.ajax({
          type: "POST", // POSTで送る
          url: "index.php", // 送信先のPHP
          dataType: 'html',
          data: rst
        }).done(function(rst, status) {
          // 丸々生成された画面がrstに入っている(新しい商品リストが展開されたver)
          var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          $('#js-ajax-panel-list').html(panelListHTML);
          $('#js-ajax-search-title').html(seachTitleHTML);
        });
      });
    });

    //ソートフィルター
    $("#js-sort").change(function() {
      var sort_select = $(this).val();
      var category_select = $("#js-category").val();
      var price_select = $("#js-price").val();
      var currentMinNum = '<?php echo $currentMinNum; ?>';

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "json",
        data: {
          c_id: category_select,
          sort: sort_select,
          price: price_select,
          current: currentMinNum
        }
      }).then(function(rst, status) {
        console.log(rst);
        console.log(status);
        // rstを使ってでまるごと書き換える処理へ $(rst).$(rst).find('取得したい要素名を指定').html()といった感じ ajaxPanel-listを作って $('中を変えたい要素名').html(ajaxPanel-list)
        $.ajax({
          type: "POST", // POSTで送る
          url: "index.php", // 送信先のPHP
          dataType: 'html',
          data: rst
        }).done(function(rst, status) {
          // 丸々生成された画面がrstに入っている(新しい商品リストが展開されたver)
          var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          $('#js-ajax-panel-list').html(panelListHTML);
          $('#js-ajax-search-title').html(seachTitleHTML);
        });
      });
    });

    //価格フィルター
    $("#js-price").change(function() {
      var sort_select = $("#js-sort").val();
      var category_select = $("#js-category").val();
      var price_select = $(this).val();
      var currentMinNum = '<?php echo $currentMinNum; ?>';

      $.ajax({
        type: "post",
        url: "ajaxFilter.php",
        dataType: "json",
        data: {
          c_id: category_select,
          sort: sort_select,
          price: price_select,
          current: currentMinNum
        }
      }).then(function(rst, status) {
        console.log(rst);
        console.log(status);
        $.ajax({
          type: "POST", // POSTで送る
          url: "index.php", // 送信先のPHP
          dataType: 'html',
          data: rst //新しく作った商品リストを渡す
        }).done(function(rst, status) {
          // 丸々生成された画面がrstに入っている(新しい商品リストが展開されたver)
          var panelListHTML = $('#js-ajax-panel-list', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          var seachTitleHTML = $('#js-ajax-search-title', $(rst)).html(); // 取得したHTMLからcontentsタグの中身を抽出
          $('#js-ajax-panel-list').html(panelListHTML);
          $('#js-ajax-search-title').html(seachTitleHTML);
        });
      });
    });

    //todo: .search-titleの書き換え追加
    //----end---


  });
</script>
</body>

</html>