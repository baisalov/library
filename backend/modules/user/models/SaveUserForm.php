<?php

namespace backend\modules\user\models;

use common\models\User;
use Yii;

/**
 * Class SaveUserForm
 * @package backend\modules\user\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role_name
 * @property bool $is_deleted
 */
class SaveUserForm extends User
{
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $role_name;

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'saveParamsInsert']);
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'saveRoleInsert']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'saveParamsUpdate']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveRoleUpdate']);
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['username', 'trim'];
        $rules[] = ['username', 'required'];
        $rules[] = ['username', 'string', 'min' => 2, 'max' => 255];

        $rules[] = ['email', 'trim'];
        $rules[] = ['email', 'required'];
        $rules[] = ['email', 'email'];
        $rules[] = ['email', 'string', 'max' => 255];

        $rules[] = ['password', 'required', 'on' => 'create'];
        $rules[] = ['password', 'string', 'min' => 6];

        $rules[] = ['role_name', 'required'];
        $rules[] = ['role_name', 'string'];

        $rules[] = ['status', 'required'];

        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => Yii::t('app', 'Password'),
            'role_name' => Yii::t('app', 'Role'),
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    protected function saveParamsInsert()
    {
        $this->generateAuthKey();
        $this->generateEmailVerificationToken();
        $this->setPassword($this->password);

    }

    /**
     * @throws \yii\base\Exception
     */
    protected function saveParamsUpdate()
    {
        if(!isset($this->password) && $this->password !== '') {
            $this->setPassword($this->password);
        }
    }

    /**
     *
     * @throws \Exception
     */
    protected function saveRoleInsert()
    {
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($this->role_name), $this->id);
    }

    /**
     *
     * @throws \Exception
     */
    protected function saveRoleUpdate()
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($this->id);
        $auth->assign($auth->getRole($this->role_name), $this->id);
    }
}