INSERT INTO `behatviewer_project` (`id`, `name`, `slug`, `test_command`, `user_id`, `configuration_id`) VALUES
(2, 'Bar Foo', 'bar-foo', 'app/console bar foo', 1, 2);

INSERT INTO `behatviewer_configuration` (`id`, `data`)
VALUES (2, '{\"repository_path\":\"\\/bar\\/foo\",\"branch\":\"master\"}');

REPLACE INTO `acl_classes` (`id`, `class_type`)
VALUES (1, 'BehatViewer\\BehatViewerBundle\\Entity\\Project');

INSERT INTO `acl_object_identities` (`id`, `parent_object_identity_id`, `class_id`, `object_identifier`, `entries_inheriting`)
VALUES (2, NULL, 1, '2', 1);

INSERT INTO `acl_object_identity_ancestors` (`object_identity_id`, `ancestor_id`)
VALUES (2, 2);

REPLACE INTO `acl_security_identities` (`id`, `identifier`, `username`)
VALUES (1, 'BehatViewer\\BehatViewerBundle\\Entity\\User-user', 1);

INSERT INTO `acl_entries` (`id`, `class_id`, `object_identity_id`, `security_identity_id`, `field_name`, `ace_order`, `mask`, `granting`, `granting_strategy`, `audit_success`, `audit_failure`)
VALUES (1, 1, 2, 1, NULL, 0, 128, 1, 'all', 0, 0);
