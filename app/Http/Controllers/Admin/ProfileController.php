<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //
    public function index(Request $request)
    {
        $user_auth = session('user_auth');
        if ($request->isMethod('post')) {
            $rule = [
                'name' => 'required',
                'password' => 'nullable|regex:/^\w{6,18}$/',
                'phone' => [
                    'nullable',
                    'regex:/^1[34578]\d{9}/',
                ],
                'telephone' => [
                    'nullable',
                    'regex:/(^(\d{3,4}-)?\d{7,8})$|(13[0-9]{9})/'
                ]
            ];

            $msg = [
                'name.required' => '姓名不能为空',
                'password.regex' => '密码必须是字母或数字或特殊字符组成，6-18位',
                'phone.regex' => '手机号必须是合法的11位数字',
                'telephone.regex' => '固话格式不正确',
            ];
            $data = $request->all();
            $valid = Validator::make($data, $rule, $msg);
            if ($valid->fails()) {
                exit(responseToJson([], $valid->errors()->first(), 201));
            }

            if ($data['password'] != '') {
                $data['password'] = md5($data['password']);
            } else {
                unset($data['password']);
            }

            try {
                UserModel::where('code', $user_auth['code'])->update($data);
            } catch (\Exception $e) {
                return responseToJson([], '提交失败：【' . $e->getMessage() . '】', 201);
            }

            return responseToJson([], '提交成功');
        }
        $userinfo = UserModel::findOrFail($user_auth['code']);
        return view('admin.user.profile', [
            'userinfo' => $userinfo,
        ]);
    }
}
