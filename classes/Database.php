<?php

namespace Crm2Esp;

class Database {
    public $db;

    public function __construct($server, $dbuser, $password, $database)
    {
        try {
            $this->db = new \mysqli($server, $dbuser, $password, $database);
        } catch (Exception $e) {

        }

        // Check if tables exist. If not, create.
        $sql = "CREATE TABLE IF NOT EXISTS subscribers (
            `id` int NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `account` VARCHAR(512),
            `list_source` VARCHAR(512),
            `list_source_name` VARCHAR(512),
            `list_target` VARCHAR(512),
            `list_target_name` VARCHAR(512),
            `email` VARCHAR(512),
            `name` VARCHAR(512),
            `imported` VARCHAR(16),
            `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated` TIMESTAMP,
            `ip` int NOT NULL
        )";

        try {
            $result = $this->db->query($sql);
        } catch (Exception $e) {

        }

        // $sql = "CREATE TABLE IF NOT EXISTS log (
        //
        // )";
    }
}
