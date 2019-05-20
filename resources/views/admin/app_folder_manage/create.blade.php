@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label>文件夹名称<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               placeholder="请输入桌面名称，长度4-20位"
                               minlength="4"
                               maxlength="20" required autocomplete="off">
                        <p class="text-muted font-14 m-t-10">请输入文件夹名称，长度4-20位</p>
                    </div>
                    <div class="form-group">
                        <label for="">选择应用</label>
                        <br>
                        <select name="app_ids[]" id="my-select" multiple>
                            @foreach($apps as $k => $app)
                                <option value="{{ $k }}">{{ $app }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
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

@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //app_ids = $("#inprogress").sortable("toArray");
            /*$("#upcoming, #inprogress, #completed").sortable({
                connectWith: ".taskList",
                placeholder: 'task-placeholder',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    app_ids = $("#inprogress").sortable("toArray");
                }
            }).disableSelection();*/
            Parsley.on('form:submit', function (e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('app_folder_manage.store') }}', 'post', true)
            });

            //多选框
            $("#my-select").bootstrapDualListbox({
                nonSelectedListLabel: false,
                selectedListLabel: false,
                filterTextClear: '全部',
                filterPlaceHolder: '过滤条件',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: true,
                moveAllLabel: '添加全部',
                moveSelectedLabel: '添加选中',
                removeAllLabel: '移除全部',
                removeSelectedLabel: '移除选中',
                infoText: '选中/未选中共 {0} 项',
                infoTextFiltered: '从 {1} 项 筛选 {0} 项',
                infoTextEmpty: '空',
            });
        });
    </script>
@endsection