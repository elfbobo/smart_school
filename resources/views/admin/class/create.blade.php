@extends('admin.layout.form')
@section('title', '新增')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form data-parsley-validate>
                    <div class="form-group">
                        <label for="">班级代码</label>
                        <input type="text" class="form-control" name="class_code"
                               placeholder="请输入班级代码，字母或数字组成，不超过10位"
                               maxlength="10"
                               required
                        >
                        <p class="text-muted form-text">请输入班级代码，字母或数字组成，不超过10位</p>
                    </div>
                    <div class="form-group">
                        <label for="">班级名称</label>
                        <input type="text" class="form-control"
                               name="class_name"
                               placeholder="请输入班级名称，不超过20位"
                               maxlength="20"
                               required
                        >
                        <p class="text-muted form-text">请输入班级名称，不超过20位</p>
                    </div>
                    <div class="form-group">
                        <label for="">班级简称</label>
                        <input type="number" class="form-control"
                               name="class_short_name"
                               placeholder="请输入班级简称，不超过10个字符"
                               max="10"
                        >
                    </div>
                    <div class="form-group">
                        <label for="">所属年级</label>
                        <select name="grade" id="" class="form-control select2" required>
                            <option value=""></option>
                            @foreach(getYear() as $year)
                                <option value="{{ $year }}">{{ $year }}级</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">所属学部</label>
                        <select name="dept_code" id="" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($dept as $code => $item)
                                <option value="{{ $code .'|'.$item }}">{{ '['.$code.']'.$item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">所属专业</label>
                        <select name="course_code" id="" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($course as $code => $item)
                                <option value="{{ $code .'|'.$item }}">{{ '['.$code.']'.$item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">是否使用</label>
                        <select name="status" id="" class="form-control">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">是否在校</label>
                        <select name="in_school" id="" class="form-control">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">备注</label>
                        <textarea name="remark" rows="5" class="form-control" placeholder="不超过200字" maxlength="200"></textarea>
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
                return postFormData(formData, '{{ route('class.store') }}', 'post', true)
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