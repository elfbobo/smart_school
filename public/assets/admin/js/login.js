
function login(data, url) {
    var load = layer.load(2);
    $.ajax({
        type: 'post',
        url: url,
        timeout: 60000,
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
                layer.msg(res.msg, {time: 1000}, function () {
                    window.location.href=res.data.target_url;
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