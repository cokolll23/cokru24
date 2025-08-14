<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\SystemException;
use Bitrix\Main\IO\InvalidPathException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\LoaderException;
use Lab\Crmcustomtab\Orm\BookTable;
use Lab\Crmcustomtab\Orm\AuthorTable;
use Lab\Crmcustomtab\Orm\GarageTable;
use Lab\Crmcustomtab\Data\TestDataInstaller;

Loc::getMessage(__FILE__);

class lab_crmcustomtab extends CModule
{
    public $MODULE_ID = 'lab.crmcustomtab';
    public $MODULE_SORT = 500;
    public $MODULE_VERSION;
    public $MODULE_DESCRIPTION;
    public $MODULE_VERSION_DATE;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_DESCRIPTION = Loc::getMessage('LAB_CRMCUSTOMTAB_INSTALL_MODULE_DESCRIPTION');
        $this->MODULE_NAME = Loc::getMessage('LAB_CRMCUSTOMTAB_INSTALL_MODULE_NAME');
        $this->PARTNER_NAME = Loc::getMessage('LAB_CRMCUSTOMTAB_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('LAB_CRMCUSTOMTAB_PARTNER_URI');
    }

    /**
     * @throws SystemException
     */
    public function DoInstall(): void
    {
        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallFiles();
            $this->InstallDB();
            $this->InstallEvents();

        } else {
            throw new SystemException(Loc::getMessage('LAB_CRMCUSTOMTAB_INSTALL_ERROR_VERSION'));
        }
    }

    public function InstallFiles($params = []): void
    {
        $component_path = $this->getPath() . '/install/components';

        if (Directory::isDirectoryExists($component_path)) {
            CopyDirFiles($component_path, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components', true, true);
        } else {
            throw new InvalidPathException($component_path);
        }
    }

    public function InstallDB(): void
    {
        Loader::includeModule($this->MODULE_ID);

        $entities = $this->getEntities();

        foreach ($entities as $entity) {
            if (!Application::getConnection($entity::getConnectionName())->isTableExists($entity::getTableName())) {
                Base::getInstance($entity)->createDbTable();
            }
        }
        $this->installManyToManyTable();

        foreach ($entities as $entity) {
            $this->addEntityElements($entity);
        }
    }

    private function installGarageTable(): void
    {
        $connection = Application::getConnection();
        $tableName = 'garage';

        if (!$connection->isTableExists($tableName)) {
            $connection->queryExecute("
           create table garage (
            id int unsigned not null auto_increment primary key,
             MARKA varchar(255) not null,
             MODEL varchar(255) not null,
            YEAR VARCHAR(255) not null,
            COLOR varchar(255) not null,
             MILEAGE varchar(255) not null
)
        ");
        }
    }

    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        // создание вкладки в карточке сущности CRM
        $eventManager->registerEventHandler(// /local/modules/lab.crmcustomtab/lib/Crm/Handlers.php
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\Handlers',//класс
            'updateTabs' // метод
        );
        $eventManager->registerEventHandler(
            'crm',
            'OnAfterCrmDealAdd',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\OnAfterCrmDealAddHandler',
            'OnAfterCrmDealAddHandler'
        );

        // todo зарегить кастомные js расширения из модуля в обработчике
        $eventManager->registerEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\OnPrologHandler',// обработчик класс
            'OnPrologHandler'// метод класса
        );
    }

    private function addEntityElements(string $entityClass): void
    {
        if ($entityClass === AuthorTable::class) {
            TestDataInstaller::addAuthors();
        } elseif ($entityClass === BookTable::class) {
            TestDataInstaller::addBooks();
        }
    }

    private function installManyToManyTable(): void
    {
        $connection = Application::getConnection();
        $tableName = 'lab_book_author';

        if (!$connection->isTableExists($tableName)) {
            $connection->queryExecute("
            CREATE TABLE {$tableName} (
                BOOK_ID int NOT NULL,
                AUTHOR_ID int NOT NULL,
                PRIMARY KEY (BOOK_ID, AUTHOR_ID),
                CONSTRAINT fk_{$tableName}_book FOREIGN KEY (BOOK_ID) REFERENCES lab_book(ID) ON DELETE CASCADE,
                CONSTRAINT fk_{$tableName}_author FOREIGN KEY (AUTHOR_ID) REFERENCES lab_author(ID) ON DELETE CASCADE
            )
        ");
        }
    }



    /**
     * @throws SqlQueryException
     * @throws LoaderException
     * @throws InvalidPathException
     */
    public function DoUninstall(): void
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    /**
     * @throws InvalidPathException
     */
    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\Handlers',
            'updateTabs'
        );
        $eventManager->registerEventHandler(
            'crm',
            'OnAfterCrmDealAdd',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\OnAfterCrmDealAddHandler',
            'OnAfterCrmDealAddHandler'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            '\\Lab\\Crmcustomtab\\Crm\\OnPrologHandler',
            'OnPrologHandler'
        );
    }

    /**
     * @throws SqlQueryException
     * @throws LoaderException
     */
    public function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = \Bitrix\Main\Application::getConnection();

        $entities = $this->getEntities();
        $this->unInstallManyToManyTable();

        foreach ($entities as $entity) {
            if (Application::getConnection($entity::getConnectionName())->isTableExists($entity::getTableName())) {
                $connection->dropTable($entity::getTableName());
            }
        }

    }

    /**
     * Удаляет файлы, установленные компонентом
     * @throws InvalidPathException
     */
    public function UninstallFiles(): void
    {
        $component_path = $this->getPath() . '/install/components';

        if (Directory::isDirectoryExists($component_path)) {
            $installed_components = new \DirectoryIterator($component_path);
            foreach ($installed_components as $component) {
                if ($component->isDir() && !$component->isDot()) {
                    $target_path = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $component->getFilename();
                    if (Directory::isDirectoryExists($target_path)) {
                        Directory::deleteDirectory($target_path);
                    }
                }
            }
        } else {
            throw new InvalidPathException($component_path);
        }
    }

    /**
     * @throws SqlQueryException
     */
    private function unInstallManyToManyTable(): void
    {
        $connection = Application::getConnection();
        $tableName = 'lab_book_author';

        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }
    }


    /**
     * @return
     *  classes моделей в папке lib для установки таблиц в БД
     */
    private function getEntities(): array
    {
        return [
            AuthorTable::class,
            BookTable::class,
            GarageTable::class,
        ];
    }

    public function getPath($notDocumentRoot = false): string
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '20.00.00');
    }
}
