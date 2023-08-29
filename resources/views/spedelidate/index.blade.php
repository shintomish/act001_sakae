{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>納期特例一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('spedelidate.create')}}">新規登録</a>
    </div>

    <div class="row">
        <!-- 検索エリア -->
        {{-- <!--- form  class="my-2 my-lg-0 ml-2" action="{{route('spedelidateserchyear')}}" method="GET"> --}}
            {{-- @csrf --}}
            {{-- @method('get') --}}
            {{-- <table>
                <tr>
                    <style>
                        .exright{
                            text-align: right;
                        }
                    </style>
                    <div class="exright">
                        <select class="custom-select" id="year" name="year"> --}}
                            {{-- @foreach ($loop_year_flg as $loop_year_flg2) --}}
                                {{-- 2021年のみ --}}
                                {{-- @if ($loop_year_flg2['no']==$nowyear)
                                <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                @else
                                <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                @endif --}}
                                {{-- @if ($loop_year_flg2['no']==0) --}}
                                    {{-- <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                {{-- @else --}}
                                    {{-- @if ($loop_year_flg2['no']==$nowyear) --}}
                                        {{-- <option selected value="{{ $nowyear }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                    {{-- @else --}}
                                        {{-- <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                    {{-- @endif --}}
                                {{-- @endif --}}
                            {{-- @endforeach --}}
                        {{-- </select>
                        <button type="submit" class="btn btn-secondary btn_sm">検索</button>
                    </div>
                </tr>
            </table>
        </form --> --}}
        <!-- 検索エリア -->
    </div>

    {{-- Line --}}
    <hr class="mb-4">

    <div class="table-responsive">

        <table class="table table-striped table-borderd table-scroll">
            <thead>
                <tr>
                    <th scope="col" class ="text-end bg-secondary text-right">ID</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">@sortablelink('business_code', '事業者コード')</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">@sortablelink('business_name', '顧客名')</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">代表者</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">決算月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">役員報酬</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">従業員</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">納付種別</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">達人入力</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">納付作成</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">納付申告</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">支払日</th>
                    {{-- <div class="opacit-40">rgba(255, 255, 0, 0.4)黄色 --}}
                    <th scope="col" class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">確認事項</th>

                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">報酬変更</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">変更後</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">変更時期</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-success text-right">納付書</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-secondary text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($spedelidates->count())
                    @foreach($spedelidates as $spedelidate)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $spedelidate->id }}</td>

                        {{-- 事業者コード --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                <td>{{ $customers2->business_code }}</td>
                            @endif
                        @endforeach

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                <td>{{ $customers2->business_name }}</td>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach

                        {{-- 決算月 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                @foreach ($loop_closing_month as $loop_closing_month2)
                                    @if ($loop_closing_month2['no']==$customers2->closing_month)
                                        <td>{{ $loop_closing_month2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- 役員報酬 --}}
                        <td>{{ number_format($spedelidate->officecompe) }}</td>

                        {{-- 従業員 --}}
                        <td>{{ number_format($spedelidate->employee) }}</td>

                        {{-- 納付種別 --}}
                        <td>{{ $spedelidate->paymenttype }}</td>

                        {{-- 達人入力 1:― 2:○', --}}
                        @foreach ($loop_circle_cross as $loop_circle_cross2)
                            @if ($loop_circle_cross2['no']==$spedelidate->adept_flg)
                                <td>{{ $loop_circle_cross2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 納付書作成 1:― 2:○', --}}
                        @foreach ($loop_payslip_flg as $loop_payslip_flg2)
                            @if ($loop_payslip_flg2['no']==$spedelidate->payslip_flg)
                                <td>{{ $loop_payslip_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 0円納付申告 1:― 2:○', --}}
                        @foreach ($loop_declaration_flg as $loop_declaration_flg2)
                            @if ($loop_declaration_flg2['no']==$spedelidate->declaration_flg)
                                <td>{{ $loop_declaration_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 支払日注意 --}}
                        <td>{{ $spedelidate->paydate_att }}</td>

                        {{-- 確認事項 --}}
                        <td>{{ $spedelidate->checklist }}</td>

                        {{-- 役員報酬変更 --}}
                        @foreach ($loop_chaneg_flg as $loop_chaneg_flg2)
                            @if ($loop_chaneg_flg2['no']==$spedelidate->chaneg_flg)
                                <td>{{ $loop_chaneg_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 変更後 --}}
                        <td>{{ $spedelidate->after_change }}</td>

                        {{-- 変更時期 --}}
                        <td>{{ $spedelidate->change_time }}</td>

                        {{-- 納付書データの連携 --}}
                        <td>{{ $spedelidate->linkage_pay }}</td>

                       <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('spedelidate.edit',$spedelidate->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('spedelidate.destroy', $spedelidate->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-danger btn-sm" type="submit" value="削除" id="btn_del"
                                            onclick='return confirm("削除しますか？");'>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p>0件です。</p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
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
        {{ $spedelidates->appends(request()->query())->render() }}
     </ul>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user"); --}}
    <script type="text/javascript">
            // $('.btn_del').click(function()
            //     if( !confirm('本当に削除しますか？') ){
            //         /* キャンセルの時の処理 */
            //         return false;
            //     }
            //     else{
            //         /*　OKの時の処理 */
            //         return true;
            //     }
            // });
    </script>
@endsection
