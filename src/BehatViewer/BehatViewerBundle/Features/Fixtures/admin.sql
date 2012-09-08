INSERT INTO behatviewer_role(`id`, `name`, `role`) VALUES(2, 'Admin', 'ROLE_admin');

INSERT INTO `behatviewer_user` (`id`, `username`, `salt`, `password`, `email`, `is_active`)
VALUES (2, 'admin', '25a471df497cf7c89c1d1f38c889fb2c', '5f06047b0c4cf7473efc0782268d716dc038333c', 'admin@foo.bar', 1);

INSERT INTO behatviewer_user_role VALUES(2, 2);
INSERT INTO behatviewer_user_role VALUES(2, 1);
