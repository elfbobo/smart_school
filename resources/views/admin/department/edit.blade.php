@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label>上级部门<span class="text-danger">*</span></label>
                        <select name="parent_id" id="" class="form-control select2" required>
                            <option value="0">一级部门</option>
                            @foreach($dpts as $dpt)
                                <option value="{{ $dpt['id'] }}" {{ $info->parent_id == $dpt['id'] ? 'selected' : '' }}>{!! $dpt['title_prefix'] . $dpt['title'] !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>部门编号<span class="text-danger">*</span></label>
                        <input type="text" name="code" required=""
                               placeholder="请输入部门编号，不超过10位"
                               maxlength="10"
                               class="form-control"
                               value="{{ $info->code }}"
                               autocomplete="off"
                        >
                        <p class="form-text text-muted">部门编号不超过10位，必须是字母或数字组成</p>
                    </div>
                    <div class="form-group">
                        <label>部门名称<span class="text-danger">*</span></label>
                        <input type="text" name="name" required=""
                               placeholder="请输入部门名称，不超过30位"
                               maxlength="30"
                               class="form-control"
                               value="{{ $info->name }}"
                               autocomplete="off"
                        >
                        <p class="form-text text-muted">部门名称不超过30位</p>
                    </div>

                    <div class="form-group">
                        <label for="">部门类别</label>
                        <select name="category" id="" class="form-control" required>
                            <option value="">选择部门类别</option>
                            @foreach($category as $item)
                                <option value="{{ $item->code }}" {{ $info->category == $item->code ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">部门办别</label>
                        <select name="bbdm" id="" class="form-control">
                            <option value="">选择部门办别</option>
                            @foreach($bb as $item)
                                <option value="{{ $item->code }}" {{ $info->bbdm == $item->code ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">部门负责人</label>
                        <select name="leader" id="" class="form-control select2" style="width: 100%;">
                            <option value=""></option>
                            @foreach($users as $code => $user)
                                <option value="{{ $code }}" {{ $info->leader==$code?'selected':'' }}>[{{ $code }}]{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">分管校长</label>
                        <br>
                        <select name="principal" id="" class="form-control select2" style="width: 100%;">
                            <option value=""></option>
                            @foreach($users as $code => $user)
                                <option value="{{ $code }}" {{ $info->principal==$code?'selected':'' }}>[{{ $code }}]{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">备注</label>
                        <textarea name="remark" id="" class="form-control" rows="5" maxlength="200" placeholder="不超过200字">{{ $info->remark }}</textarea>
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
                return postFormData(formData, '{{ route('department.update', ['id' => $info->id]) }}', 'put', true)
            });
        });
    </script>
@endsection