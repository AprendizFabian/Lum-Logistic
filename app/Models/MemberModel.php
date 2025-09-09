<?php
namespace App\Models;
use App\Database;
use PDO;
use PDOException;

class MemberModel
{
    private $pdo;
    private $config;

    public function __construct()
    {
        $this->pdo = Database::getInstance();

        $this->config = [
            'user' => [
                'table' => 'users',
                'id_field' => 'id_user',
                'identifier_field' => 'email',
                'columns' => 'u.id_user AS id, u.username AS username, u.email AS email, u.is_active AS status, r.role_name AS rol, u.id_role, "user" AS type',
                'join_alias' => 'u',
            ],
            'store' => [
                'table' => 'stores',
                'id_field' => 'id_store',
                'identifier_field' => 'store_email',
                'columns' => 's.id_store AS id, s.store_name AS username, s.store_email AS email, s.store_address AS address, s.is_active AS status, r.role_name AS rol, c.city_name AS city, s.city_id, s.id_role, "store" AS type',
                'join_alias' => 's',
            ]
        ];
    }

    private function getConfig(string $type): array
    {
        if (!isset($this->config[$type])) {
            throw new \InvalidArgumentException("Invalid member type: $type");
        }
        return $this->config[$type];
    }

    public function getMembers(?string $type = null, ?string $search = null): array
    {
        $results = [];
        $types = $type ? [$type] : array_keys($this->config);

        foreach ($types as $t) {
            $c = $this->getConfig($t);

            $sql = "SELECT {$c['columns']}
                    FROM {$c['table']} {$c['join_alias']}
                    JOIN roles r ON {$c['join_alias']}.id_role = r.id_role";
            if ($t === 'store') {
                $sql .= " JOIN cities c ON {$c['join_alias']}.city_id = c.id_city";
            }

            $params = [];
            if (!empty($search)) {
                if ($t === 'user') {
                    $sql .= " WHERE ({$c['join_alias']}.username LIKE ? 
                               OR {$c['join_alias']}.email LIKE ?)";
                    $params = ["%{$search}%", "%{$search}%"];
                } elseif ($t === 'store') {
                    $sql .= " WHERE ({$c['join_alias']}.store_name LIKE ? 
                               OR {$c['join_alias']}.store_email LIKE ?)";
                    $params = ["%{$search}%", "%{$search}%"];
                }
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            $typeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($typeResults as $result) {
                $result['member_type'] = $t;
                $results[] = $result;
            }
        }

        return $results;
    }


    public function getMemberById($id): ?array
    {
        foreach ($this->config as $type => $c) {
            $sql = "SELECT t.*, r.role_name AS rol, :type AS type
                        FROM {$c['table']} t
                        JOIN roles r ON t.id_role = r.id_role
                        WHERE t.{$c['id_field']} = :id
                        LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id, ':type' => $type]);

            if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $result;
            }
        }

        return null;
    }

    public function addMember(array $data, string $type)
    {
        $c = $this->getConfig($type);

        if ($type === 'user') {
            $sql = "INSERT INTO {$c['table']} (username, email, password, id_role) 
                    VALUES (:username, :email, :password, :id_role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                ':id_role' => $data['id_role'],
            ]);
        } elseif ($type === 'store') {
            $sql = "INSERT INTO {$c['table']} (store_name, store_address, store_email, password, city_id, id_role)
                        VALUES (:store_name, :store_address, :store_email, :password, :city_id, :id_role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':store_name' => $data['store_name'],
                ':store_address' => $data['store_address'],
                ':store_city' => $data['store_city'],
                ':store_email' => $data['store_email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
                ':city_id' => $data['city_id'],
                ':id_role' => $data['id_role'],
            ]);
        }

        return $this->pdo->lastInsertId();
    }

    public function editMember(array $data, string $type)
    {
        if ($type === 'user') {
            $sql = "UPDATE users 
                    SET username = :username, email = :email, id_role = :id_role
                    WHERE id_user = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':id_role' => $data['id_role'],
                ':id' => $data['id_user'],
            ]);
        } elseif ($type === 'store') {
            $sql = "UPDATE stores 
                        SET store_name = :store_name, store_address = :store_address, store_email = :store_email, id_role = :id_role, city_id = :city_id
                        WHERE id_store = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':store_name' => $data['store_name'],
                ':store_address' => $data['store_address'],
                ':store_city' => $data['store_city'],
                ':store_email' => $data['store_email'],
                ':id_role' => $data['id_role'],
                ':city_id' => $data['city_id'],
                ':id' => $data['id_store'],
            ]);
        }
        return $stmt->rowCount();
    }


    public function toggleMemberStatus(int $id, string $type, int $status)
    {
        $c = $this->getConfig($type);

        $sql = "UPDATE {$c['table']} SET is_active = :status WHERE {$c['id_field']} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':status' => $status, ':id' => $id]);

        return $stmt->rowCount();
    }

    public function verifyAccount(string $identifier, string $password, string $type = 'user')
    {
        $c = $this->getConfig($type);

        $nameField = $type === 'user' ? 'username' : 'store_name';

        $sql = "SELECT 
                    {$c['id_field']} AS id, 
                    {$c['identifier_field']} AS email, 
                    {$nameField} AS username,
                    id_role, 
                    is_active AS status, 
                    password, 
                    '$type' AS type 
                FROM {$c['table']}
                WHERE {$c['identifier_field']} = :identifier
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':identifier' => $identifier]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account && password_verify($password, $account['password'])) {
            unset($account['password']);
            return $account;
        }
        return null;
    }


    public function updateLastLogin(int $id, string $type)
    {
        $c = $this->getConfig($type);

        $sql = "UPDATE {$c['table']} SET last_login = NOW() WHERE {$c['id_field']} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return true;
    }

    public function getCities()
    {
        $sql = "SELECT * FROM cities";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
