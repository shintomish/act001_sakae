{{-- @extends('layouts.app') --}}
@extends('layouts.clienttrans')

@section('content')
    <h5>送信データ確認ページ</h5>
    <div class="row">

        <!-- 検索エリア -->
        {{-- <form  class="my-2 my-lg-0 ml-2" action="{{route('transserch_custom')}}" method="GET"> --}}
            {{-- @csrf
            @method('get')
            <style>
                .exright{
                    text-align: right;
                }
            </style> --}}
            {{-- <div class="exright">
                <select style="margin-right:5px;" class="custom-select" id="customer_id" name="customer_id">
                    @foreach ($customer_findrec as $customer_findrec2)
                        @if ($customer_findrec2['id']==$customer_id)
                    <option selected="selected" value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @else
                            <option value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @endif

                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn_sm">検索</button>
            </div> --}}

        {{-- </form --> --}}
        <!-- 検索エリア -->
    </div>

    <div class="table-responsive">

        <table class="table table-striped table-borderd">
            <thead>
                <tr>
                    <th class="text-end"scope="col">ID</th>
                    <th scope="col">@sortablelink('filename','送信ファイル名')</th>
                    <th scope="col">@sortablelink('created_at', '送信日')</th>
                    <th class="text-end" scope="col">@sortablelink('filesize','ファイルサイズ')</th>
                    <th scope="col">会社名</th>
                    {{-- <th scope="col">顧客ID</th> --}}

                </tr>
            </thead>

            <tbody>
                @if($imageuploads->count())
                    @foreach($imageuploads as $imageupload)
                    <tr>
                        <td class="text-end">{{ number_format($imageupload->id) }}</td>
                        <td>{{ $imageupload->filename }}</td>
                        <td>{{ $imageupload->created_at }}</td>
                        @php
                            // $temp = $imageupload->filesize / 1024;
                            // 2021/11/28 変更
                            $insize = $imageupload->filesize;
                            if ($insize >= 1073741824) {
                                $fileSize = round($insize / 1024 / 1024 / 1024,1) . ' GB';
                            } elseif ($insize >= 1048576) {
                                $fileSize = round($insize / 1024 / 1024,1) . ' MB';
                            } elseif ($insize >= 1024) {
                                $fileSize = round($insize / 1024,1) . ' KB';
                            } else {
                                $fileSize = $insize . ' bytes';
                            }
                            $temp = $fileSize;
                        @endphp
                        {{-- <td class="text-end">{{ number_format($temp) }}</td> --}}
                        <td class="text-end">{{ $temp }}</td>

                        <td>
                            @foreach ($customer_findrec as $customer_findrec2)
                                @if ($customer_findrec2->id==$imageupload->customer_id)
                                    {{$customer_findrec2['business_name']}}
                                @endif
                            @endforeach
                        </td>

                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p> </p></td>
                        <td><p>0件です。</p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
    {{-- {{ $imageuploads->render() }} --}}
    {{-- {{ $imageuploads->appends(request()->query())->links() }} --}}
        {{ $imageuploads->appends(request()->query())->render() }}
    </ul>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user");
    <script type="text/javascript">
            $('.btn_del').click(function()
                if( !confirm('本当に削除しますか？') ){
                    /* キャンセルの時の処理 */
                    return false;
                }
                else{
                    /*　OKの時の処理 */
                    return true;
                }
            });
    </script> --}}
@endsection
