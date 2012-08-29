INSERT INTO `behatviewer_project` (`id`, `name`, `slug`, `test_command`, `user_id`, `strategy`, `configuration_id`) VALUES
(1, 'Foo Bar', 'foo-bar', 'app/console foo bar', 1, 'git_local', 1);

INSERT INTO `behatviewer_configuration` (`id`, `data`)
VALUES (1, '{\"repository_path\":\"\\/foo\\/bar\",\"branch\":\"master\"}');
