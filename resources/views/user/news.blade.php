<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/fontawesome-free/css/all.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/jqvmap/jqvmap.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("admin/dist/css/adminlte.min.css") }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/daterangepicker/daterangepicker.css") }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset("admin/plugins/summernote/summernote-bs4.min.css") }}">
    <title>{{ trans('label.admin.title.t5') }}</title>
</head>
<style>
    body{
        background: linear-gradient(120deg,#2980b9, #8e44ad);
    }
</style>
<body>
<div id="root" class="py-5">
    <div class="container">
        @if(session("failed"))
            <div class="alert alert-danger">
                {{ session("failed") }}
            </div>
        @elseif(session("success"))
            <div class="alert alert-success">
                {{ session("success") }}
            </div>
        @endif
        <div class="d-flex justify-content-end py-2" >
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-header">
                        <h2>{{ trans('label.admin.title.t5') }}</h2>
                    </div>
                    @if(count($news) == 0)
                        <div class="card-body">
                            <p class="card-text" style="font-size: 20px; color: red">{{ trans('auth.empty') }}</p>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="row">
                                @foreach($news as $new)
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="card text-center">
                                            <img class="card-img-top" src="{{asset('thumbnail/'.$new->image)}}" alt="Card image cap">
                                            <div class="card-body">
                                                <h3 class="card-text"><a href="{{ url("/news/".$new->id."/detail") }}">{{$new->title}}</a></h3>
                                                <p class="card-text">{{$new->description}}</p>
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ url("/news/".$new->id."/detail") }}">{{trans("label.admin.news.title.t3")}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end" style="margin-right: 3%">
                        {!! $news->appends($_GET)->links("pagination::bootstrap-4") !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/1da19b0fb1.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset("admin/plugins/jquery/jquery.min.js") }}"></script>

</body>
</html>
