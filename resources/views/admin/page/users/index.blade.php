@extends('admin.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ trans('label.admin.title.t1') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
{{--            @include('admin.pages.partials.errors')--}}
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
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            @if(count($data) == 0)
                                <div class="card-body">
                                    <p class="card-text" style="font-size: 20px; color: red">{{ trans('auth.admin.empty') }}</p>
                                </div>
                            @else
                                <div class="card-body">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th> {{ trans("label.admin.thead.th1") }} </th>
                                            <th> {{ trans("label.admin.thead.th2") }} </th>
                                            <th> {{ trans("label.admin.thead.th3") }} </th>
                                            <th> {{ trans("label.admin.thead.th5") }} </th>
                                            <th> {{ trans("label.admin.thead.th7") }} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $user)
                                            <tr>
                                                <td> {{ $user->id }} </td>
                                                <td> {{ $user->name }} </td>
                                                <td> {{ $user->email }} </td>
                                                <td> {{ $user->role }} </td>
                                                <td>
                                                    <button type="button" onclick="confirmDelete( {{ $user->id }} )" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteUser"> {{ trans("label.btn.delete") }} </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>

                                    </table>
                                </div>
                            @endif
                            <div class="d-flex justify-content-end" style="margin-right: 3%">
                                {!! $data->appends($_GET)->links("pagination::bootstrap-4") !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ url("/admin/users/delete") }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="id_user" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Once deleted, you will not be able to recover this Data!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
                        <button type="submit" class="btn btn-danger">Ok</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("custom-js")
    <script>
        /**
         * Confirm delete user
         * @param id
         */
        function confirmDelete(id) {
            $("#id_user").val(id);
            $("#deleteUser").modal("show");
        }
    </script>
@endsection
