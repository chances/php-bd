drop table if exists repository_types cascade;
drop table if exists projects;

create table repository_types (
  id serial primary key,
  name varchar(255) not null
);

create table projects (
  id serial primary key,
  name varchar(255) not null,
  expires date not null,
  description text not null,
  repository_type smallint references repository_types(id)
);

insert into repository_types (name) values
  ( 'git' ),
  ( 'subversion' );

insert into projects (name, expires, description, repository_type) values
  ('taco-copter', '2016-03-01', 'A quadcopter that delivers tacos', 2),
  ('dreft', '2015-12-31', 'Yet another new javascript build tool', 1);
