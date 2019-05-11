@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">上级类别<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <select name="parent_id" class="form-control">
                                <option value="0">一级类别</option>
                                @if(!empty($types))
                                @foreach($types as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['title_display'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-muted font-14 m-t-10">请输入类别名称，长度4-20位</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">类别名称<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"
                                   placeholder="请输入类别名称，长度4-20位"
                                   minlength="4"
                                   maxlength="20" required autocomplete="off">
                            <p class="text-muted font-14 m-t-10">请输入类别名称，长度4-20位</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-10 offset-2">
                            <input type="hidden" name="icon_url" id="avatar">
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
@section('before-js')
    <!--上传插件-->
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            Parsley.on('form:submit', function (e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('service_type.store') }}')
            });
        });
    </script>
@endsection