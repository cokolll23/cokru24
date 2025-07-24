<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;


class CBPHelloWorldActivity extends BaseActivity
{
    /**
     * @param void
     * @see parent::_construct()
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Title' => '',

            // return
            'Text' => null,
        ];

        $this->SetPropertiesTypes([
            'Text' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection
    {
        $errors = parent::internalExecute();

        $this->preparedProperties['Text'] = "Привет, мир!";

        $this->log($this->preparedProperties['Text']);

        return $errors;
    }

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Subject' => [
                'Name' => Loc::getMessage('HELLOWORLD_ACTIVITY_FIELD_SUBJECT'),
                'FieldName' => 'subject',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Default' => Loc::getMessage('HELLOWORLD_ACTIVITY_DEFAULT_SUBJECT'),
                'Options' => [],
            ],
            'Comment' => [
                'Name' => Loc::getMessage('HELLOWORLD_ACTIVITY_FIELD_COMMENT'),
                'FieldName' => 'comment',
                'Type' => FieldType::TEXT,
                'Required' => true,
                'Options' => [],
            ],
        ];
        return $map;
    }
}