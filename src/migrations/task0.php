<?php

$arr = [];
$arr[] = "create table if not exists user_roles
(
    id        bigint auto_increment primary key,
    role_name varchar(50) not null unique
)";

$arr[] = "create table if not exists users
(
    id        bigint auto_increment primary key,
    role_id   bigint       not null,
    first_name varchar(255) not null default 'unnamed',
    last_name varchar(255) not null default 'unnamed',
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    constraint fk_user_role
        foreign key (role_id)
            references user_roles (id)
            on delete restrict
            on update cascade
)";

return $arr;