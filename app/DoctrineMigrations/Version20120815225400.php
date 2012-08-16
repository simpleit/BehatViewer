<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120815225400 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("UPDATE behatviewer_project SET type='public'");
		$this->addSql("UPDATE behatviewer_build SET status='failed' WHERE id IN (SELECT DISTINCT build_id FROM behatviewer_feature WHERE status='failed')");
		$this->addSql("UPDATE behatviewer_build SET status='passed' WHERE id NOT IN (SELECT DISTINCT build_id FROM behatviewer_feature WHERE status='failed')");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("UPDATE behatviewer_project SET type=NULL");
		$this->addSql("UPDATE behatviewer_build SET status=NULL WHERE id IN (SELECT DISTINCT build_id FROM behatviewer_feature WHERE status='failed')");
		$this->addSql("UPDATE behatviewer_build SET status=NULL WHERE id NOT IN (SELECT DISTINCT build_id FROM behatviewer_feature WHERE status='failed')");
    }
}