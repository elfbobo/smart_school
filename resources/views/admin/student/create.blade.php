@extends('admin.layout.app')
@section('title', '新增学生信息')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    @include('admin.layout.location')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form data-parsley-validate>
                    <div class="form-group">
                        <label class="">头像</label>
                        <div id="show-img" style="margin-bottom: 10px;">
                        </div>
                        <input type="file"
                               name="files"
                               class="filestyle"
                               data-input="false"
                               data-btnClass="btn-success"
                               data-disabled="false"
                               data-text="上传">
                        <p class="text-muted">支持png、jpg、jpeg格式，大小不超过1M</p>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-custom">基本信息</div>
                        <div style="clear: both;">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">学号<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="union_id"
                                           placeholder="请输入学号，小写字母或数字组成"
                                           minlength="4"
                                           maxlength="20"
                                           required
                                    >
                                    <p class="form-text text-muted">学号由小写字母或数字组成，不超过20位</p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">姓名<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="请输入姓名，长度2-30位"
                                           minlength="2"
                                           maxlength="30" required autocomplete="off">
                                    <p class="text-muted m-t-10">请输入姓名，长度2-30位</p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">学籍号</label>
                                    <input type="text" name="school_roll_code" class="form-control"
                                           placeholder="请输入学籍号，长度4-12位"
                                           minlength="4"
                                           maxlength="12" autocomplete="off">
                                    <p class="text-muted m-t-10">请输入学籍号，长度4-12位</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">性别</label>
                                    <select name="gender" id="" class="form-control">
                                        <option value="1">男</option>
                                        <option value="2">女</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">姓名拼音</label>
                                    <input type="text" class="form-control" name="name_py" placeholder="姓名拼音" maxlength="20">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">曾用名</label>
                                    <input type="text" class="form-control" name="used_name" placeholder="曾用名" maxlength="30">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">一卡通卡号</label>
                                    <input type="text" class="form-control" name="card_number" placeholder="一卡通卡号" maxlength="30">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">银行卡号</label>
                                    <input type="text" class="form-control"
                                           name="bank_card"
                                           placeholder="银行卡号" maxlength="30">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">出生日期</label>
                                    <input type="text" class="form-control jsdate"
                                           name="birthday"
                                           placeholder="出生日期"
                                           autocomplete="off"
                                    >
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">证件类型</label>
                                    <select name="id_type" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($id_type as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">证件号码</label>
                                    <input type="text" class="form-control"
                                           name="id_card"
                                           placeholder="证件号码" maxlength="30">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">民族</label>
                                    <select name="nation" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($nation as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">政治面貌</label>
                                    <select name="politics_status" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($politics_status as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">宗教信仰</label>
                                    <select name="religion" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($religion as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">婚姻状况</label>
                                    <select name="marital_status" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($marital_status as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">健康状况</label>
                                    <select name="health_status" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($health_status as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">来源国别</label>
                                    <select name="country" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($country as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">籍贯</label>
                                    <select name="birthplace" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($birthplace as $v)
                                            <option value="{{ $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">港澳台侨</label>
                                    <select name="gatq" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($gatq as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">户口性质</label>
                                    <select name="hkxz" id="" class="form-control">
                                        <option value="">请选择</option>
                                        <option value="1">农业户口</option>
                                        <option value="2">非农业户口</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">户口所在地</label>
                                    <input type="text" class="form-control" name="hkszd" placeholder="户口所在地">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label class="">户籍所在街镇名称</label>
                                    <input type="text" class="form-control" name="hjszjzmc" placeholder="户籍所在街镇名称">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="">户籍所在居委会(村委会)名称</label>
                                    <input type="text" class="form-control" name="hjszcwhmc" placeholder="户籍所在居委会(村委会)名称">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="">血型</label>
                                    <select name="blood_type" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($blood_type as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="">身份标识</label>
                                    <select name="identity_type" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($identity_type as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="">身高（cm）</label>
                                    <input type="number" class="form-control" name="height" placeholder="身高" step="0.01">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="">体重（kg）</label>
                                    <input type="number" class="form-control" name="weight" placeholder="体重" step="0.01">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="">特长</label>
                                    <input type="text" class="form-control" name="specialty" placeholder="特长" maxlength="250">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-info">学籍信息</div>
                        <div style="clear: both">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">学生类别</label>
                                    <select name="student_type" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($student_type as $k => $v)
                                            <option value="{{ $k . '|' . $v }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">所在年级</label>
                                    <select name="grade" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach(getYear() as $v)
                                            <option value="{{ $v }}">{{ $v }}级</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">所在学部</label>
                                    <select name="dept_code" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($dept as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">所在专业</label>
                                    <select name="course_code" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($course as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">所在班级</label>
                                    <select name="class_code" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($class as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">就读学历</label>
                                    <select name="education_code" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach($education as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">学制</label>
                                    <input type="number" class="form-control"
                                           name="shooling_length"
                                           min="1"
                                           max="10"
                                           placeholder="学制"
                                    >
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">是否在籍</label>
                                    <select name="in_registry" id="" class="form-control">
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">是否在校</label>
                                    <select name="in_school" id="" class="form-control">
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">培养方式</label>
                                    <select name="train_type" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($train_type as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学日期</label>
                                    <input type="text" class="form-control jsdate"
                                           name="entry_date" placeholder="入学日期" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-primary">入学前信息</div>
                        <div style="clear: both">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="">何省市报考</label>
                                    <select name="enroll_province_city" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($birthplace as $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">生源地</label>
                                    <select name="student_source_place" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($birthplace as $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">考生号</label>
                                    <input type="text" class="form-control"
                                           name="candidate_number"
                                           placeholder="考生号"
                                           maxlength="30"
                                    >
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">准考证号</label>
                                    <input type="text" class="form-control"
                                           name="pass_number" placeholder="准考证号"
                                           maxlength="30">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学前单位</label>
                                    <input type="text" class="form-control"
                                           name="entry_before_unit" placeholder="入学前单位"
                                           maxlength="200">
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="">入学年级</label>
                                    <select name="entry_grade" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach(getYear() as $v)
                                            <option value="{{ $v }}">{{ $v }}级</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学学部</label>
                                    <select name="entry_dept_name" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($dept as $code => $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学专业</label>
                                    <select name="entry_course" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($course as $code => $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学方式</label>
                                    <select name="entry_type" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach($entry_type as $code => $name)
                                            <option value="{{ $code . '|' . $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">学生来源</label>
                                    <input type="text" class="form-control" name="student_source" maxlength="100" placeholder="学生来源">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">入学总成绩</label>
                                    <input type="number" class="form-control"
                                           name="total_score"
                                           placeholder="入学总成绩"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-success">毕业信息</div>
                        <div style="clear: both">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="">预计毕业年份</label>
                                    <select name="graduate_year" id="" class="form-control select2" data-allow-clear="true">
                                        <option value=""></option>
                                        @foreach(getYear() as $v)
                                            <option value="{{ $v }}">{{ $v }}年</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="">实际毕业时间</label>
                                    <input type="text" class="form-control jsdate"
                                           name="graduate_date"
                                           placeholder="实际毕业时间"
                                           autocomplete="off"
                                    >
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="">毕业评语（1000字）</label>
                                    <textarea class="form-control" name="graduate_comments" rows="6"
                                              placeholder="毕业评语"
                                              maxlength="1000"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-pink">联系方式</div>
                        <div style="clear: both">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label class="">通讯地址</label>
                                    <input type="text" class="form-control"
                                           name="address"
                                           placeholder="通讯地址"
                                           maxlength="200"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">通讯邮编</label>
                                    <input type="text" class="form-control"
                                           name="zip_code"
                                           placeholder="通讯邮编"
                                           maxlength="8"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-8">
                                    <label class="">家庭地址</label>
                                    <input type="text" class="form-control"
                                           name="home_address"
                                           placeholder="家庭地址"
                                           maxlength="200"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">家庭邮编</label>
                                    <input type="text" class="form-control"
                                           name="home_zip_code"
                                           placeholder="家庭邮编"
                                           maxlength="8"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">家庭电话</label>
                                    <input type="text" class="form-control"
                                           name="home_tel"
                                           placeholder="家庭电话"
                                           maxlength="15"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">个人邮箱</label>
                                    <input type="email" class="form-control"
                                           name="email"
                                           placeholder="个人邮箱"
                                           maxlength="100"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">个人手机号</label>
                                    <input type="text" class="form-control"
                                           name="phone"
                                           placeholder="个人手机号"
                                           maxlength="11"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">联系电话</label>
                                    <input type="text" class="form-control"
                                           name="tel"
                                           placeholder="联系电话"
                                           maxlength="15"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">QQ</label>
                                    <input type="text" class="form-control"
                                           name="qq"
                                           placeholder="QQ"
                                           maxlength="15"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="">MSN</label>
                                    <input type="text" class="form-control"
                                           name="msn"
                                           placeholder="MSN"
                                           maxlength="100"
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="">个人主页</label>
                                    <input type="text" class="form-control"
                                           name="personal_home_page"
                                           placeholder="个人主页地址"
                                           maxlength="250"
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ribbon-box">
                        <div class="ribbon ribbon-purple">其他</div>
                        <div style="clear: both">
                            <div class="form-group">
                                <label for="">备注</label>
                                <textarea name="remark" rows="5" class="form-control" placeholder="不超过200字" maxlength="200"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <input type="hidden" name="avatar" id="avatar">
                        <button type="submit" class="btn btn-custom waves-effect waves-light">
                            提交
                        </button>
                        <button type="button" class="btn btn-info waves-effect waves-light"
                                onclick="window.history.back(-1)">取消</button>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
    <script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <!--上传插件-->
    <script src="{{ asset('plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script>

    <!-- Parsley js -->
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            initDatePicker('.jsdate'); //初始化日期控件
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('student.store') }}', 'post', false)
            });


            var upload = new uploadFile('.filestyle', '/api/upload');
            var load;
            upload.setMaxFileSize(1024*1024);
            upload.init();
            upload = upload.btInstance;
            //图片添加完成后触发的事件
            upload.on("fileuploadadd", function(e, data) {
                load = layer.msg('正在上传，请稍等片刻...', {time:0, icon: 16, shade:0.3});
            })
            //当一个单独的文件处理队列结束触发(验证文件格式和大小)
            upload.on("fileuploadprocessalways", function(e, data) {
                //获取文件
                file = data.files[0];
                //获取错误信息
                if (file.error) {
                    layer.msg(file.error);
                }
            })
            //显示上传进度条
            upload.on("fileuploadprogressall", function(e, data) {

            })
            //上传请求失败时触发的回调函数
            upload.on("fileuploadfail", function(e, data) {
                layer.msg('上传失败');
            })
            //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e, data) {
                    var res = data.result;
                    if (res.code == 1000) {
                        $('#show-img').children().remove();
                        $('#show-img').append('<img src="' + res.extra.filepath + '" alt="" width="120" class="">');
                        $('#avatar').val(res.extra.filepath);
                    }  else {
                        layer.msg(res.msg);
                    }
                })
                //上传请求结束后，不管成功，错误或者中止都会被触发
                .on("fileuploadalways", function(e, data) {
                    layer.close(load);
                    $('button[type=submit]').attr('disabled', false);
                })

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