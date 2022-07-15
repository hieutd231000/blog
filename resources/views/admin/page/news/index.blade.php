@extends('admin.master')
@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            {{--@include('admin.pages.partials.errors')--}}
            @if(session("success"))
                <div class="alert alert-success">
                    {{ session("success") }}
                </div>
            @elseif(session("failed"))
                <div class="alert alert-danger">
                    {{ session("failed") }}
                </div>
            @endif
            <div class="container-fluid">
                <div class="py-4 d-flex justify-content-end">
                    <a type="button" href="{{url("/admin/news/add")}}" class="btn btn-success">Add</a>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{trans("label.admin.title.t2")}}</h2>
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
                                                <div class="card">
                                                    <img class="card-img-top" src="{{asset('thumbnail/'.$new->image)}}" alt="Card image cap">
                                                    <div class="card-body">
                                                        <h3 class="card-title"><b>{{$new->title}}</b></h3>
                                                        <p class="card-text">{{$new->description}}</p>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="info-box-text py-1">
                                                                <a class="fa fa-edit" href="{{ url("/admin/news/".$new->id."/edit") }}" style="margin-right: 10px"></a>
                                                                <a class="fa fa-trash" href="{{ url("/admin/news/delete/" . $new->id) }}"></a>
                                                            </span>
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
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <div class="modal" id="show_modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="file_name"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Upload Date: <span id="file_upload"></span></p>
                        <p>File Size: <span id="file_size"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal" id="upload_modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url("/file-manage/upload") }}" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="file" name="file" accept=".zip" class="form-control" id="file-name">
                            </div>
                            <button type="submit" onclick="processSaveFile()" class="btn btn-primary float-right">
                                <i class="fa fa-spinner fa-spin" id="loading" style="display: none"></i>
                                <span id="btn-save-file">Save File</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

