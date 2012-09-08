INSERT INTO `behatviewer_project` (`id`, `name`, `slug`, `test_command`, `user_id`, `configuration_id`) VALUES
(2, 'Bar Foo', 'bar-foo', 'app/console bar foo', 1, 2);

INSERT INTO `behatviewer_configuration` (`id`, `data`)
VALUES (2, '{\"repository_path\":\"\\/bar\\/foo\",\"branch\":\"master\"}');