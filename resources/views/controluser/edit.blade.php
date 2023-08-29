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
                            <h3 class="card-title">複数法人情報</h3>
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

                        <form action="{{ route('ctluser.update',$controlusers->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="" >所属組織</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="organization_id" name="organization_id">
                                                @foreach ($organizations as $organization2)
                                    <option value="{{$organization2->id}}"
                                                        @if($controlusers->organization_id===$organization2->id) selected=""
                                                        @endif > {{$organization2->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">ユーザー名</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="user_id" name="user_id">
                                                @foreach ($users as $users2)
                                                    @if($users2->id==$controlusers->user_id)
                                                        <option selected="selected" value="{{$users2->id}}">{{$users2->name}}</option>
                                                    @else
                                                        <option value="{{$users2->id}}">{{$users2->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="">顧客名</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select d-block w-100" id="customer_id" name="customer_id">
                                                @foreach ($customers as $customers2)
                                                    @if($customers2->id==$controlusers->customer_id)
        <option selected="selected" value="{{$customers2->id}}">{{$customers2->business_name}}</option>
                                                    @else
        <option value="{{$customers2->id}}">{{$customers2->business_name}}</option>
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

                                    <a class="btn btn-primary" href="{{route('ctluser.index')}}">戻る</a>
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
