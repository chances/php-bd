drop table if exists redmine_repository_types cascade;
drop table if exists redmine_projects;

create table redmine_repository_types (
  id serial primary key,
  name varchar(255) not null
);

create table redmine_projects (
  id serial primary key,
  name varchar(255) not null,
  expires date not null,
  description text not null,
  owner_email varchar(255) not null,
  repository_type smallint references redmine_repository_types(id)
);

insert into redmine_repository_types (name) values
  ( 'git' ),
  ( 'subversion' );

insert into redmine_projects (name, expires, description, owner_email, repository_type) values
  ('taco-copter', '2016-03-01', 'A quadcopter that delivers tacos', 'cawil@cat.pdx.edu', 2),
  ('dreft', '2015-12-31', 'Yet another new javascript build tool', 'willic@pdx.edu', 1);
