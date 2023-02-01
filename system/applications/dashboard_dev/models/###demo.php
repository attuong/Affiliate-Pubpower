<?php

include 'MySQL.php';
$config = [
    'dbuser' => DB_USER,
    'dbpassword' => DB_PASSWORD,
    'dbname' => DB_NAME,
    'dbhost' => DB_HOST,
    'dbport' => DB_PORT,
    'encoding' => DB_ENCODING
];
$MySQL = MySQL::getInstance($config);


define('TABLE_PRODUCT', 'product');


/**
 * FIND
 */
$where = [
    'id' => 1, //=>  WHERE id = 1
    'id' => ['!=' => 1], //=>  WHERE id != 1
    'status' => 'ON', //=> WHERE status = ON
    'name' => ['$like' => '%tung%'], //=> WHERE name LIKE '%tung%'
    'category' => ['$in' => [1, 2, 3, 4, 5]], // WHERE IN
    'category' => ['$nin' => [1, 2, 3, 4, 5]], // WHERE NOT IN
    'number' => ['>' => 1], //=> Các giá trị: > , < , != , >= , <=
    //=> OR
    '$or' => [
        ['name' => 'tung'],
        ['name' => 'tu']
    ]
];
$field = array(
    'id', 'name', 'description' //=> Lấy ra các trường dữ liệu được khai báo trong mảng
);
$order = array(
    'id' => 'DESC' //=>Chọn 1 trong 3 giá trị: DESC|ASC|RAND()
);
$order = ['field' => "(id,1,5,2,6,7)"]; // sắp xếp theo thứ tự cho sẵn

$limit = 1; // Hoặc truyền vào giá trị string "0,10" để phân trang
// Lấy nhiều row
$rows = $MySQL->find(TABLE_PRODUCT, $where, $field, $order, $limit);
// Lấy 1 row
$row = $MySQL->findOne(TABLE_PRODUCT, $where, $field, $order, 1);

/**
 * COUNT
 */
$number = $MySQL->count(TABLE_PRODUCT, $where, $field = array('id'));


/**
 * DELETE
 */
$number = $MySQL->delete(TABLE_PRODUCT, $where);


/**
 * UPDATE
 */
$data = array(
    'name' => 'tung',
    'lastupdate' => time()
);
$MySQL->update(TABLE_PRODUCT, $where, $data);


/**
 * INSERT
 */
$data = array(
    'name' => 'tung',
    'description' => 'dep trai'
); //=> $data không nhất thiết phải điền đủ trường
$MySQL->insert(TABLE_PRODUCT, $data);

/**
 * Chạy câu lệnh đơn thuần.
 */
$result = $MySQL->get_row("SELECT * FROM " . TABLE_PRODUCT); //FIND ONE - lấy ra 1 row
$results = $MySQL->get_results("SELECT * FROM " . TABLE_PRODUCT); // FIND - lấy ra nhiều row
$number = $MySQL->get_var("SELECT COUNT(id) FROM " . TABLE_PRODUCT); // COUNT - ĐẾM
$update = $MySQL->query($query_update);
$insert = $MySQL->query($query_insert);


/**
 * DEBUG
 * Dưới mỗi câu lệnh gọi ra hàm debug
 */
$MySQL->find(TABLE_PRODUCT, $qr1);
$MySQL->debug(); //=> Sẽ in ra debug của câu lệnh với query 1

$MySQL->find(TABLE_PRODUCT, $qr2);
$MySQL->debug(); //=> Sẽ in ra debug của câu lệnh vs query 2.

