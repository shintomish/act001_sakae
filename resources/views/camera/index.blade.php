{{-- @extends('layouts.app') --}}
@extends('layouts.client')
<?php
    function formatBytes($bytes, $precision = 2, array $units = null)
    {
        if ( abs($bytes) < 1024 ){
            $precision = 0;
        }

        if ( is_array($units) === false ){
            $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }

        if ( $bytes < 0 ){
            $sign = '-';
            $bytes = abs($bytes);
        }else{
            $sign = '';
        }
        $exp   = floor(log($bytes) / log(1024));
        $unit  = $units[$exp];
        $bytes = $bytes / pow(1024, floor($exp));
        $bytes = sprintf('%.'.$precision.'f', $bytes);
        return $sign.$bytes.' '.$unit;
    }
?>

@section('content')
    @if (session('message'))
        @if (session('message') == '送信処理を正常終了しました。')
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @else
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
    @endif
    <div class="row">

        <!-- 検索エリア -->
        <form  class="my-2 my-lg-0 ml-2" action="{{route('topclientserch')}}" method="GET">
            {{-- <form  class="my-2 my-lg-0 ml-2" action="{{route('transserch_custom')}}" method="GET"> --}}
            @csrf
            @method('get')
            <style>
                .exright{
                    text-align: right;
                }
            </style>
            <div class="exright">
                <select style="margin-right:5px;" class="custom-select" id="customer_id" name="customer_id">
                    @foreach ($customer_findrec as $customer_findrec2)
                        @if ($customer_findrec2['id']==$customer_id)
                    <option selected="selected" value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @else
                            <option value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @endif

                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn_sm">送信元</button>
            </div>

        </form -->
        <!-- 検索エリア -->
    </div>

    <div class="container">
        <h6 class="jumbotron" style="color:#4300fc">カメラアップロード</h6>
        <form action="{{ route('postUpload', $customer_id )}}" id="file-upload" method="POST" enctype="multipart/form-data">
            <div id="upload-container" style="border: 1px solid; padding: 30px;">
                @csrf
            {{-- 複数法人 --}}
            <a style="color:#4300fc">From: </a>
            <select style="margin-bottom:5px; background-color:rgb(214, 209, 209)" class="custom-select" id="customer_id" name="customer_id">
                @foreach ($customer_findrec as $customer_findrec2)
                    @if ($customer_findrec2['id']==$customer_id)
                <option value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                    @else
                    <option disabled value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                    @endif
                @endforeach
            </select>

            <div class="flow-error">
                <div class="alert alert-danger">
                </div>
                <div class="alert alert-success">
                </div>
            </div>

            <div class="flow-drop mb10" ondragenter="jQuery(this).addClass('flow-dragover');" ondragend="jQuery(this).removeClass('flow-dragover');" ondrop="jQuery(this).removeClass('flow-dragover');" accept="image/*" capture="camera">
            <a class="ml10 flow-browse btn btn-primary">&#x1F4F8; 写真を撮ってアップロードする</a>
            {{-- <label class="btn btn-info">
                &#x1F4F8; 写真を撮ってアップロードする
                <input type="file" class="d-none" accept="image/*" capture="camera" @change="onCaptureImage">
            </label> --}}
            {{-- <input id="upload" type="file" name="image" accept="image/*" capture="camera"> --}}
            </div>

            <div class="progress flow-progress" style="margin-top:10px; height: 30px;" >
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">45% Complete</span>
                </div>
            </div>
            <div class="progress flow-list" style="margin-top:10px; height: 30px;" >
                <div class="progress-flow-list flow-list-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">sec</span>
                </div>
            </div>
        </form>
            {{-- //プログレスバーの実行
            //flow.progress() で進捗が取得できるのでそれを利用してプログレスバーを設定
            $('.bar').css({width:Math.floor(flow.progress()*100) + '%'}); --}}
        </div>

    <style>
            .ml10{
                margin-left: 10px;
            }
            .mr10{
                margin-right: 10px;
            }
            .mb10{
                margin-bottom: 10px;
            }
            /* Uploader: Drag & Drop */
            .flow-error { font-size:14px;display: none; }
            .flow-error >div{ display: none; }
            /* .flow-drop {padding:30px 15px; font-size:13px; text-align:center; color:#666; background-color:#fafafa; border:2px dashed #ccc; border-radius:4px; margin-top:40px; z-index:9999; display:none;} */
            /* drop display:none-->textセンター表示 display:flex--->text左表示 */
            .flow-drop {padding:30px 15px; font-size:14px; text-align:center; color:#505050; background-color:#fafafa; border:2px dashed #ccc; border-radius:6px; margin-top:40px; z-index:9999; display:flex;}
            .flow-dragover {padding:30px; color:#555; background-color:#eef1c3; border:1px solid rgb(244, 245, 233);}
            .flow-dragleave {padding:30px 15px; font-size:13px; text-align:center; color:#666; background-color:#fafafa; border:2px dashed #ccc; border-radius:4px; margin-top:40px; z-index:9999; display:none;}

            /* ---Uploader: Progress bar--- */
            /* .flow-progress{display: none;} */
            .flow-progress{width:Math.floor(flow.progress()*100) + '%'};

            .is-paused .progress-resume-link {display:inline;}
            .is-paused .progress-pause-link {display:none;}
            .is-complete .progress-pause {display:none;}

            /* ---Uploader: List of items being uploaded--- */
            /* --これでは表示されない */
            /* .flow-list {margin-top: 30px;} width:'0'*/

            /* .flow-list {display:none;} 2021/12/10 */
            /* --display:noneでは連続で操作すると表示されないのでinlineで下に追加される */
            .flow-list {display:inline;}

            .uploader-item {width:148px; height:90px; background-color:#666; position:relative; border:2px solid black; float:left; margin:0 6px 6px 0;}
            .uploader-item-thumbnail {width:100%; height:100%; position:absolute; top:0; left:0;}
            .uploader-item img.uploader-item-thumbnail {opacity:0;}
            .uploader-item-creating-thumbnail {padding:0 5px; font-size:9px; color:white;}
            .uploader-item-title {position:absolute; font-size:9px; line-height:11px; padding:3px 50px 3px 5px; bottom:0; left:0; right:0; color:white; background-color:rgba(0,0,0,0.6); min-height:27px;}
            .uploader-item-status {position:absolute; bottom:3px; right:3px;}

            /* Uploader: Hover & Active status */
            .uploader-item:hover, .is-active .uploader-item {border-color:#4a873c; cursor:pointer; }
            .uploader-item:hover .uploader-item-title, .is-active .uploader-item .uploader-item-title {background-color:rgba(74,135,60,0.8);}

            /* Uploader: Error status */
            .is-error .uploader-item:hover, .is-active.is-error .uploader-item {border-color:#900;}
            .is-error .uploader-item:hover .uploader-item-title, .is-active.is-error .uploader-item .uploader-item-title {background-color:rgba(153,0,0,0.6);}
            .is-error .uploader-item-creating-thumbnail {display:none;}

        </style>


        <hr class="mb-4">  {{-- // line --}}

    </div>

@endsection


@section('scripts')
<!-- Scripts -->
<script src="{{ asset('js/flow.min.js') }}"></script>

<?php
    // 初期設定 2021/12/14
    // auth_customer_findrecの多重読み込みを1回で行う。jsonを利用
    $status = false;
    $arr = array(
        "res" => array(
            "info" => array(
                [
                    "status"     => $status
                ]
            )
        )
    );

    $arr = json_encode($arr);
    // file_put_contents("customer_info_status.json" , $arr); <===Orijinal
    // $jsonfileは、Controllerのindexより取得
    file_put_contents($jsonfile , $arr);
?>

<script type="text/javascript">

    (function () {
    var customer_id = $("#customer_id" + " option:selected").val();
    var isImage = true;
    var r = new Flow({
        simultaneousUploads : 1,
        // target: '/uploads',
        target: "{{ route('postUpload', $customer_id ) }}",
        permanentErrors:[404, 500, 501],
        headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}'},
        testChunks:false
    });
      // Flow.js isn't supported, fall back on a different method
    if (!r.support) {
        $('.flow-error').show();
        return ;
    }
    // Show a place for dropping/selecting files
    $('.flow-drop').show();
    r.assignDrop($('.flow-drop')[0]);
    r.assignBrowse($('.flow-browse')[0]);
    // Handle file add event
    r.on('fileAdded', function(file){
        isImage = true;

        if( isImage ) {
            // Show progress bar
            $('.flow-progress, .flow-list').show();

            $('.flow-drop').css({color:'#220477'}); /* 青にする */

            // Add the file to the list
            $('.flow-list').append(
                '<li class="flow-file list-group-item flow-file-'+file.uniqueIdentifier+'">' +
                'Uploading <span class="flow-file-name mr10"></span> ' +
                '<span class="flow-file-size mr10"></span> ' +
                '<span class="flow-file-progress mr10"></span> '
            );
            var $self = $('.flow-file-'+file.uniqueIdentifier);
            $self.find('.flow-file-name').text(file.name);
            $self.find('.flow-file-size').text(readablizeBytes(file.size));
        }

    });
    r.on('filesSubmitted', function(file) {
        if( isImage ) {
            r.upload();
        }
    });

    r.on('fileSuccess', function(file,message){
        setTimeout(function(){
        $('.flow-progress').fadeOut(400,function(){
            $(this).hide();
            $('.progress-bar').css({width:'0'});
            $('.flow-list').css({width:'0'});
            $('.flow-drop').css({color:'#505050'}); /* 元に戻す */

            message = file.name + " のアップロードが正常に終了しました。";
            alert('success',message,5000);
            // 表示5sec javasrripterror対応で[client.blade]のjquery.min.jsをcommentoutする

        })
    },1000)

    });
    r.on('fileError', function(file, message){
        // Reflect that the file upload has resulted in error
        $('.flow-progress').hide();
        $('.flow-file-'+file.uniqueIdentifier).hide();
        // 2021/12/19
        var response = JSON.parse(message || "null");
        // console.log(response);
        if(response == null) {
            message = file.name + ' のアップロードが出来ませんでした。';
            alert('danger',message,5000);    // 表示5sec
        } else {
            if(response.status == "BG") {
                alert('danger',response.error,5000);    // 表示5sec
            } else {
                message = file.name + ' のアップロードが出来ませんでした。';
                alert('danger',message,5000);    // 表示5sec
            }
        }

    });
    r.on('fileProgress', function(file){
        // Handle progress for both the file and the overall upload
        $('.flow-file-'+file.uniqueIdentifier+' .flow-file-progress')
        .html(Math.floor(file.progress()*100) + '% '
            + readablizeBytes(file.averageSpeed) + '/s '
            // + secondsToStr(file.timeRemaining()) + ' remaining') ;
            // + secondsToStr(file.timeRemaining()) + ' 残り') ;
            + ' 残り 約 ' + secondsToStr(file.timeRemaining()) ) ;
        $('.progress-bar').css({width:Math.floor(r.progress()*100) + '%'});
    });

    function alert(type,message,timeout){
        $('.flow-error').find('.alert').hide();
        $('.flow-error').show();
        $('.flow-error').find('.alert-' + type).text(message).fadeIn(400,function(){
                setTimeout(function(){
                    $(this).fadeOut(400,function(){
                    $(this).hide();
                    $('.flow-error').hide();
                });
            },timeout)
            });
        }
    })();
    function readablizeBytes(bytes) {
        var s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];
        var e = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, e)).toFixed(2) + " " + s[e];
    }
    function secondsToStr (temp) {
            function numberEnding (number) {
        //     return (number > 1) ? 's' : '';
        //   }
        //   var years = Math.floor(temp / 31536000);
        //   if (years) {
        //     return years + ' year' + numberEnding(years);
        //   }
        //   var days = Math.floor((temp %= 31536000) / 86400);
        //   if (days) {
        //     return days + ' day' + numberEnding(days);
        //   }
        //   var hours = Math.floor((temp %= 86400) / 3600);
        //   if (hours) {
        //     return hours + ' hour' + numberEnding(hours);
        //   }
        //   var minutes = Math.floor((temp %= 3600) / 60);
        //   if (minutes) {
        //     return minutes + ' minute' + numberEnding(minutes);
        //   }
        //   var seconds = temp % 60;
        //   return seconds + ' second' + numberEnding(seconds);
            return (number > 1) ? '' : '';
        }
        var years = Math.floor(temp / 31536000);
        if (years) {
            return years + ' 年' + numberEnding(years);
        }
        var days = Math.floor((temp %= 31536000) / 86400);
        if (days) {
            return days + ' 日' + numberEnding(days);
        }
        var hours = Math.floor((temp %= 86400) / 3600);
        if (hours) {
            return hours + ' 時間' + numberEnding(hours);
        }
        var minutes = Math.floor((temp %= 3600) / 60);
        if (minutes) {
            return minutes + ' 分' + numberEnding(minutes);
        }
        var seconds = temp % 60;
        return seconds + ' 秒' + numberEnding(seconds);
    }
</script>
@endsection
