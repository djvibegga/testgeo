###in psql command line:

create database demo encoding = 'UTF-8';

create user demo with password 'demo';

\c demo

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO demo;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO demo;

create extension postgis;
create table "cadastr" ("number" char(22) UNIQUE, border geometry);
