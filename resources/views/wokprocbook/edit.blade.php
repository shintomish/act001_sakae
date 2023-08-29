{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>税理士業務処理簿</h2>
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('customer.create')}}">新規登録</a> --}}
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8 order-md-1">
            <form class="needs-validation" novalidate action="{{ route('wokprocbook.update',$wokprocbook->id)}}" method="POST">
                @csrf
                @method('PUT')

            <h4 class="mb-3">情報</h4>
            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                <div class="col-2 bg-secondary text-right">
                    <label for="year">年</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                            @if ($loop_year_flg2['no']==$nowyear)
                                <option selected="selected" value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @else
                                <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                <div class="col-2">
                    <label></label>
                </div>
                <div class="col-4">

                </div>
            </div>

            <div class="row">
                    <div class="col-2">
                        <label for="refnumber">整理番号</label>
                    </div>
                    <div class="col-4">
        <input type="text" class="form-control" name="refnumber" value="{{ old('refnumber',$wokprocbook->refnumber)}}">
                    </div>

                    <div class="col-2">
                        <label for="busi_class">業務区分</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="busi_class" name="busi_class">
                            @foreach ($loop_busi_class as $loop_busi_class2)
                                @if ($loop_busi_class2['no']==$wokprocbook->busi_class)
                    <option selected="selected" value={{$loop_busi_class2['no']}}>{{ $loop_busi_class2['name'] }}</option>
                                @else
                                    @if ($loop_busi_class2['no']==0)
                                        <option disabled value={{$loop_busi_class2['no']}}>{{ $loop_busi_class2['name'] }}</option>
                                    @else
                                        <option value={{$loop_busi_class2['no']}}>{{ $loop_busi_class2['name'] }}</option>
                                    @endif
                                @endif

                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="custm_id">会社名</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="custm_id" name="custm_id">
                        @foreach($customers as $customers2)
                            @if($customers2->id==$wokprocbook->custm_id)
                    <option selected="selected" value={{$wokprocbook->custm_id}}>{{ $customers2->business_name }}</option>
                            @else
                                <option value={{$customers2->id}}>{{ $customers2->business_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="contents_class">内容（税目等）</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="contents_class" name="contents_class">
                        {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                        @foreach ($loop_contents_class as $loop_contents_class2)
                            @if ($loop_contents_class2['no']==$wokprocbook->contents_class)
                        <option selected="selected" value={{$wokprocbook->contents_class}}>{{ $loop_contents_class2['name'] }}</option>
                            @else
                                @if ($loop_contents_class2['no']==0)
                                <option disabled value="{{ $loop_contents_class2['no'] }}">{{ $loop_contents_class2['name'] }}</option>
                                @else
                                <option value="{{ $loop_contents_class2['no'] }}">{{ $loop_contents_class2['name'] }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="start_notification">添付書面</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="attach_doc" name="attach_doc">
                        @foreach ($loop_attach_doc as $loop_attach_doc2)
                            @if ($loop_attach_doc2['no']==$wokprocbook->attach_doc)
                        <option selected="selected" value={{$wokprocbook->attach_doc}}>{{ $loop_attach_doc2['name'] }}</option>
                            @else
                                @if ($loop_attach_doc2['no']==0)
                                <option disabled value="{{ $loop_attach_doc2['no'] }}">{{ $loop_attach_doc2['name'] }}</option>
                                @else
                                <option value="{{ $loop_attach_doc2['no'] }}">{{ $loop_attach_doc2['name'] }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="facts_class">顛末</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="facts_class" name="facts_class">
                        @foreach ($loop_facts_class as $loop_facts_class2)
                            @if ($loop_facts_class2['no']==$wokprocbook->facts_class)
                        <option selected="selected" value={{$wokprocbook->facts_class}}>{{ $loop_facts_class2['name'] }}</option>
                            @else
                                @if ($loop_facts_class2['no']==0)
                                <option disabled value="{{ $loop_facts_class2['no'] }}">{{ $loop_facts_class2['name'] }}</option>
                                @else
                                <option value="{{ $loop_facts_class2['no'] }}">{{ $loop_facts_class2['name'] }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="proc_date">処理年月日</label>
                </div>
                <div class="col-4">
                    @php
                        $str = "";
                        if (isset($wokprocbook->proc_date)) {
                            $str = ( new DateTime($wokprocbook->proc_date))->format('Y-m-d');
                        }
                    @endphp
                    <input type="date" class="form-control" name="proc_date" value="{{$str}}">
                </div>

                    <div class="col-2">
                        {{-- 提出日 -> 税務代理権限書提出日 2022/08/25--}}
                        {{-- <label for="filing_date">提出日</label> --}}
                        <label for="filing_date">税務代理権限書提出日</label>
                    </div>
                    <div class="col-4">
                        @php
                            $str = "";
                            if (isset($wokprocbook->filing_date)) {
                                $str = ( new DateTime($wokprocbook->filing_date))->format('Y-m-d');
                            }
                        @endphp
                        <input type="date" class="form-control" name="filing_date" value="{{$str}}">
                    </div>
                </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="login_flg">社員・所属</label>
                </div>
                <div class="col-4">
                    {{-- //利用区分 顧客(1):社員(2):所属(3) --}}
                    <select class="custom-select d-block w-100" id="login_flg" name="login_flg">
                        @foreach ($loop_login_flg as $loop_login_flg2)
                            @if ($loop_login_flg2['no']==0 || $loop_login_flg2['no']==1)
                                <option disabled value={{$loop_login_flg2['no']}}>{{ $loop_login_flg2['name'] }}</option>
                            @else
                                @if ($loop_login_flg2['no']==$wokprocbook->login_flg)
                                <option selected="selected" value={{$wokprocbook->login_flg}}>{{ $loop_login_flg2['name'] }}</option>
                                @else
                                <option value={{$loop_login_flg2['no']}}>{{ $loop_login_flg2['name'] }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="staff_no">担当者</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="staff_no" name="staff_no">
                        @foreach ($users as $users2)
                            @if ($users2->id==$wokprocbook->staff_no)
                            <option selected="selected"  value={{$wokprocbook->staff_no}}>{{ $users2->name }}</option>
                            @else
                                <option value={{$users2->id}}>{{ $users2->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                {{-- 備考 --}}
                <div class="col-2">
                    <label for="remarks">備考</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="remarks" value="{{ old('remarks',$wokprocbook->remarks)}}">
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('wokprocbook.input')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
