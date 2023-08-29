@extends('layouts.customer')

@section('content')
<div class="content-wrapper">
    <!-- ---------------------------------------------------------------------- -->
    <!-- Content Header (Page header)                                           -->
    <!-- ---------------------------------------------------------------------- -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 id="page-title" class="m-0 text-dark">システム・メンテナンス</h1> --}}
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- ---------------------------------------------------------------------- -->
    <!-- Main content                                                           -->
    <!-- ---------------------------------------------------------------------- -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- card content News編集 -->
                    <div id="card_newsrepo_list_edit" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">News・メール配信情報</h3>
                        </div>
                        <!-- /.card-header -->

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('newsrepo.update',$newsrepos->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="id"></label>
                                        </div>
                                        <div class="col-3">
                        <input type="hidden"  class="hidden" name="id" value="{{ old('id', $newsrepos->id) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:3px;" for="mail_flg">MAIL／登録</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="mail_flg" name="mail_flg">
                                            @foreach ($loop_mail_flg as $loop_mail_flg2)
                                                @if ($loop_mail_flg2['no']==$newsrepos->mail_flg)
                                                <option selected="selected" value="{{ $loop_mail_flg2['no'] }}">{{ $loop_mail_flg2['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:5px;" for="individual_mail">法人／個人</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="individual_mail" name="individual_mail">
                                            @foreach ($loop_individual_mail as $loop_individual_mail2)
                                                @if ($loop_individual_mail2['no']==$newsrepos->individual_mail)
                                                <option selected="selected" value="{{ $loop_individual_mail2['no'] }}">{{ $loop_individual_mail2['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:5px;" for="interim_mail">選　択　月</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="interim_mail" name="interim_mail">
                                            @foreach ($loop_interim_mail as $loop_interim_mail2)
                                                @if ($loop_interim_mail2['no']==$newsrepos->interim_mail)
                                                <option selected="selected" value="{{ $loop_interim_mail2['no'] }}">{{ $loop_interim_mail2['name'] }}</option>

                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:5px;" for="announce_month">告　知　月</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="announce_month" name="announce_month">
                                            {{-- @foreach ($loop_announce_month as $loop_announce_month2)
                                                @if ($loop_announce_month2['no']==$newsrepos->announce_month)
                                                <option selected="selected" value="{{ $loop_announce_month2['no'] }}">{{ $loop_announce_month2['name'] }}</option>
                                                @endif
                                            @endforeach --}}
                                            @foreach ($loop_announce_month as $loop_announce_month2)
                                                @if ($loop_announce_month2['no']!='0')
                                                @if ($loop_announce_month2['no']==$newsrepos->announce_month)
                                                <option selected="selected" value="{{ $loop_announce_month2['no'] }}">{{ $loop_announce_month2['name'] }}</option>
                                                @else
                                                <option value="{{ $loop_announce_month2['no'] }}">{{ $loop_announce_month2['name'] }}</option>
                                                @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="comment">コメント</label>
                                        </div>
                                        @php
                                            $textarea = $newsrepos->comment;
                                            $textarea = htmlspecialchars($textarea);
                                            // nl2br--> br/>が表示
                                            // $textarea = nl2br($textarea);
                                        @endphp
                                            <!-- ５行にしたいテキストエリア -->
                                            <textarea class="row-5" name="comment">{{$textarea}}</textarea>
{{-- <input type="textarea" class="row-5" name="comment" value="{{ !! nl2br($newsrepos->comment) !! }}"> --}}

                                        <style>
                                            /** ５行ピッタシに調整 */
                                            .row-5 {
                                                height: calc( 1.3em * 5 );
                                                line-height: 1.3;
                                                max-width: 550px;
                                            }
                                        </style>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-block btn-primary">保存</button>
                                    <a class="btn btn-block btn-primary" href="{{route('newsrepo.index')}}">戻る</a>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
<!-- /.content -->
</div>
@endsection

@section('part_javascript')
ChangeSideBar("nav-item-system-organization");

@endsection
