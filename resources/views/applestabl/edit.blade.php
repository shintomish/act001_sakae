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
                <div class="col-10">
                    <!-- card content の編集 -->
                    <div id="card_organization_list_edit" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">情報</h3>
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

                        <form action="{{ route('applestabl.update',$applestabl->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">年</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="year" name="year">
                                                @foreach ($loop_year_flg as $loop_year_flg2)
                                                    @if ($loop_year_flg2['no']==$nowyear)
                                                    <option selected="selected" value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                    @else
                                                    {{-- <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                                    <option  value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="companyname">会社名</label>
                                        </div>
                                        <div class="col-6">
<input id="companyname" type="text" class="form-control @error('companyname') is-invalid @enderror" name="companyname" value="{{ old('companyname',$applestabl->companyname) }}" required autocomplete="companyname">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="estadetails">申請・設立内容</label>
                                        </div>
                                        <div class="col-6">
<input id="estadetails" type="text" class="form-control @error('estadetails') is-invalid @enderror" name="estadetails" value="{{ old('estadetails',$applestabl->estadetails) }}" required autocomplete="estadetails">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="delivery_at">納期</label>
                                        </div>
                                        <div class="col-6">
                                            @php
                                                $str = "";
                                                if (isset($applestabl->delivery_at)) {
                                                    $str = ( new DateTime($applestabl->delivery_at))->format('Y-m-d');
                                                }
                                            @endphp
                                            <input type="date" class="form-control" name="delivery_at" value="{{$str}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="mail_flg">申請・郵送</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="mail_flg" name="mail_flg">
                                                @foreach ($loop_mail_flg as $loop_mail_flg2)
                                                @if ($loop_mail_flg2['no']==$applestabl->mail_flg)
                                            <option selected="selected" value={{$applestabl->mail_flg}}>{{ $loop_mail_flg2['name'] }}</option>
                                                @else
                                                    @if ($loop_mail_flg2['no']==0)
                                                    <option disabled value="{{ $loop_mail_flg2['no'] }}">{{ $loop_mail_flg2['name'] }}</option>
                                                    @else
                                                    <option value="{{ $loop_mail_flg2['no'] }}">{{ $loop_mail_flg2['name'] }}</option>
                                                    @endif
                                                @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary me-md-2">保存</button>

                                    <a class="btn btn-primary" href="{{route('applestabl.index')}}">戻る</a>
                                </div>

                            </div>
                            <!-- /.card-footer -->
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
ChangeSideBar("nav-item-system-user");

@endsection
