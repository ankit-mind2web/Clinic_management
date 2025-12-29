<?php

namespace App\Models\Admin;

use App\Core\Model;
use PDO;

class AppointmentModel extends Model
{
    // Base FROM + JOIN clause
    private function baseFrom(): string
    {
        return "
            FROM appointments a
            JOIN users p ON p.id = a.patient_id
            JOIN users d ON d.id = a.doctor_id
        ";
    }

    // Build WHERE clause for search & filters
    private function buildWhere(
        string $search = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = ''
    ): array {
        $where  = "WHERE 1=1";
        $params = [];

        // Global search
        if ($search !== '') {
            $where .= " AND (
                p.full_name LIKE :search
                OR d.full_name LIKE :search
                OR a.start_utc LIKE :search
                OR a.status LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }

        // Status filter
        if ($status !== '') {
            $where .= " AND a.status = :status";
            $params[':status'] = $status;
        }

        // From date filter
        if ($fromDate !== '') {
            $where .= " AND a.start_utc >= :fromDate";
            $params[':fromDate'] = "{$fromDate} 00:00:00";
        }

        // To date filter
        if ($toDate !== '') {
            $where .= " AND a.start_utc <= :toDate";
            $params[':toDate'] = "{$toDate} 23:59:59";
        }

        return [$where, $params];
    }

    // Count appointments with filters
    public function countFiltered(
        string $search = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = ''
    ): int {
        [$where, $params] = $this->buildWhere($search, $status, $fromDate, $toDate);

        $sql = "SELECT COUNT(*) " . $this->baseFrom() . " " . $where;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    // Get paginated appointments with filters
    public function getFiltered(
        string $search = '',
        string $sort = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = '',
        int $page = 1,
        int $perPage = 10
    ): array {
        [$where, $params] = $this->buildWhere($search, $status, $fromDate, $toDate);

        // Sorting logic
        $orderBy = "ORDER BY a.start_utc DESC";
        if ($sort === 'name_asc') {
            $orderBy = "ORDER BY p.full_name ASC";
        } elseif ($sort === 'name_desc') {
            $orderBy = "ORDER BY p.full_name DESC";
        } elseif ($sort === 'date_asc') {
            $orderBy = "ORDER BY a.start_utc ASC";
        } elseif ($sort === 'date_desc') {
            $orderBy = "ORDER BY a.start_utc DESC";
        }

        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
            " . $this->baseFrom() . "
            $where
            $orderBy
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all filtered appointments (export use)
    public function getAllFiltered(
        string $search = '',
        string $sort = '',
        string $status = '',
        string $fromDate = '',
        string $toDate = ''
    ): array {
        [$where, $params] = $this->buildWhere($search, $status, $fromDate, $toDate);

        $orderBy = "ORDER BY a.start_utc DESC";
        if ($sort === 'name_asc') {
            $orderBy = "ORDER BY p.full_name ASC";
        } elseif ($sort === 'name_desc') {
            $orderBy = "ORDER BY p.full_name DESC";
        }

        $sql = "
            SELECT
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
            " . $this->baseFrom() . "
            $where
            $orderBy
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Backward compatible method for old controllers
    public function getAll(): array
    {
        return $this->getFiltered('', '', '', '', '', 1, 1000);
    }

    // Count all appointments (dashboard)
    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    }

    // Get recent appointments (dashboard)
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
             FROM appointments a
             JOIN users p ON p.id = a.patient_id
             JOIN users d ON d.id = a.doctor_id
             ORDER BY a.start_utc DESC
             LIMIT ?"
        );

        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get appointment by ID
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.*,
                p.full_name AS patient_name,
                p.email AS patient_email,
                d.full_name AS doctor_name,
                d.email AS doctor_email
             FROM appointments a
             JOIN users p ON p.id = a.patient_id
             JOIN users d ON d.id = a.doctor_id
             WHERE a.id = ?
             LIMIT 1"
        );

        $stmt->execute([$id]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        return $appointment ?: null;
    }
}
