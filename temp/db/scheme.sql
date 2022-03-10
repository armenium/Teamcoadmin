create table clients
(
	id         int unsigned auto_increment
		primary key,
	name       varchar(255) null,
	company    varchar(255) null,
	address    varchar(255) null,
	address_2  varchar(255) null,
	city       varchar(255) null,
	state      varchar(255) null,
	zip        varchar(255) null,
	country    varchar(255) null,
	email      varchar(255) null,
	phone      varchar(255) null,
	created_at timestamp    null,
	updated_at timestamp    null
)
	collate = utf8mb4_unicode_ci;

create table colors
(
	id         int unsigned auto_increment
		primary key,
	name       varchar(255)         not null,
	value_code varchar(255)         not null,
	status     tinyint(1) default 1 not null,
	position   int unsigned         null,
	created_at timestamp            null,
	updated_at timestamp            null
)
	collate = utf8mb4_unicode_ci;

create table countries
(
	id         int unsigned auto_increment
		primary key,
	name       varchar(255) not null,
	created_at timestamp    null,
	updated_at timestamp    null
)
	collate = utf8mb4_unicode_ci;

create table files
(
	id          int unsigned auto_increment
		primary key,
	name        varchar(255) null,
	url         varchar(255) null,
	description longtext     null,
	created_at  timestamp    null,
	updated_at  timestamp    null
)
	collate = utf8mb4_unicode_ci;

create table jersey_details
(
	id         int unsigned auto_increment
		primary key,
	style_code varchar(255) null,
	colors     longtext     null,
	created_at timestamp    null,
	updated_at timestamp    null,
	roster_id  int unsigned not null
)
	collate = utf8mb4_unicode_ci;

create table migrations
(
	id        int unsigned auto_increment
		primary key,
	migration varchar(255) not null,
	batch     int          not null
)
	collate = utf8mb4_unicode_ci;

create table password_resets
(
	email      varchar(255) not null,
	token      varchar(255) not null,
	created_at timestamp    null
)
	collate = utf8mb4_unicode_ci;

create index password_resets_email_index
	on password_resets (email);

create table products
(
	id          int unsigned auto_increment
		primary key,
	name        varchar(255)    not null,
	description text            null,
	url_svg     varchar(255)    not null,
	svg_info    text            null,
	shopify_id  bigint unsigned not null,
	created_at  timestamp       null,
	updated_at  timestamp       null
)
	collate = utf8mb4_unicode_ci;

create table quotes
(
	id            int unsigned auto_increment
		primary key,
	description   longtext     null,
	date_required varchar(255) null,
	client_id     int unsigned not null,
	created_at    timestamp    null,
	updated_at    timestamp    null,
	constraint quotes_client_id_foreign
		foreign key (client_id) references clients (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table file_quote
(
	id         int unsigned auto_increment
		primary key,
	quote_id   int unsigned not null,
	file_id    int unsigned not null,
	created_at timestamp    null,
	updated_at timestamp    null,
	constraint file_quote_file_id_foreign
		foreign key (file_id) references files (id)
			on update cascade on delete cascade,
	constraint file_quote_quote_id_foreign
		foreign key (quote_id) references quotes (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table rosters
(
	id            int unsigned auto_increment
		primary key,
	reference     varchar(255) null,
	comments      longtext     null,
	number_color  int          null,
	inside_color  varchar(255) null,
	outside_color varchar(255) null,
	client_id     int unsigned not null,
	created_at    timestamp    null,
	updated_at    timestamp    null,
	constraint rosters_client_id_foreign
		foreign key (client_id) references clients (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table file_roster
(
	id         int unsigned auto_increment
		primary key,
	roster_id  int unsigned not null,
	file_id    int unsigned not null,
	created_at timestamp    null,
	updated_at timestamp    null,
	constraint roster_file_file_id_foreign
		foreign key (file_id) references files (id)
			on update cascade on delete cascade,
	constraint roster_file_roster_id_foreign
		foreign key (roster_id) references rosters (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table quantities
(
	id         int unsigned auto_increment
		primary key,
	size       varchar(255) null,
	quantity   varchar(255) null,
	roster_id  int unsigned not null,
	created_at timestamp    null,
	updated_at timestamp    null,
	constraint quantities_roster_id_foreign
		foreign key (roster_id) references rosters (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table sizes
(
	id         int unsigned auto_increment
		primary key,
	name       varchar(4) null,
	created_at timestamp  null,
	updated_at timestamp  null
)
	collate = utf8mb4_unicode_ci;

create table states
(
	id         int unsigned auto_increment
		primary key,
	name       varchar(255) not null,
	state_code varchar(255) not null,
	country_id int unsigned not null,
	created_at timestamp    null,
	updated_at timestamp    null,
	constraint states_country_id_foreign
		foreign key (country_id) references countries (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table styles
(
	id                 int unsigned auto_increment
		primary key,
	product_name       varchar(255) null,
	product_shopify_id bigint       null,
	quantity           int          null,
	style_info         longtext     null,
	url_svg_temp       longtext     null,
	quote_id           int unsigned not null,
	created_at         timestamp    null,
	updated_at         timestamp    null,
	constraint styles_quote_id_foreign
		foreign key (quote_id) references quotes (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table teams
(
	id         int unsigned auto_increment
		primary key,
	size       varchar(255) null,
	number     varchar(255) null,
	name       varchar(255) null,
	roster_id  int unsigned not null,
	created_at timestamp    null,
	updated_at timestamp    null,
	constraint teams_roster_id_foreign
		foreign key (roster_id) references rosters (id)
			on update cascade on delete cascade
)
	collate = utf8mb4_unicode_ci;

create table token_products
(
	id         int unsigned auto_increment
		primary key,
	token      varchar(255)    not null,
	product_id bigint unsigned not null,
	data       text            null,
	url_svg    text            null,
	created_at timestamp       null,
	updated_at timestamp       null
)
	collate = utf8mb4_unicode_ci;

create index token_products_token_index
	on token_products (token);

create table users
(
	id                int unsigned auto_increment
		primary key,
	name              varchar(255) not null,
	email             varchar(255) not null,
	email_verified_at timestamp    null,
	password          varchar(255) not null,
	remember_token    varchar(100) null,
	created_at        timestamp    null,
	updated_at        timestamp    null,
	constraint users_email_unique
		unique (email)
)
	collate = utf8mb4_unicode_ci;


