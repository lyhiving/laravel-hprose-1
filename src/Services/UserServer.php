<?php


namespace whereof\hprose\Services;


/**
 * Class UserServer
 * @package whereof\hprose\Services
 */
class UserServer
{
    /**
     * @return string
     */
    public function index()
    {
        return '用户列表';
    }


    /**
     * @return string
     */
    public function create()
    {
        return '创建用户';
    }

    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        return '查看用户信息:' . $id;
    }

    /**
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        return '编辑用户信息:' . $id;
    }


    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        return '删除用户信息:' . $id;
    }

}