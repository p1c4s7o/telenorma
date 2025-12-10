<?php

$dir = rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

require_once $dir . '../src/helpers/funcs.php';
function build_dsn(string $driver, string $host, string $dbname, int $port = 3306): string
{
    return "$driver:host=$host:$port;dbname=$dbname";
}

$config = require_once  $dir . 'config.php';

$pdo = new PDO(build_dsn($config['driver'], $config['host'], $config['db_name'], $config['port']),
    $config['user'], $config['password'], $config['attributes']);

// TODO may be
//$stmt = $pdo->prepare("SELECT
//                    g.name AS product_name,
//                    MAX(CASE WHEN af.name = 'Color' THEN af.name END) AS additional_field_name_1,
//                    MAX(CASE WHEN af.name = 'Color' THEN afv.name END) AS additional_field_value_1,
//                    MAX(CASE WHEN af.name = 'Size' THEN af.name END) AS additional_field_name_2,
//                    MAX(CASE WHEN af.name = 'Size' THEN afv.name END) AS additional_field_value_2
//                FROM
//                    goods g
//                        LEFT JOIN additional_goods_field_values agfv ON g.id = agfv.good_id
//                        LEFT JOIN additional_fields af ON agfv.additional_field_id = af.id AND af.is_deleted = 0
//                        LEFT JOIN additional_field_values afv ON agfv.additional_field_value_id = afv.id AND afv.is_deleted = 0
//                GROUP BY
//                    g.id, g.name
//                ORDER BY
//                    g.id");
//$stmt = $pdo->prepare("select g.name as product_name,

$stmt = $pdo->prepare("SELECT
             g.name AS product_name,
             MAX(CASE WHEN af.name = 'Color' THEN af.name END) AS additional_field_name_1,
             MAX(CASE WHEN af.name = 'Color' THEN afv.name END) AS additional_field_value_1,
             MAX(CASE WHEN af.name = 'Size' THEN af.name END) AS additional_field_name_2,
             MAX(CASE WHEN af.name = 'Size' THEN afv.name END) AS additional_field_value_2
         FROM
             goods g
                 INNER JOIN
             additional_goods_field_values agfv ON g.id = agfv.good_id
                 INNER JOIN
             additional_fields af ON agfv.additional_field_id = af.id
                 INNER JOIN
             additional_field_values afv ON agfv.additional_field_value_id = afv.id
         WHERE
             af.is_deleted = 0 AND afv.is_deleted = 0
         GROUP BY
             g.id, g.name
         ORDER BY
             g.id");

$stmt->execute();
header('Content-type: application/json; charset=utf-8');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
exit;