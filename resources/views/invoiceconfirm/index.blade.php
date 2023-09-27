{{-- @extends('layouts.app') --}}
@extends('layouts.invoice')
<?php

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
        <form  class="my-2 my-lg-0 ml-2" action="{{route('invoiceconfirmserch')}}" method="GET">
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
                <button type="submit" class="btn btn-secondary btn_sm">送信先</button>
            </div>
        </form -->
        <!-- 検索エリア -->
    </div>
    <hr class="mb-4">  {{-- // line --}}

    <style>
        /* スクロールバーの実装 */
        .table_sticky {
            display: block;
            overflow-y: scroll;
            /* height: calc(100vh/2); */
            height: 600px;
            border:1px solid;
            border-collapse: collapse;
        }
        .table_sticky thead th {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            left: 0;
            color: #fff;
            background: rgb(180, 226, 11);
            &:before{
                content: "";
                position: absolute;
                top: -1px;
                left: -1px;
                width: 100%;
                /* height: 100%; 2023/06/12 sortablelink対応 */
                height: 10%;
                border: 1px solid #ccc;
            }
        }

        table{
            width: 1800px;
        }
        th,td{
            width: 200px;
            height: 10px;
            vertical-align: middle;
            padding: 0 15px;
            border: 1px solid #ccc;
        }
        .fixed01,
        .fixed02{
            /* position: -webkit-sticky; */
            position: sticky;
            top: 0;
            left: 0;
            color: rgb(8, 8, 8);
            background: #333;
            &:before{
                content: "";
                position: absolute;
                top: -1px;
                left: -1px;
                width: 100%;
                height: 100%;
                border: 1px solid #ccc;
            }
        }
        .fixed01{
            z-index: 2;
        }
        .fixed02{
            z-index: 1;
        }
    </style>

    <div class="table-responsive">
        {{-- <table class="table table-striped table-borderd table-scroll"> --}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    {{-- <th scope="col" class ="col-xs-3 col-md-1 bg-secondary text-left">ID</th>
                    <th scope="col" class ="col-xs-3 col-md-2 bg-info text-right">顧客名</th>
                    <th scope="col" class ="col-xs-3 col-md-2 bg-info text-right">送信ファイル名</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">ファイルサイズ</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">送信日</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">未読/既読</th> --}}

                    <th scope="col" class ="fixed01">ID</th>
                    <th scope="col" class ="fixed01">顧客名</th>
                    <th scope="col" class ="fixed01">送信ファイル名</th>
                    <th scope="col" class ="fixed01">ファイルサイズ</th>
                    <th scope="col" class ="fixed01">送信日</th>
                    <th scope="col" class ="fixed01">未読/既読</th>
                </tr>

                <tbody>
                    @if($invoices->count())
                        @foreach($invoices as $invoices2)
                            <tr>
                                {{-- ID --}}
                                <td>{{ $invoices2->id }}</td>

                                {{-- 社名/氏名 --}}
                                @foreach ($customer_findrec as $customers2)
                                    @if ($customers2->id==$invoices2->customer_id)
                                        <td>{{ $customers2->business_name }}</td>
                                    @endif
                                @endforeach

                                {{-- ファイルパス --}}
                                {{-- <td>{{ $invoices2->filepath }}</td> --}}

                                {{-- ファイル名 --}}
                                <td>{{ $invoices2->filename }}</td>

                                {{-- ファイルサイズ / 送信日 --}}
                                @php
                                    $str = "";
                                    if (isset($invoices2->created_at)) {
                                        $str = ( new DateTime($invoices2->created_at))->format('Y-m-d');
                                    }

                                    $insize = $invoices2->filesize;
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

                                    if($invoices2->urgent_flg == 2) {
                                        $kidoku = '未読';
                                        $textcolor = 'text-danger';
                                    } else {
                                        $kidoku = '既読';
                                        $textcolor = 'text-secondary';
                                    }
                                @endphp
                                
                                {{-- ファイルサイズ --}}
                                <td class="text-left">{{ $temp }}</td>

                                {{-- 送信日 --}}
                                <td>{{ $str }}</td>

                                {{-- 未読/既読 --}}
                                <td>
                                    <h6>
                                        <p class={{ $textcolor }}>{{ $kidoku }}</p>
                                    </h6>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><p>0件です。</p></td>
                            <td><p> </p></td>
                            {{-- <td><p> </p></td> --}}
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                        </tr>
                    @endif
                </tbody>
            </thead>
        </table>

        {{-- ページネーション / pagination）の表示 --}}
        <ul class="pagination justify-content-center">
            {{ $invoices->appends(request()->query())->render() }}
        </ul>

    </div>

    <div class="container">
    </div>
    
    <style>
    </style>

    <hr class="mb-4">  {{-- // line --}}

@endsection

@section('scripts')

<!-- Scripts -->
<script src="{{ asset('js/flow.min.js') }}"></script>


<script type="text/javascript">
</script>

@endsection
