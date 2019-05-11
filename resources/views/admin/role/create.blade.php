@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">角色代码</label>
                        <div class="col-10">
                            <input type="text" name="code" class="form-control"
                                   placeholder="角色代码，非必填项"
                                   minlength="4"
                                   maxlength="20" required autocomplete="off">
                            <p class="form-text text-muted">数字或字母组成，4-20位</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">角色名称<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"
                                   placeholder="请输入角色名称，不超过20个字"
                                   maxlength="20" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">角色描述</label>
                        <div class="col-10">
                            <textarea name="description" class="form-control" rows="5" placeholder="不超过200字..." maxlength="200"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">选择用户</label>
                        <div class="col-10">
                            <select name="users[]" id="" class="select2" multiple data-placeholder="选择用户">
                                @foreach($users as $user)
                                    <option value="{{ $user->code }}">{{ $user->name . '(' . $user->code . ')' }}</option>
                                @endforeach
                            </select>
                            <p class="form-text text-muted">为当前角色分配用户</p>
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">是否启用<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="1" name="status" checked>
                                <label> 是</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="0" name="status">
                                <label> 否</label>
                            </div>
                        </div>
                    </div>--}}
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
                return postFormData(formData, '{{ route('role.store') }}')
            });
        });
    </script>
@endsection