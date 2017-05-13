# basic-sql
Basic Sql Class

# define class
new sql(file_path);

# file_path
define from document root
#
default path : /service/connect.php
#
connect.php includes $host, $user, $pass and $db variables

# constructor
Connects to db. If fail print "Couldn't connect to db" and exit

# s
s($columns, $table) => prepares select statement "SELECT $columns FROM $table"

# u
u($table, $set) => prepares update statement "UPDATE $table SET $set"

# d
d($table) => prepares delete statement "DELETE FROM $table" | use with w() or it will truncate table

# i
i($table, $columns, $values) => prepares insert statement "INSERT INTO $table($columns) values($values)"

# w
w($where) => adds where clause to query " WHERE $where"

# o
o($order) => adds order by clause to query " ORDER BY $order"

# l
l($limit) => adds limit clause to query " LIMIT $limit"

# lk
lk($like) => adds like clause to query " LIKE $like"

# b
b($b1, $b2) => adds between clause to query " BETWEEN $b1 AND $b2"

# g
g($group) => adds group by clause to query " GROUP BY $group"

# f
f($query) => you can write all query without s(), w() etc. Ex. f("SELECT * FROM table")

# set_names
set_names($name) => sets names "SET NAMES $name"

# set_charset
set_charset($charset) => sets charset "SET CHARACTER SET $charset"

# r
r() => executes prepared query.
#
if use with true " r(true) " returns number of rows
#
if execute select statement returns an array

    array(
        [0] => array(
            [post_id] = 4567
            [post_title] = Test title
        )

        [1] => array(
            [post_id] = 4568
            [post_title] = Test title 2
        )
    )

other statements it returns an array
    
    array(
        [insert_id] => int/undefined -> if it is insert query you can get insert id
        [status] => OK/ERROR -> you can check if the query executed or not
        [affected_rows] => int/undefined -> if it is update or delete you can get number of affected rows
    )

# examples
$query1 = $sql->S("*", "posts")->R(); -> returns all data from posts table
#
$query2 = $sql->S("*", "posts")->R(true); -> returns number of rows
#
$query3 = $sql->S("*", "posts")->W("post_id = '256'")->R(); -> returns post with id 256
#
$query4 = $sql->S("*", "posts")->W("post_id > 400")->O("post_id DESC")->R(); -> returns posts which have id gt 400 and orders
#
$query5 = $sql->S("*", "posts")->W("post_title")->LK("'%$search%'")->R();->R(); -> search query
#
$query6 = $sql->U("posts", "post_title = 'New title'")->W("post_id = '356'")->R(); -> updates post title with id 356
#
$query7 = $sql->D("posts")->W("post_id = '745'")->R(); -> deletes post with id 745
#
$query8 = $sql->I("posts", "post_title, post_content", "'Insert new post', 'Content of new post'")->R(); -> inserts new post
#
$query9 = $sql->F("SELECT * FROM table 1 AS T1 INNER JOIN table2 AS T2 ON T1.id = T2.id")->R(); -> execute the query
