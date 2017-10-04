/*
 Navicat Premium Data Transfer

 Source Server         : PostgreSQL
 Source Server Type    : PostgreSQL
 Source Server Version : 90405
 Source Host           : localhost
 Source Database       : Parsclick
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 90405
 File Encoding         : utf-8

 Date: 11/14/2015 16:38:06 PM
*/

				-- ----------------------------
				--  Table structure for authors
				-- ----------------------------
DROP TABLE IF EXISTS "public"."authors" ;
CREATE TABLE "public" . "authors" ("id" int4 NOT NULL,
"username" varchar (30) NOT NULL COLLATE "default",
"password" varchar (60) NOT NULL COLLATE "default",
"first_name" varchar (100) NOT NULL COLLATE "default",
"last_name" varchar (255) COLLATE "default",
"email" varchar (100) COLLATE "default",
"status" int2 NOT NULL,
"photo" bytea,
"token" varchar (255) COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."authors" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for articles
				-- ----------------------------
DROP TABLE IF EXISTS "public"."articles" ;
CREATE TABLE "public" . "articles" ("id" int4 NOT NULL,
"subject_id" int4 NOT NULL,
"author_id" int4,
"name" varchar (255) NOT NULL COLLATE "default",
"position" int4 NOT NULL,
"visible" int2 NOT NULL,
"content" text COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."articles" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for categories
				-- ----------------------------
DROP TABLE IF EXISTS "public"."categories" ;
CREATE TABLE "public" . "categories" ("id" int4 NOT NULL,
"name" varchar (255) NOT NULL COLLATE "default",
"position" int4 NOT NULL,
"visible" int2 NOT NULL)
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."categories" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for comments
				-- ----------------------------
DROP TABLE IF EXISTS "public"."comments" ;
CREATE TABLE "public" . "comments" ("id" int4 NOT NULL,
"member_id" int4 NOT NULL,
"course_id" int4 NOT NULL,
"created" timestamp (6) NOT NULL,
"body" text COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."comments" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for subjects
				-- ----------------------------
DROP TABLE IF EXISTS "public"."subjects" ;
CREATE TABLE "public" . "subjects" ("id" int4 NOT NULL,
"name" varchar (255) NOT NULL COLLATE "default",
"position" int4 NOT NULL,
"visible" int2 NOT NULL)
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."subjects" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for super_admin
				-- ----------------------------
DROP TABLE IF EXISTS "public"."super_admin" ;
CREATE TABLE "public" . "super_admin" ("id" int4 NOT NULL,
"username" varchar (30) NOT NULL COLLATE "default",
"password" varchar (60) NOT NULL COLLATE "default",
"first_name" varchar (60) NOT NULL COLLATE "default",
"last_name" varchar (255) COLLATE "default",
"email" varchar (255) NOT NULL COLLATE "default",
"token" varchar (255) COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."super_admin" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for members
				-- ----------------------------
DROP TABLE IF EXISTS "public"."members" ;
CREATE TABLE "public" . "members" ("id" int4 NOT NULL,
"username" varchar (50) NOT NULL COLLATE "default",
"hashed_password" varchar (60) NOT NULL COLLATE "default",
"email" varchar (50) NOT NULL COLLATE "default",
"first_name" varchar (100) COLLATE "default",
"last_name" varchar (50) COLLATE "default",
"gender" varchar (20) COLLATE "default",
"address" varchar (255) COLLATE "default",
"city" varchar (20) COLLATE "default",
"post_code" varchar (20) COLLATE "default",
"phone" varchar (20) COLLATE "default",
"photo" bytea,
"status" int2 NOT NULL,
"token" varchar (255) COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."members" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for courses
				-- ----------------------------
DROP TABLE IF EXISTS "public"."courses" ;
CREATE TABLE "public" . "courses" ("id" int4 NOT NULL,
"category_id" int4 NOT NULL,
"author_id" int4,
"name" varchar (255) NOT NULL COLLATE "default",
"youtubePlaylist" varchar (255) COLLATE "default",
"file_link" varchar (255) COLLATE "default",
"position" int4 NOT NULL,
"visible" int2 NOT NULL,
"content" text COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."courses" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for files
				-- ----------------------------
DROP TABLE IF EXISTS "public"."files" ;
CREATE TABLE "public" . "files" ("id" int4 NOT NULL,
"course_id" int4 NOT NULL,
"name" varchar (255) NOT NULL COLLATE "default",
"type" varchar (255) NOT NULL COLLATE "default",
"size" int4 NOT NULL,
"description" varchar (255) COLLATE "default")
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."files" OWNER TO "hassan" ;

				-- ----------------------------
				--  Table structure for playlist
				-- ----------------------------
DROP TABLE IF EXISTS "public"."playlist" ;
CREATE TABLE "public" . "playlist" ("id" int4 NOT NULL,
"member_id" int4 NOT NULL,
"course_id" int4 NOT NULL)
WITH (OIDS = FALSE) ;
ALTER TABLE "public"."playlist" OWNER TO "hassan" ;

				-- ----------------------------
				--  Primary key structure for table authors
				-- ----------------------------
ALTER TABLE "public"."authors" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Indexes structure for table authors
				-- ----------------------------
CREATE UNIQUE INDEX "authors_id_key" ON "public" . "authors" USING btree ("id" ASC NULLS LAST) ;

				-- ----------------------------
				--  Primary key structure for table articles
				-- ----------------------------
ALTER TABLE "public"."articles" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Indexes structure for table articles
				-- ----------------------------
CREATE INDEX "author_id" ON "public" . "articles" USING btree (author_id ASC NULLS LAST) ;
CREATE INDEX "subject_id" ON "public" . "articles" USING btree (subject_id ASC NULLS LAST) ;

				-- ----------------------------
				--  Primary key structure for table categories
				-- ----------------------------
ALTER TABLE "public"."categories" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Indexes structure for table categories
				-- ----------------------------
CREATE UNIQUE INDEX "categories_id_key" ON "public" . "categories" USING btree ("id" ASC NULLS LAST) ;

				-- ----------------------------
				--  Primary key structure for table comments
				-- ----------------------------
ALTER TABLE "public"."comments" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Indexes structure for table comments
				-- ----------------------------
CREATE INDEX "course_id" ON "public" . "comments" USING btree (course_id ASC NULLS LAST) ;
CREATE INDEX "member_id" ON "public" . "comments" USING btree (member_id ASC NULLS LAST) ;

				-- ----------------------------
				--  Indexes structure for table subjects
				-- ----------------------------
CREATE UNIQUE INDEX "subjects_id_key" ON "public" . "subjects" USING btree ("id" ASC NULLS LAST) ;

				-- ----------------------------
				--  Indexes structure for table members
				-- ----------------------------
CREATE UNIQUE INDEX "members_id_key" ON "public" . "members" USING btree ("id" ASC NULLS LAST) ;

				-- ----------------------------
				--  Primary key structure for table courses
				-- ----------------------------
ALTER TABLE "public"."courses" ADD PRIMARY KEY ("id") NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Indexes structure for table courses
				-- ----------------------------
CREATE UNIQUE INDEX "courses_id_key" ON "public" . "courses" USING btree ("id" ASC NULLS LAST) ;

				-- ----------------------------
				--  Foreign keys structure for table articles
				-- ----------------------------
ALTER TABLE "public"."articles" ADD CONSTRAINT "articles_author_id_fkey" FOREIGN KEY ("author_id") REFERENCES "public"."authors" ("id") ON
UPDATE CASCADE ON
DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "public"."articles" ADD CONSTRAINT "articles_subject_id_fkey" FOREIGN KEY ("subject_id") REFERENCES "public"."subjects" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Foreign keys structure for table comments
				-- ----------------------------
ALTER TABLE "public"."comments" ADD CONSTRAINT "comments_course_id_fkey" FOREIGN KEY ("course_id") REFERENCES "public"."courses" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;
ALTER TABLE "public"."comments" ADD CONSTRAINT "comments_member_id_fkey" FOREIGN KEY ("member_id") REFERENCES "public"."members" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Foreign keys structure for table courses
				-- ----------------------------
ALTER TABLE "public"."courses" ADD CONSTRAINT "courses_author_id_fkey" FOREIGN KEY ("author_id") REFERENCES "public"."authors" ("id") ON
UPDATE CASCADE ON
DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE "public"."courses" ADD CONSTRAINT "courses_category_id_fkey" FOREIGN KEY ("category_id") REFERENCES "public"."categories" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Foreign keys structure for table files
				-- ----------------------------
ALTER TABLE "public"."files" ADD CONSTRAINT "files_course_id_fkey" FOREIGN KEY ("course_id") REFERENCES "public"."courses" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;

				-- ----------------------------
				--  Foreign keys structure for table playlist
				-- ----------------------------
ALTER TABLE "public"."playlist" ADD CONSTRAINT "playlist_course_id_fkey" FOREIGN KEY ("course_id") REFERENCES "public"."courses" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;
ALTER TABLE "public"."playlist" ADD CONSTRAINT "playlist_member_id_fkey" FOREIGN KEY ("member_id") REFERENCES "public"."members" ("id") ON
UPDATE CASCADE ON
DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE ;

