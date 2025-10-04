<?php
class Employee {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM employees ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO employees (first_name,last_name,email,phone,salary) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['first'], $data['last'], $data['email'], $data['phone'], $data['salary']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE employees SET first_name=?, last_name=?, email=?, phone=?, salary=? WHERE id=?");
        return $stmt->execute([$data['first'], $data['last'], $data['email'], $data['phone'], $data['salary'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id=?");
        return $stmt->execute([$id]);
    }
}
