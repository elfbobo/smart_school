@extends('admin.layout.form')
@section('title', '编辑')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form data-parsley-validate>
                    <div class="form-group">
                        <label for="">专业代码</label>
                        <input type="text" class="form-control" name="code"
                               placeholder="请输入专业代码，字母或数字组成，不超过10位"
                               maxlength="10"
                               value="{{ $info->code }}"
                               required
                        >
                        <p class="text-muted form-text">请输入专业代码，字母或数字组成，不超过10位</p>
                    </div>
                    <div class="form-group">
                        <label for="">专业名称</label>
                        <input type="text" class="form-control"
                               name="name"
                               placeholder="请输入专业名称，不超过50位"
                               maxlength="50"
                               value="{{ $info->name }}"
                               required
                        >
                        <p class="text-muted form-text">请输入专业名称，不超过50位</p>
                    </div>
                    <div class="form-group">
                        <label for="">学制</label>
                        <input type="number" class="form-control"
                               name="schooling_length"
                               placeholder="请输入学制，必须是正整数"
                               max="10"
                               value="{{ $info->schooling_length }}"
                               required
                        >
                    </div>
                    <div class="form-group">
                        <label for="">专业负责人</label>
                        <select name="leader_code" id="" class="form-control select2">
                            <option value=""></option>
                            @foreach($users as $code => $user)
                                <option value="{{ $code }}" {{ $info->leader_code == $code ? 'selected' : '' }}>[{{ $code }}]{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">是否使用</label>
                        <select name="status" id="" class="form-control">
                            <option value="1" >是</option>
                            <option value="0" {{ $info->status == '0' ? 'selected' : '' }}>否</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">备注</label>
                        <textarea name="remark" rows="5" class="form-control" placeholder="不超过200字" maxlength="200">{{ $info->remark }}</textarea>
                    </div>


                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-custom waves-effect waves-light">
                            提交
                        </button>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('professional.update', ['id' => $info->id]) }}', 'put', true)
            });
        });


        //多选框
        /*$("#my-select").bootstrapDualListbox({
            nonSelectedListLabel: false,
            selectedListLabel: false,
            filterTextClear: '全部',
            filterPlaceHolder: '过滤条件',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            moveAllLabel: '添加全部',
            moveSelectedLabel: '添加选中',
            removeAllLabel: '移除全部',
            removeSelectedLabel: '移除选中',
            infoText: '选中/未选中共 {0} 项',
            infoTextFiltered: '从 {1} 项 筛选 {0} 项',
            infoTextEmpty: '空',
        });*/
    </script>
@endsection