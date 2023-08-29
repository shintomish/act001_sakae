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
                    <!-- card content ユーザーの編集 -->
                    <div id="card_organization_list_edit" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">利用者情報</h3>
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

                        <form action="{{ route('user.update',$user->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">ユーザー名</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control" name="name" value="{{ old('name',$user->name)}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="" >所属組織</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="organization_id" name="organization_id">
                                                @foreach ($organizations as $organization2)
                                                    <option value="{{$organization2->id}}"
                                                        @if($user->organization_id===$organization2->id) selected=""
                                                        @endif > {{$organization2->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">顧客</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="user_id" name="user_id">
                                                @foreach ($customers as $customers2)
                                                    @if($customers2->id==$user->user_id)
        <option selected="selected" value="{{$customers2->id}}">{{$customers2->business_name}}</option>
                                                    @else
        <option value="{{$customers2->id}}">{{$customers2->business_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">利用区分</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="login_flg" name="login_flg">
                                                {{-- <option value="{{$user->login_flg}}"> </option> --}}
                                                @foreach ($loop_login_flg as $loop_login_flg2)
                                                    @if ($loop_login_flg2['no']==$user->login_flg)
                    <option selected="selected" value={{ $loop_login_flg2['no'] }}>{{ $loop_login_flg2['name'] }}</option>
                                                    @else
                    <option value={{ $loop_login_flg2['no'] }}>{{ $loop_login_flg2['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">管理区分</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="admin_flg" name="admin_flg">
                                                @foreach ($loop_admin_flg as $loop_admin_flg2)
                                                    @if ($loop_admin_flg2['no']==$user->admin_flg)
                    <option selected="selected" value={{ $loop_admin_flg2['no'] }}>{{ $loop_admin_flg2['name'] }}</option>
                                                    @else
                    <option value={{ $loop_admin_flg2['no'] }}>{{ $loop_admin_flg2['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="email">Eメール</label>
                                        </div>

                                        <div class="col-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email',$user->email) }}" required autocomplete="email">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="password">{{ __('Password') }}</label>
                                        </div>

                                        <div class="col-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        </div>

                                        <div class="col-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary me-md-2">保存</button>

                                    <a class="btn btn-primary" href="{{route('user.index')}}">戻る</a>
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
