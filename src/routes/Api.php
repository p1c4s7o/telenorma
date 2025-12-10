<?php

namespace routes;

use App\Action\User;
use App\Boot;
use App\Route;
use App\View;

class Api extends Route
{
    private User $user;

    public function get_user(array $params, Boot $boot): void
    {
        $last_id = isset($params['vars'][1]) ? intval($params['vars'][1]) : 0;
        if ($last_id < 1)
            View::json(['status' => false, 'message' => 'Bad user id'], 422);

        View::json(['status' => true, 'from' => $last_id, 'result' => $this->user->get_user_by_id($last_id)]);
    }

    public function get_users(array $params, Boot $boot): void
    {
        $last_id = isset($params['vars'][1]) ? intval($params['vars'][1]) : 0;
        $result = ['status' => true, 'result' => $this->user->getAll($last_id)];

        if ($last_id > 0)
            $result['from'] = $last_id;

        if (($last = count($result['result'])) > 0 && $result['result'][$last - 1]['id'] > 1)
            $result['next'] = $result['result'][$last - 1]['id'];

        View::json($result);
    }

    public function create_user(): void
    {
        $new_user = self::json();
        $expected = ['first_name', 'last_name', 'role'];
        $types = [
            'first_name' => 'string',
            'last_name' => 'string',
            'role_id' => 'int'
        ];

        if (!$new_user) View::json([
            'status' => false,
            'message' => $expected[0] . ' is required'
        ], 422);

        foreach ($types as $item => $type) {
            if (!array_key_exists($item, $new_user))
                View::json([
                    'status' => false,
                    'message' => $item . ' is required'
                ], 422);

            $fn = 'is_' . $type;
            if (!$fn($new_user[$item]))
                View::json([
                    'status' => false,
                    'message' => $item . ' expected type ' . $type
                ], 422);
        }

        $result = $this->user->create(...$new_user);
        View::json($result);
    }

    public function update_user(array $params)
    {
        $new_user = self::json();
        $new_user['id'] = intval($params['vars'][1]);

        $expected = ['first_name', 'last_name', 'role'];
        $types = [
            'first_name' => 'string',
            'last_name' => 'string',
            'role_id' => 'int',
            'id' => 'int',
        ];

        if (!$new_user) View::json([
            'status' => false,
            'message' => $expected[0] . ' is required'
        ], 422);

        foreach ($types as $item => $type) {
            if (!array_key_exists($item, $new_user))
                View::json([
                    'status' => false,
                    'message' => $item . ' is required'
                ], 422);

            $fn = 'is_' . $type;
            if (!$fn($new_user[$item]))
                View::json([
                    'status' => false,
                    'message' => $item . ' expected type ' . $type
                ], 422);
        }

        $result = $this->user->update(...$new_user);
        View::json($result);
    }

    public function delete_user(array $params)
    {
        $id = $params['vars'][1];
        View::json($this->user->delete($id));
    }

    public function init_services(Boot $boot): void
    {
        $this->user = new User($boot->db());
    }

    public function after_register(Boot $boot): void
    {
        static::$_map['GET'] = [
            '/v1/user/([0-9]+)' => 'get_user',
            '/v1/users(?:/([0-9]+))?' => 'get_users',
        ];
        static::$_map['POST'] = [
            '/v1/user' => 'create_user'
        ];
        static::$_map['PATCH'] = [
            '/v1/user/([0-9]+)' => 'update_user'
        ];
        static::$_map['DELETE'] = [
            '/v1/user/([0-9]+)' => 'delete_user'
        ];
    }
}