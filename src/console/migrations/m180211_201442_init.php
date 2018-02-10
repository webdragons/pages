<?php

namespace bulldozer\pages\console\migrations;

use bulldozer\App;
use bulldozer\users\rbac\DbManager;
use yii\base\InvalidConfigException;
use yii\db\Migration;

/**
 * Class m180211_201442_init
 * @package bulldozer\users\console\migrations
 */
class m180211_201442_init extends Migration
{
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function up()
    {
        $authManager = $this->getAuthManager();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%static_sections}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11)->unsigned(),
            'creator' => $this->integer(11)->unsigned(),
            'updater' => $this->integer(11)->unsigned(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(500)->notNull(),
            'left' => $this->integer(11)->notNull(),
            'right' => $this->integer(11)->notNull(),
            'depth' => $this->integer(11)->notNull(),
            'tree' => $this->integer(11)->unsigned()->defaultValue(0),
            'active' => $this->boolean()->defaultValue(1),
            'sort' => $this->integer(11)->unsigned()->defaultValue(100),
        ], $tableOptions);

        $this->createTable('{{%static_pages}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11)->unsigned(),
            'creator' => $this->integer(11)->unsigned(),
            'updater' => $this->integer(11)->unsigned(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(500)->notNull(),
            'body' => $this->text()->notNull(),
            'section_id' => $this->integer(11)->unsigned()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'sort' => $this->integer(11)->unsigned()->defaultValue(100),
        ], $tableOptions);

        $this->createIndex('idx_section_id', '{{%static_pages}}', 'section_id');

        $managePages = $authManager->createPermission('pages_manage');
        $managePages->name = 'Управление страницами';

        $authManager->add($managePages);

        $admin = $authManager->getRole('admin');
        $authManager->addChild($admin, $managePages);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function down()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable('{{%static_sections}}');
        $this->dropTable('{{%static_pages}}');

        $managePages = $authManager->getPermission('pages_manage');
        $authManager->remove($managePages);
    }

    /**
     * @throws InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = App::$app->getAuthManager();

        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }
}