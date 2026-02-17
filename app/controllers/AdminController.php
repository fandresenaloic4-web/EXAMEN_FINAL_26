<?php

namespace app\controllers;

use Flight;

class AdminController {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Réinitialiser la base de données à l'état de `database2.sql`.
     */
    public function resetData() {
        $file = __DIR__ . '/../../database2.sql';

        if (!file_exists($file)) {
            Flight::redirect('/dashboard?error=sql_missing');
            return;
        }

        $sql = file_get_contents($file);
        // Normaliser les fins de ligne
        $sql = str_replace("\r", "", $sql);

        // Séparer les statements SQL en se basant sur le point-virgule suivi d'une nouvelle ligne
        $parts = preg_split('/;\n/', $sql);

        foreach ($parts as $part) {
            $stmt = trim($part);
            if ($stmt === '') continue;

            // Ignorer les commentaires de type -- ... et les instructions CREATE DATABASE / USE
            $lines = array_filter(array_map('trim', explode("\n", $stmt)));
            if (empty($lines)) continue;
            $first = $lines[0];
            if (strpos($first, '--') === 0) continue;
            if (preg_match('/^CREATE\s+DATABASE/i', $first)) continue;
            if (preg_match('/^USE\b/i', $first)) continue;

            try {
                if (method_exists($this->db, 'exec')) {
                    $this->db->exec($stmt);
                } else {
                    $sth = $this->db->prepare($stmt);
                    $sth->execute();
                }
            } catch (\Throwable $e) {
                // Ignorer les erreurs individuelles pour continuer la réinitialisation
                // Vous pouvez logguer $e->getMessage() si besoin
                continue;
            }
        }

        Flight::redirect('/dashboard');
    }
}
