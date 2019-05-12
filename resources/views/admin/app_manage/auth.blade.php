@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label class="">服务类别</label>
                        {{--<div class="checkbox checkbox-success">
                            <input id="checkbox6c" type="checkbox" class="check-all">
                            <label for="checkbox6c">
                                全选
                            </label>
                        </div>--}}
                        <ul class="list-group">
                            @foreach($serviceType as $item)
                            <li class="list-group-item">
                                <div class="checkbox checkbox-success form-check-inline">
                                    <input type="checkbox" id="inlineCheckbox{{ $item['id'] }}" value="{{ $item['id'] }}" onclick="checkChild(this)"
                                           name="service_type[]"
                                           {{ in_array($item['id'], $serviceTypeIds) ? 'checked' : '' }}
                                    >
                                    <label for="inlineCheckbox{{ $item['id'] }}"> {{ $item['title'] }}</label>
                                </div>
                                @if(isset($item['child']))
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            @foreach($item['child'] as $child)
                                            <div class="checkbox checkbox-success form-check-inline">
                                                <input type="checkbox" id="inlineCheckbox{{ $child['id'] }}" value="{{ $child['id'] }}"
                                                       name="service_type[]"
                                                        {{ in_array($child['id'], $serviceTypeIds) ? 'checked' : '' }}
                                                >
                                                <label for="inlineCheckbox{{ $child['id'] }}"> {{ $child['title'] }}</label>
                                            </div>
                                            @endforeach
                                        </li>
                                    </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="">服务角色</label>
                        <br>
                        <select name="app_role[]" id="my-select" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ in_array($role->id, $appRoleIds) ? 'selected' : '' }}>{{ $role->name }}</option>
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
    <!--上传插件-->
    {{--<script src="{{ asset('plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script>--}}
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            Parsley.on('form:submit', function (e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('app_manage.auth', ['id' => $id]) }}', 'post', true)
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

        function checkChild(obj) {
            if ($(obj).is(":checked")) {
                //$('input[type=checkbox]').prop('checked', true);
                $(obj).parent().siblings().find('input[type=checkbox]').prop('checked', true);
                //$(obj).parent().siblings().find('input[type=checkbox]').attr('disabled', true);
            } else {
                $(obj).parent().siblings().find('input[type=checkbox]').prop('checked', false);
                //$(obj).parent().siblings().find('input[type=checkbox]').attr('disabled', false);
            }
        }
    </script>
@endsection