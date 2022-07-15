@extends('admin.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ trans('label.admin.title.t4') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @if (session('failed'))
                <div class="alert alert-danger">
                    {{ session('failed') }}
                </div>
            @elseif(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col md 12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">

                                <form action="{{ url("admin/news/".$new->id."/update") }}" enctype="multipart/form-data" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-row">
                                        <div class="col-md-7">
                                            <div class="form-group col-md-12">
                                                <label> {{ trans("label.admin.news.title.t1") }} <span style="color: red"> *</span> </label>
                                                <h4>{{$new->title}}</h4>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label> {{ trans("label.admin.news.title.t2") }}  <span style="color: red"> *</span> </label>
                                                <textarea name="description" class="form-control">{{ $new->description }}</textarea>
                                                @if($errors->has('description'))
                                                    <p style="height: 0; margin: 0; color: red">
                                                        {{$errors->first('description')}}
                                                    </p>
                                                    <br>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label> {{ trans("label.admin.news.title.t3") }}  <span style="color: red"> *</span> </label>
                                                <textarea name="detail" class="form-control" id="detail">{{ $new->detail }}</textarea>
                                                @if($errors->has('detail'))
                                                    <p style="height: 0; margin: 0; color: red">
                                                        {{$errors->first('detail')}}
                                                    </p>
                                                    <br>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group col-md-12">
                                                <label> {{ trans("label.admin.news.title.t4") }}  <span style="color: red"> *</span> </label>
                                                <input type="file" name="photo" id="photo" accept="image/*" class="form-control"><br>
                                                <div id="img_preview">
                                                    <img id="imgPreview" src="{{asset("image/".$new->image)}}" alt="pic" />
                                                </div>
                                                @if($errors->has('photo'))
                                                    <p style="height: 0; margin: 0; color: red">
                                                        {{$errors->first('photo')}}
                                                    </p>
                                                    <br>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label> {{ trans("label.admin.news.title.t5") }}  <span style="color: red"> *</span> </label><br>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="radio" name="state" value="{{ trans("label.admin.news.state.s1") }}" @if($new->state == 0) checked @endif>
                                                        {{trans("label.admin.news.state.s1") }}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="radio" name="state" value="{{ trans("label.admin.news.state.s2") }}" @if($new->state == 1) checked @endif>
                                                        {{trans("label.admin.news.state.s2") }}
                                                    </div>
                                                </div>
                                                @if($errors->has('state'))
                                                    <p style="height: 0; margin: 0; color: red">
                                                        {{$errors->first('state')}}
                                                    </p>
                                                    <br>
                                                @endif
                                            </div>

                                            <div class="form-group col-md-12 text-right">
                                                <a href="{{ url('/admin/news') }}" class="btn btn-secondary"> {{ trans('label.btn.back') }} </a>
                                                <button type="submit" class="btn btn-primary"> {{ trans("label.btn.save") }} </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section("custom-js")
    <script>
        CKEDITOR.replace("detail");
        $(document).ready(() => {
            $("#photo").change(function () {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $("#imgPreview")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        })
    </script>
@endsection

