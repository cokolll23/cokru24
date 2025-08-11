<?php
// пространство имен для подключений ланговых файлов
use Bitrix\Main\Localization\Loc;

// пространство имен для управления (регистрации/удалении) модуля в системе/базе
use Bitrix\Main\ModuleManager;

// пространство имен для работы с параметрами модулей хранимых в базе данных
use Bitrix\Main\Config\Option;

// пространство имен с абстрактным классом для любых приложений, любой конкретный класс приложения является наследником этого абстрактного класса
use Bitrix\Main\Application;

// пространство имен для работы c ORM
use \Bitrix\Main\Entity\Base;

// пространство имен для автозагрузки модулей
use \Bitrix\Main\Loader;

// пространство имен для событий
use \Bitrix\Main\EventManager;

// подключение ланговых файлов
Loc::loadMessages(__FILE__);

class Lab_Garage extends CModule
{

    // переменные модуля
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $SHOW_SUPER_ADMIN_GROUP_RIGHTS;
    public $MODULE_GROUP_RIGHTS;
    public $errors;


    function __construct()
    {
        // создаем пустой массив для файла version.php
        $arModuleVersion = array();
        // подключаем файл version.php
        include_once(__DIR__ . '/version.php');
        // версия модуля
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        // дата релиза версии модуля
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        // id модуля
        $this->MODULE_ID = "lab.garage";
        // название модуля
        $this->MODULE_NAME = "Закладка Гараж в контактах CRM";
        // описание модуля
        $this->MODULE_DESCRIPTION = "Закладка Гараж в контактах CRM";
        // имя партнера выпустившего модуль
        $this->PARTNER_NAME = "Lab";
        // ссылка на рисурс партнера выпустившего модуль
        $this->PARTNER_URI = "https://cokru.ru";
        // если указано, то на странице прав доступа будут показаны администраторы и группы
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        // если указано, то на странице редактирования групп будет отображаться этот модуль
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    // метод отрабатывает при установке модуля
    function DoInstall()
    {
        // if ($this->isVersionD7()) {

        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();

        //*************************************//
        // Пример с установкой в один шаг      //
        //*************************************//
        // // глобальная переменная с обстрактным классом
        // global $APPLICATION;
        // // регистрируем модуль в системе
        // ModuleManager::RegisterModule("hmarketing.d7");
        // // создаем таблицы баз данных, необходимые для работы модуля
        // $this->InstallDB();
        // // создаем первую и единственную запись в БД
        // $this->addData();
        // // регистрируем обработчики событий
        // $this->InstallEvents();
        // // копируем файлы, необходимые для работы модуля
        // $this->InstallFiles();
        // // устанавливаем агента
        // $this->installAgents();
        // // подключаем скрипт с административным прологом и эпилогом
        // $APPLICATION->includeAdminFile(
        //     Loc::getMessage('INSTALL_TITLE'),
        //     __DIR__ . '/instalInfo.php'
        // );

        //  } else {
        // throw new SystemException(Loc::getMessage('LAB_CRMCUSTOMTAB_INSTALL_ERROR_VERSION'));
        // }
    }

    // метод отрабатывает при удалении модуля
    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    // метод для создания таблицы баз данных
    function InstallDB()
    {
        // подключаем модуль для того что бы был видем класс ORM
        Loader::includeModule($this->MODULE_ID);
        // через класс Application получаем соединение по переданному параметру, параметр берем из ORM-сущности (он указывается, если необходим другой тип подключения, отличный от default), если тип подключения по умолчанию, то параметр можно не передавать. Далее по подключению вызываем метод isTableExists, в который передаем название таблицы полученное с помощью метода getDBTableName() класса Base
        if (!Application::getConnection(\Lab\Garage\GarageTable::getConnectionName())->isTableExists(Base::getInstance("\Lab\Garage\GarageTable")->getDBTableName())) {
            // eсли таблицы не существует, то создаем её по ORM сущности
            Base::getInstance("\Lab\Garage\GarageTable")->createDbTable();
        }

        $this->installGarageTable();

    }

    // метод для удаления таблицы баз данных
    function UnInstallDB()
    {
        // подключаем модуль для того что бы был видем класс ORM
        Loader::includeModule($this->MODULE_ID);
        // делаем запрос к бд на удаление таблицы, если она существует, по подключению к бд класса Application с параметром подключения ORM сущности
        Application::getConnection(\Lab\Garage\GarageTable::getConnectionName())->queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance("\Lab\Garage\GarageTable")->getDBTableName());

        // удаляем параметры модуля из базы данных битрикс
        Option::delete($this->MODULE_ID);

        $this->unInstallGarageTable();
    }

    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\Lab\Garage\Events',
            // метод обработчика
            'eventHandler'
        );

    }

    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\Lab\Garage\Events',
            'eventHandler'
        );
    }

    function InstallFiles()
    {
        // скопируем компоненты из папки в битрикс, копирует одноименные файлы из одной директории в другую директорию

        CopyDirFiles(__DIR__ . "/components", $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components', true, true);

    }

    // метод для удаления файлов модуля при удалении
    function UnInstallFiles()
    {
        // удалим компонент из папки в битрикс
        if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID)) {
            // удаляет папка из указанной директории, функция работает рекурсивно
            DeleteDirFilesEx(
                "/bitrix/components/" . $this->MODULE_ID
            );
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

    /**
     * @throws SqlQueryException
     */
    private function unInstallGarageTable(): void
    {
        $connection = Application::getConnection();
        $tableName = 'garage';

        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }
    }

   /* public function getPath($notDocumentRoot = false): string
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }*/

}
