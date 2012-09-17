INSERT INTO role(`id`, `name`, `role`) VALUES(1, 'User', 'ROLE_USER');

INSERT INTO `user` (`id`, `username`, `salt`, `password`, `email`, `is_active`, `token`)
VALUES (1, 'user', '25a471df497cf7c89c1d1f38c889fb2c', '1463c76ecfae4e3ae549470d4e6d06b3e50fc95c', 'user@foo.bar', 1, 'api_key');

INSERT INTO user_role VALUES(1, 1);
