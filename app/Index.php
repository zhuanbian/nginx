<?php

class Index
{
    function __construct()
    {
        $servername = getenv('database_host', '192.168.5.73');
        $username   = getenv('database_username', 'fleet');
        $password   = getenv('database_password', '123456');
        $dbname     = getenv('database_dbname', 'nginx');
        $this->db = new Nette\Database\Connection("mysql:host={$servername};port=3306;dbname=nginx", $username, $password);
    }



    /**
     * [ 列表 ]
     * @author [panjia]
     */
    public function items($paIn = null)
    {
        $sql = "
            SELECT
                 `id`
                ,`server_name`
                ,`ip`
                ,`listen`
                ,`user`
                ,`created_at`
                ,`updated_at`
            FROM
                proxy
        ";
        $ret = $this->db->query($sql)->fetchAll();
        $ret = $ret ?: [];
        success($ret);
    }



    /**
     * [ 详情 ]
     * @author [panjia]
     */
    public function item($paIn = null)
    {
        if (empty($paIn['id'])) {
            error('参数不全 !');
        }
        $sql = "
            SELECT
                `id`
                ,`server_name`
                ,`ip`
                ,`listen`
                ,`user`
                ,`created_at`
                ,`updated_at`
            FROM
                proxy
            WHERE
                id = ?
        ";
        $id = $paIn['id'];
        $ret = $this->db->query($sql, $id)->fetch();
        $ret = $ret ?: [];
        success($ret);
    }



    /**
     * [ 添加 ]
     * @author [panjia]
     */
    public function add($paIn = null)
    {
        if (empty($paIn)) {
            error('参数不全 !');
        }
        $data = [
            'server_name' => $paIn['server_name'],
            'ip'          => $paIn['ip'],
            'listen'      => $paIn['listen'],
            'user'        => $paIn['user'],
            'created_at'  => time(),
            'updated_at'  => time(),
        ];
        $ret = $this->db->query('INSERT INTO proxy', $data);
        if (!$ret) {
            error('添加失败 !');
        }
        // 生成文件
        $this->gen($paIn);
    }



    /**
     * [ 修改 ]
     * @author [panjia]
     */
    public function edit($paIn = null)
    {
        if (empty($paIn)) {
            error('参数不全 !');
        }
        $data = [
            'server_name' => $paIn['server_name'],
            'ip'          => $paIn['ip'],
            'listen'      => $paIn['listen'],
            'user'        => $paIn['user'],
            'updated_at'  => time(),
        ];
        $id = $paIn['id'];
        $ret = $this->db->query('UPDATE proxy SET', $data, 'WHERE id = ?', $id);
        if (!$ret) {
            error('修改失败 !');
        }
        // 生成文件
        $this->gen($paIn);
    }



    /**
     * [ 删除 ]
     * @author [panjia]
     */
    public function del($paIn = null)
    {
        if (empty($paIn['id'])) {
            error('参数不全 !');
        }
        $sql = "
            DELETE FROM
                proxy
            WHERE
                id = ?
        ";
        $id = $paIn['id'];
        $ret = $this->db->query($sql, $id);
        success();
    }



    /**
     * [ 重启 ]
     * @author [panjia]
     */
    public function reboot($paIn = null)
    {
        $cmd = "sudo /usr/local/nginx/sbin/nginx -s reload";
        exec($cmd);
        success();
    }



    /**
     * [ 生成 ]
     * @author [panjia]
     */
    public function gen($paIn = null)
    {
        if (empty($paIn['server_name'])||empty($paIn['server_name'])||empty($paIn['server_name'])) {
            error('参数不全! 生成失败 !');
        }
        $server_name = $paIn['server_name'];
        $ip          = $paIn['ip'];
        $listen      = $paIn['listen'];

        $data = "
            upstream my_server {
                server {$ip}:8080;
                keepalive 2000;
            }

            server {
                listen       {$listen};
                server_name  {$server_name};
                client_max_body_size 1024M;

                location /my/ {
                    proxy_pass http://my_server/;
                    proxy_set_header Host $host:$server_port;
                }
            }
        ";
        $fileName = $server_name.'.conf';
        file_put_contents(BASE_PATH.'/tmp/'.$fileName, $data);
        clearstatcache();
        success();
    }

}
