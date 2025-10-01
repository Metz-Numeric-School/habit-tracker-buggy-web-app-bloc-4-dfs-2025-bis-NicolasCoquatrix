<?php
namespace App\Repository;

use App\Entity\User;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function findAll()
    {
        $users = $this->getConnection()->query("SELECT * FROM mns_user");
        return EntityMapper::mapCollection(User::class, $users->fetchAll());
    }

    public function find(int $id)
    {
        // Correction : Requête préparée pour éviter les injections SQL
        $sql = "SELECT * FROM mns_user WHERE id = :id";
        $query = $this->getConnection()->prepare($sql);
        $query->execute(['id' => $id]);
        $data = $query->fetch();
        return $data ? EntityMapper::map(User::class, $data) : null;
    }

    public function findByEmail(string $email)
    {
        // Correction : Requête préparée pour éviter les injections SQL
        $sql = "SELECT * FROM mns_user WHERE email = :email";
        $query = $this->getConnection()->prepare($sql);
        $query->execute(['email' => $email]);
        $data = $query->fetch();
        return $data ? EntityMapper::map(User::class, $data): null;
    }

    public function insert(array $data = array())
    {
        $sql = "INSERT INTO mns_user (lastname, firstname, email, password, isadmin) VALUES (:lastname, :firstname, :email, :password, :isadmin)";
        $query = $this->getConnection()->prepare($sql);
        $query->execute($data);
        return $this->getConnection()->lastInsertId();
    }
}