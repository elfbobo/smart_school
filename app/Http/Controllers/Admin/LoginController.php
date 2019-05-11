<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\UserModel;
use App\Models\Admin\UserRoleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //登录
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $username = $request->post('username', null);
            $password = $request->post('password', null);

            if (!$username) {
                return $this->responseJson(201, '账号不能为空');
            }

            if (!$password) {
                return $this->responseJson(201, '密码不能为空');
            }

            $user = UserModel::where('code', $username)->first();
            if (!$user) {
                return $this->responseJson(201, '账号不存在');
            }

            if (md5($password) !== $user->password) {
                return $this->responseJson(201, '密码错误');
            }

            //获取用户角色
            if ($user->code != config('custom.super_manager')) {
                $roles = UserRoleModel::where('user_id', $user->id)->pluck('role_id')->toArray();
                $roles = array_unique($roles);
                if (!$roles) {
                    return $this->responseJson(201, '抱歉，您无权限登录');
                }
            }

            $user_auth = [
                //'id' => $user->id,
                'code' => $user->code,
                'username' => $user->name,
                //'avatar' => $user->avatar,
                'roles' => isset($roles) ? $roles : [],
            ];

            session()->put('user_auth', $user_auth);
            session()->save();
            return $this->responseJson(200, '登录成功', ['target_url' => '/']);
        }
        return view('admin.login');
    }

    //登出
    public function logout()
    {
        session()->flush();
        session()->save();
        return redirect()->route('admin.login');
    }

    //返回json提示信息
    private function responseJson($code = 200, $msg = 'ok', $data = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
