//--------------------------------------------------------------------------------------------------
//- 共通Function
//--------------------------------------------------------------------------------------------------

$(function() {
    dispLoading("loading...");  
  
  });
  
  $(window).load(function () {
    removeLoading();
  });
  
  //10秒たったら強制的にロード画面を非表示
  $(function(){
    setTimeout('stopload()',10000);
  });
    
  function stopload(){
    removeLoading();
  }
  


  /**
   * 
   * @param {*} obj_link 
   */
  function NavMenuOpen(obj_link)
  {
    // 全てのhas-treeviewからActiveを削除する
    $(".has-treeview").removeClass("menu-open");
    obj_link.addClass("menu-open");
  }
  
  /**
   * 
   * @param {*} obj_link 
   * @param {*} obj_title_link 
   */
  function AddActiveClass(obj_link, obj_title_link = null) {
    // 全てのnav-linkからActiveを削除する
    $(".nav-link").removeClass("active");
  
    // 対象のnav-linkにActiveを追加する
    obj_link.addClass("active");
  
    if(obj_title_link != null){
      obj_title_link.addClass("active");
    }
  }
  
/**
 * Loading イメージ表示関数
 * @param {*} msg 画面に表示する文言
 */
function dispLoading(msg){
    // 引数なし（メッセージなし）を許容
    if( msg == undefined ){
        msg = "";
    }

    // 画面表示メッセージ
    var dispMsg = "<div class='loadingMsg'>" + msg + "</div>";

    // ローディング画像が表示されていない場合のみ出力
    if($("#loading").length == 0){
        $("body").append("<div id='loading'>" + dispMsg + "</div>");
    }
}

/**
 * Loading イメージ削除関数
 */
function removeLoading(){
    $("#loading").remove();
}

