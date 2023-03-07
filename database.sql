DROP TABLE IF EXISTS `comment`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NULL,
    `surname` VARCHAR(255) NULL,
    `city` VARCHAR(255) NULL,
    `isAdmin` TINYINT UNSIGNED NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`)
);

CREATE TABLE `comment`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `userId` INT UNSIGNED NOT NULL,
    `comment` VARCHAR(400) NOT NULL,
    `stars` TINYINT UNSIGNED NOT NULL,
    `date` DATETIME NOT NULL,

    PRIMARY KEY (`id`)
);
ALTER TABLE
    `comment` ADD CONSTRAINT `comment_userid_foreign` FOREIGN KEY(`userId`) REFERENCES `user`(`id`);


INSERT INTO
`user` (`id`, `email`,`username`, `password`, `name`, `surname`, `city`, `isAdmin`)
values
(NULL, 'admin@admin.com','admin', 'admin', 'genoveffo', 'ginevro', 'Padova', 1),
(NULL, 'user@user.com','user', 'user', 'franco', 'battiatino', 'Padova', 0),
(NULL, 'user1@user1.com','user1', 'user1', 'bob', 'sponge', 'Padova', 0);

INSERT INTO
`comment` (`id`, `userId`, `comment`, `stars`, `date`) 
values 
(NULL, 2, 'Consiglio a tutti Crystal Ski, impianti di risalita veloci e efficaci, piste con neve fresca e ben battuta e vista spettacolare. Unica pecca la cioccolata calda della baita.', 5, NOW()),
(NULL, 3, ' Brutte piste, la neve sparata dai cannoni era insufficiente, personale poco cordiale', 2, NOW());





