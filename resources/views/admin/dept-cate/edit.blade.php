@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label>部门类别代码<span class="text-danger">*</span></label>
                        <input type="text" name="code" required=""
                               placeholder="部门类别代码，不超过10位"
                               maxlength="10"
                               class="form-control"
                               autocomplete="off"
                               value="{{ $info->code }}"
                        >
                        <p class="form-text text-muted">部门类别代码不超过10位，字母或数字组成</p>
                    </div>
                    <div class="form-group">
                        <label>部门类别名称<span class="text-danger">*</span></label>
                        <input type="text" name="name" required=""
                               placeholder="请输入部门类别名称，不超过30位"
                               maxlength="30"
                               class="form-control"
                               autocomplete="off"
                               value="{{ $info->name }}"
                        >
                        <p class="form-text text-muted">部门类别名称不超过30位</p>
                    </div>
                    <div class="form-group">
                        <label for="">是否使用</label>
                        <br>
                        <div class="radio radio-info form-check-inline">
                            <input type="radio" id="inlineRadio1" value="1" name="status" {{ $info->status == '1' ? 'checked' : '' }}>
                            <label for="inlineRadio1"> 是</label>
                        </div>
                        <div class="radio radio-info form-check-inline">
                            <input type="radio" id="inlineRadio2" value="0" name="status" {{ $info->status == '0' ? 'checked' : '' }}>
                            <label for="inlineRadio2"> 否</label>
                        </div>
                    </div>

                    <div class="form-group m-b-0">
                        <button class="btn btn-custom waves-effect waves-light" type="submit">
                            提交
                        </button>
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
                return postFormData(formData, '{{ route('dept-cate.update', ['id' => $info->id]) }}', 'put', true)
            });
        });
    </script>
@endsection