//--------------------------------------------------------------------------------------------------
//- 共通API
//--------------------------------------------------------------------------------------------------
$(function () {
    //----------------------------------------------------
    //- 共通API
    //----------------------------------------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      
    $.wait = function (msec) {
        // Deferredのインスタンスを作成
        var d = new $.Deferred;
    
        setTimeout(function () {
          // 指定時間経過後にresolveしてdeferredを解決する
          d.resolve(msec);
        }, msec);
    
        return d.promise();
    };

    AjaxAPI = {
        // Ajax通信
        callServerMethod: function (action, request) {
            var defer = $.Deferred();
            $.ajax({
                url: action,
                method: 'post',
                dataType: 'text',
                data: request,          // サーバに渡すデータ
                processData: false,     // Ajaxがdataを整形しない指定
                contentType: false,     // contentTypeもfalseに指定
                success: defer.resolve,
                error: defer.reject
            });
            return defer.promise();
        },

        // Ajax通信
        callAjax: function (action, request, successCallback, warnCallback, errorCallback) {
            $.ajax({
                url: action,
                method: 'post',
                dataType: 'text',
                data: request,          // サーバに渡すデータ
                processData: false,     // Ajaxがdataを整形しない指定
                contentType: false      // contentTypeもfalseに指定
            })
            
            .done(function (res) {
                var obj = JSON.parse(res); // 文字列=>オブジェクト
                if (successCallback != null) {
                    // 正常
                    successCallback(obj);
                }
                else {
                    AjaxAPI.successDefaultFunc(obj.result.message);
                }
                removeLoading();
            })
            
            .fail(function (jqXHR, textStatus, errorThrown) {
                // サーバ通信エラー
                if (errorCallback != null) {
                    errorCallback(jqXHR, textStatus, errorThrown);
                }
                else {
                    AjaxAPI.failDefaultFunc(jqXHR, textStatus, errorThrown);
                }
                removeLoading();
            });
        },

        // サーバ通信成功時の通常処理
        successDefaultFunc: function (message) {
            removeLoading();
            if (message != null && message != "") {
                $.notifyBar({cssClass: "success", html: message});
            }
            else {
                $.notifyBar({cssClass: "success", html: 'success!!!'});
            }
        },
        
        // サーバエラー(想定内)時の通常処理
        warnDefaultFunc: function (message) {
            removeLoading();
            $.notifyBar({cssClass: "warning", html: message});
        },
        
        // サーバエラー(想定外)時の通常処理
        errorDefaultFunc: function (message) {
            removeLoading();
            $.notifyBar({cssClass: "error", html: message});
        },
        
        // サーバエラー(想定外)時の通常処理
        failDefaultFunc: function (jqXHR, textStatus, errorThrown) {
            removeLoading();
            $.notifyBar({cssClass: "error", html: jqXHR.status + ":" + jqXHR.statusText});
        }
    
        /* 呼び出しもとのサンプル */
        /*
        $('#btnTantoList1Add').click(function () {
            var reqData = new FormData();
            reqData.append("data1", "TestData1");
            reqData.append("data2", "TestData2");
            AjaxAPI.callServerMethod(
                "<?php echo $this->Url->build(['controller' => 'S302', 'action' => 'ajaxListener']); ?>",
                reqData
            )
            .done(function (response) {
                $.notifyBar({cssClass: "success", html: response});
            })
            .fail(function (jqXHR) {
                $.notifyBar({cssClass: "error", html: jqXHR.status + ":" + jqXHR.statusText});
            });
        });
        */
    };

    BootstrapMessage = {
        confirm: function ({message, title, funcOk, funcCancel, id, caption_ok, caption_cancel}){
            id             = (id             != null) ? id : "#dynamic_message_box";
            caption_ok     = (caption_ok     != null) ? caption_ok : "OK";
            caption_cancel = (caption_cancel != null) ? caption_cancel : "Cancel";
            var contents = '';
            contents += '  <div class="modal-dialog modal-dialog-centered">';
            contents += '    <div class="modal-content">';
            contents += '      <div class="modal-header">';
            contents += '        <h5 class="modal-title" id="confirm_title">タイトル</h5>';
            contents += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            contents += '          <span aria-hidden="true">&times;</span>';
            contents += '        </button>';
            contents += '      </div>';
            contents += '      <div class="modal-body" id="confirm_message">';
            contents += '        メッセージ内容';
            contents += '      </div>';
            contents += '      <div class="modal-footer">';
            contents += '        <button type="button" class="btn btn-success" id="confirm_btn_ok" name="deletebtn" >OK</button>';
            contents += '        <button type="button" class="btn btn-secondary" id="confirm_btn_cancel" data-dismiss="modal">キャンセル</button>';
            contents += '      </div>';
            contents += '    </div>';
            contents += '  </div>';
            
            // HTMLコンテンツの動的生成
            $(id).append(contents);
            $("#confirm_title").html(title);
            $("#confirm_message").html(message);
            $("#confirm_btn_ok").html(caption_ok);
            $("#confirm_btn_cancel").html(caption_cancel);

            // OKボタン押下イベントハンドラ
            $("#confirm_btn_ok").get(0).onclick = function(){
                if (funcOk != undefined && funcOk != null) {
                    funcOk();
                }
                $('#dynamic_message_box').modal('hide');
                $("#"+message_id).empty();
            };

            // キャンセルボタン押下イベントハンドラ
            $("#confirm_btn_cancel").get(0).onclick = function(){
                if (funcCancel != undefined && funcCancel != null) {
                    funcCancel();
                }
                $('#dynamic_message_box').modal('hide');
                $("#"+message_id).empty();
            };

            $('#dynamic_message_box').modal('show');  
        },

        show: function ({message, title, funcOk, id, caption_ok}){
            id             = (id             != null) ? id : "#dynamic_message_box";
            caption_ok     = (caption_ok     != null) ? caption_ok : "OK";
            var contents = '';
            contents += '  <div class="modal-dialog modal-dialog-centered">';
            contents += '    <div class="modal-content">';
            contents += '      <div class="modal-header">';
            contents += '        <h5 class="modal-title" id="confirm_title">タイトル</h5>';
            contents += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            contents += '          <span aria-hidden="true">&times;</span>';
            contents += '        </button>';
            contents += '      </div>';
            contents += '      <div class="modal-body" id="confirm_message">';
            contents += '        メッセージ内容';
            contents += '      </div>';
            contents += '      <div class="modal-footer">';
            contents += '        <button type="button" class="btn btn-success" id="confirm_btn_ok" name="deletebtn" >OK</button>';
            contents += '      </div>';
            contents += '    </div>';
            contents += '  </div>';
            
            // HTMLコンテンツの動的生成
            $(id).append(contents);
            $("#confirm_title").html(title);
            $("#confirm_message").html(message);
            $("#confirm_btn_ok").html(caption_ok);

            // OKボタン押下イベントハンドラ
            $("#confirm_btn_ok").get(0).onclick = function(){
                if (funcOk != undefined && funcOk != null) {
                    funcOk();
                }
                $('#dynamic_message_box').modal('hide');
                $("#"+message_id).empty();
            };

            $('#dynamic_message_box').modal('show');  
        },
    };

    MessageBox = {
        // 通知メッセージボックス
        show: function (message, title, callback, id, zindex)
        {
            id     = (id != null)     ? id     : "#show_message_box";
            title  = (title != null)  ? title  : "通知メッセージ";
            zindex = (zindex != null) ? zindex : 999;
            
            // ダイアログを作成
            $(id).html(message);
            $(id).dialog({
                modal: true,
                title: title,
                zindex: zindex,
                buttons: {
                    "OK": function () {
                        if (callback != undefined && callback != null) {
                            callback();
                        }
                        $(this).dialog("close");
                    }
                }
            });
        },

        /**
         * 確認メッセージボックス
         * @param {type} message
         * @param {type} title
         * @param {type} funcYes
         * @param {type} funcNo
         * @returns {undefined}
         */
        confirm: function (message, title, funcYes, funcNo, id, zindex)
        {
            id     = (id != null)     ? id     : "#show_message_box";
            title  = (title != null)  ? title  : "確認メッセージ";
            zindex = (zindex != null) ? zindex : 999;
            // ダイアログを作成
            $(id).html(message);
            $(id).dialog({
                modal: true,
                title: title,
                zindex: zindex,
                buttons: {
                    "はい": function () {
                        if (funcYes !== undefined && funcYes != null) {
                            funcYes();
                        }
                        $(this).dialog("close");
                    },
                    "いいえ": function () {
                        if (funcNo !== undefined && funcNo != null) {
                            funcNo();
                        }
                        $(this).dialog("close");
                    }
                }
            });
        },
    };

    DynamicSubmit ={
        submit: function (formAction, formMethod, requestData, rquestName) {
            rquestName = this.rquestName;
            if (rquestName == null) {
              rquestName = "requestData";
            }
            var formId = "dynamic_form";
            var objForm = $('<form/>', {id: formId, action: formAction, method: formMethod})
                    .append($('<input/>', {type: 'hidden', name: rquestName, value: requestData}))
                    .appendTo(document.body);
            objForm.submit();
            objForm.remove();
        }
    };
});
