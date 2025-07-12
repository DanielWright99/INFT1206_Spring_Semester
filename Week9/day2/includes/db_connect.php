<?php

function get_db_connection() {

    $conn = pg_connect("host=host.docker.internal port 5432 dbname=portfolio user=admin password=password");
    if(!$conn) {
        die("Database connection failed: " . pg_last_error() . pg_last_error());
    }
    return $conn;
}
