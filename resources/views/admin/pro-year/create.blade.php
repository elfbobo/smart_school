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
                        <label for="">选择年级</label>
                        <select name="grade" id="" class="form-control select2">
                            <option value=""></option>
                            @foreach(getYear() as $year)
                                <option value="{{ $year }}">{{ $year }}级</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">选择学部</label>
                        <select name="dept_code" id="" class="form-control select2">
                            <option value=""></option>
                            @foreach($dept as $code => $name)
                                <option value="{{ $code . '|' . $name }}">{{ '[' . $code . ']' . $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">选择专业</label>
                        <select name="course_code" id="" class="form-control select2">
                            <option value=""></option>
                            @foreach($course as $code => $name)
                                <option value="{{ $code . '|' . $name }}">{{ '[' . $code . ']' . $name }}</option>
                            @endforeach
                        </select>
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
                return postFormData(formData, '{{ route('year-professional.store') }}', 'post', true)
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