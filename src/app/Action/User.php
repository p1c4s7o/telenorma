<?php

namespace App\Action;

use App\Db;

class User
{
    public int $limit = 20;

    public function __construct(private readonly Db $db)
    {
    }

    /**
     * @param int $id
     * @return array
     */
    public function get_user_by_id(int $id): array
    {
        $stmt = $this->db->prepare("SELECT u.id, u.first_name, u.last_name, u.created_at, u.updated_at,
        JSON_OBJECT('role_name', r.role_name, 'role_id', r.id) AS role
        FROM users u
        LEFT JOIN user_roles r ON u.role_id = r.id
        WHERE u.id = :id
        ORDER BY u.id DESC
        LIMIT 1");

        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && isset($row['role']))
            $row['role'] = json_decode($row['role'], true);

        return $row;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getAll(int $id = 0): array
    {
        $where = '';

        if ($id < 1)
            $id = 0;

        if ($id > 0)
            $where = 'WHERE u.id < :last_id';

        $stmt = $this->db->prepare("SELECT u.id, u.first_name, u.last_name, u.created_at, u.updated_at,
        JSON_OBJECT('role_name', r.role_name, 'role_id', r.id) AS role
        FROM users u
        LEFT JOIN user_roles r ON u.role_id = r.id
        $where
        ORDER BY u.id DESC
        LIMIT $this->limit");

        if ($id > 0)
            $stmt->bindParam(':last_id', $id, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        return array_map(function ($row) {
            if ($row && isset($row['role']))
                $row['role'] = json_decode($row['role'], true);
            return $row;
        }, $result);
    }

    /**
     * @param string $first_name
     * @param string $last_name
     * @param int $role_id
     * @return array
     * @throws \Exception
     */
    public function create(string $first_name, string $last_name, int $role_id): array
    {
        $result = [
            'status' => true
        ];

        try {
            $this->db->begin();
            $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, role_id) VALUES (:first, :last, :role_id)");
            $stmt->execute([
                'first' => $first_name,
                'last' => $last_name,
                'role_id' => $role_id,
            ]);

            $last_id = $this->db->lastInsert();
            if (is_string($last_id) && ($id = intval($last_id)) > 0)
                $result['result'] = $this->get_user_by_id($id);

            $this->db->commit();
        } catch (\Exception $e) {
            if ($this->db->inTx())
                $this->db->rollBack();
            throw $e;
        }

        return $result;
    }

    public function update(string $first_name, string $last_name, int $role_id, int $id): array
    {
        $result = [
            'status' => true
        ];

        try {
            $this->db->begin();
            $stmt = $this->db->prepare("UPDATE users SET first_name = :first, last_name = :last, role_id = :role_id WHERE id = :id");
            $stmt->execute([
                'first' => $first_name,
                'last' => $last_name,
                'role_id' => $role_id,
                'id' => $id
            ]);

            $last_id = $this->db->lastInsert();
            if (is_string($last_id) && ($id = intval($last_id)) > 0)
                $result['result'] = $this->get_user_by_id($id);

            $this->db->commit();
        } catch (\Exception $e) {
            if ($this->db->inTx())
                $this->db->rollBack();
            throw $e;
        }

        return $result;
    }

    public function delete(int $id): array
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return [
            'status' => $stmt->execute(['id' => $id])
        ];
    }
}