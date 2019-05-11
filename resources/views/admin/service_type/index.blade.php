@extends('admin.layout.app')
@section('title', '服务类别')
@section('css')
    <!--Footable-->
    <link href="{{ asset('plugins/nestable/dist/css/jquery.nestable.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- Page-Title -->
    @include('admin.layout.location')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">服务类别</h4>
                <p class="text-muted m-b-30 font-13">
                    服务类别，鼠标滑动到分类上并按住左键可以实现自由拖拽排序
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            {{--<div class="form-group mr-2">
                                <button class="btn btn-success" onclick="openIframe('导入', '{{ route('user.import') }}')"><i class="fa fa-plus mr-2"></i> 导入</button>
                            </div>--}}
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('service_type.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-pink" disabled="" id="save-node" onclick="saveNode()"><i class="fa fa-save"></i> 保存</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-custom" onclick="expandAll()"><i class="fa fa-plus"></i> 展开</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-success" onclick="collapseAll()"><i class="fa fa-minus"></i> 收起</button>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($items))
                    <div class="dd">
                        <ol class="dd-list">
                            {!! $items !!}
                        </ol>
                    </div>
                    @else
                    <p class="text-muted">暂无数据！</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!--FooTable-->
    <script src="{{ asset('plugins/nestable/dist/js/jquery.nestable.js') }}"></script>
@endsection
@section('script')
    <script>
        $('.dd').nestable({ /* config options */ });

        $('.dd').on('change', function() {
            //console.log($('.dd').nestable('serialize'));
            $('#save-node').attr('disabled', false);
        });

        function saveNode() {
            var ids = $('.dd').nestable('serialize');
            var load = layer.load(2);
            $.ajax({
                type: 'post',
                url: '{{ route('service_type.save-node') }}',
                dataType: 'json',
                data: {ids: ids},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                complete: function () {
                    layer.close(load);
                },
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg(res.msg, {time:1500}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function (xhr, state) {
                    layer.msg('服务器错误');
                }
            });
        }
    </script>
@endsection