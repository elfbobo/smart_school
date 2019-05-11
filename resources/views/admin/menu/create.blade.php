@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-2 col-form-label">上级菜单<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <select name="parent_id" id="" class="form-control select2" required>
                                <option value="0">顶级菜单</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu['id'] }}">{{ $menu['title_prefix'] . $menu['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">菜单名称<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="title" class="form-control"
                                   placeholder="请输入菜单名称，不超过20个字"
                                   maxlength="20" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">是否菜单<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="1" name="is_menu" checked>
                                <label> 是</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="0" name="is_menu">
                                <label> 否</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">规则</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="uri" placeholder="路由规则，如：menus.create" autocomplete="off">
                            <p class="text-muted font-14 m-t-10">为菜单时不用填写，采用restful风格，必须是：menus.create 这种写法</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">图标</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="icon" placeholder="仅支持fontawesome图标，如：fa fa-plus" autocomplete="off">
                            <p class="text-muted font-14 m-t-10">仅支持fontawesome图标，如：fa fa-plus
                                <a href="javascript:;" onclick="openIframe('选择图标，复制粘贴', '{{ url('select-icons') }}', 600, 400)">选择图标</a></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">排序</label>
                        <div class="col-10">
                            <input type="number" class="form-control" name="sort" value="0" min="0">
                            <p class="text-muted font-14 m-t-10">数字越小，越靠前</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">创建子功能<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="1" name="subs">
                                <label> 是</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="0" name="subs" checked>
                                <label> 否</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="action" style="display: none;">
                        <label class="col-2 col-form-label">勾选功能</label>
                        <div class="col-10">
                            @foreach($actions as $action)
                            <div class="checkbox checkbox-custom form-check-inline">
                                <input type="checkbox" name="action[]" value="{{ $action['e_name'] . '-' . $action['c_name'] }}" {{ $action['is_check'] ? 'checked' : '' }}>
                                <label for="inlineCheckbox1"> {{ $action['c_name'] }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">是否隐藏<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="1" name="status">
                                <label> 是</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="0" name="status" checked>
                                <label> 否</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-10 offset-2">
                            <button type="submit" class="btn btn-custom waves-effect waves-light">
                                提交
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('menu.store') }}')
            });
        });

        $('input[name="subs"]').on('click', function () {
            if ($(this).val() == 1) {
                $('#action').show();
            } else {
                $('#action').hide();
            }
        });
    </script>
@endsection