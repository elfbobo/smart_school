jQuery(document).ready(function () {
    // Select2
    $(".select2").select2();


    /**
     * 全选、全不选操作
     */
    $('.check-all').on('click', function() {
        if ($(this).is(":checked")) {
            $('input[type=checkbox]').prop('checked', true);
        } else {
            $('input[type=checkbox]').prop('checked', false);
        }
    })
});


/**
 * Theme: Highdmin - Bootstrap 4 Web App kit
 * Author: Coderthemes
 * Module/App: Main Js
 */
function openIframe(title, url, w, h) {
    title = title || '';
    w = w || 800;
    h = h || 600;
    layer.open({
        type: 2,
        title: title,
        area: [w + 'px', h + 'px'],
        content: url,
    });
}

/**
 * 删除一条记录
 * @param url
 * @param obj
 */
function removeOne(url, obj)
{
    layer.confirm('确认删除吗？', function () {
        var load = layer.load(2);
        $.ajax({
            type: 'delete',
            url: url,
            titmeout: 60000,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            complete: function () {
                layer.close(load);
            },
            success: function (res) {
                if (res.code == 200) {
                    layer.msg(res.msg, {time: 1500}, function () {
                        if (typeof obj !== 'undefined') {
                            //get the footable object
                            var footable = $(obj).parents('table').data('footable');
                            //get the row we are wanting to delete
                            var row = $(obj).parents('tr:first');

                            //delete the row
                            footable.removeRow(row);
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    layer.msg(res.msg);
                }
            },
            error: function (xhr, status) {
                if (status == 'timeout') {
                    layer.msg('服务器超时，请稍后重试');
                } else {
                    layer.msg('服务器错误，请稍后重试');
                }
            }
        });
    })
}



function removeAll(url) {
    var data = {
        id: [],
    };
    $('input[type=checkbox]').each(function () {
        if ($(this).is(":checked")) {
            if ($(this).val() != '') {
                data.id.push($(this).val())
            }
        }
    });

    if (data.id == '') {
        layer.msg('请勾选删除项');
        return false;
    }

    layer.confirm('确认删除吗？', function () {
        var load = layer.load(2);
        $.ajax({
            type: 'delete',
            url: url,
            titmeout: 60000,
            dataType: 'json',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            complete: function () {
                layer.close(load);
            },
            success: function (res) {
                if (res.code == 200) {
                    layer.msg(res.msg, {time: 1500}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(res.msg);
                }
            },
            error: function (xhr, status) {
                if (status == 'timeout') {
                    layer.msg('服务器超时，请稍后重试');
                } else {
                    layer.msg('服务器错误，请稍后重试');
                }
            }
        });
    })
}


/**
 * ajax 表单提交
 * @param data
 * @param url
 * @param type 提交方式
 */
function postFormData(data, url, type, closed) {
    var load = layer.load(2);
    type = type || 'post';
    closed = closed || false;
    $.ajax({
        type: type,
        url: url,
        titmeout: 60000,
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        complete: function () {
            layer.close(load);
        },
        success: function (res) {
            if (res.code == 200) {
                layer.msg(res.msg, {time: 1500}, function () {
                    if (closed) {
                        var index = parent.layer.getFrameIndex(window.name)
                        layer.close(index);
                        parent.window.location.reload()
                    } else {
                        window.location.reload()
                    }
                });
            } else {
                layer.msg(res.msg);
            }
        },
        error: function (xhr, status) {
            if (status == 'timeout') {
                layer.msg('服务器超时，请稍后重试');
            } else {
                layer.msg('服务器错误，请稍后重试');
            }
        }
    });

    return false;
}

function uploadFile(context, url, acceptTypes, nums, size)
{
    var nums = nums || 1;
    var acceptTypes = acceptTypes || /(gif|jpe?g|png)$/i;
    var size = size || 3*1024*1024
    var upload = $(context).fileupload({
        url : url,//请求发送的目标地址
        Type : 'POST',//请求方式 ，可以选择POST，PUT或者PATCH,默认POST
        dataType : 'json',//服务器返回的数据类型
        autoUpload : true,
        acceptFileTypes : acceptTypes,//验证图片格式
        maxNumberOfFiles : nums,//最大上传文件数目
        maxFileSize : size, // 文件上限1MB
        minFileSize : 100,//文件下限  100b
        messages : {//文件错误信息
            acceptFileTypes : '文件类型不匹配',
            maxFileSize : '文件过大',
            minFileSize : '文件过小'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    //图片添加完成后触发的事件
    upload.on("fileuploadadd", function(e, data) {
        $('button[type=submit]').attr('disabled', true);
    })
    //当一个单独的文件处理队列结束触发(验证文件格式和大小)
    upload.on("fileuploadprocessalways", function(e, data) {
        //获取文件
        file = data.files[0];
        //获取错误信息
        if (file.error) {
            $('button[type=submit]').attr('disabled', false);
            layer.msg(file.error);
        }
    })
    //显示上传进度条
    upload.on("fileuploadprogressall", function(e, data) {

    })
    return upload;
}

//序列化表单json对象
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [ o[this.name] ];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}

/**
 * 日期范围选择
 * @param beginSelector
 * @param endSelector
 * @constructor
 */
function dateRangePicker(beginSelector,endSelector, config) {
    if ('undefined' === typeof config) {
        config = {
            language: "zh-CN",
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
            orientation:'bottom',
            endDate:new Date()
        }
    }
    // 仅选择日期
    $(beginSelector).datepicker(config).on('changeDate', function(ev){
        if(ev.date){
            $(endSelector).datepicker('setStartDate', new Date(ev.date.valueOf()))
        }else{
            $(endSelector).datepicker('setStartDate',null);
        }
    })

    $(endSelector).datepicker(config).on('changeDate', function(ev){
        if(ev.date){
            $(beginSelector).datepicker('setEndDate', new Date(ev.date.valueOf()))
        }else{
            $(beginSelector).datepicker('setEndDate',new Date());
        }
    })
}

/**
 * 日期选择框
 * @param selector
 * @param config
 */
function initDatePicker(selector, config) {
    if ('undefined' === typeof config) {
        config = {
            language: "zh-CN",
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
            orientation:'bottom',
            endDate:new Date()
        }
    }

    $(selector).datepicker(config);
}

function expandAll() {
    $('.dd').nestable('expandAll');//展开所有节点
}
function collapseAll() {
    $('.dd').nestable('collapseAll');//展开所有节点
}

function parseParams(data) {
    try {
        var tempArr = [];
        for (var i in data) {
            var key = encodeURIComponent(i);
            var value = encodeURIComponent(data[i]);
            tempArr.push(key + '=' + value);
        }
        var urlParamsStr = tempArr.join('&');
        return urlParamsStr;
    } catch (err) {
        return '';
    }
}