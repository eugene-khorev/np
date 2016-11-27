<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Check if password has been changed
            $dirtyPassword = $this->getDirtyAttributes(['password']);
            if (!empty($dirtyPassword)) {
                // Encode password before saving model
                $this->password = static::EncodePassword($this->password);
            }
            
            // Check if access token is not defined
            if (empty($this->access_token)) {
                // Generate access token
                $this->access_token = \Yii::$app->security->generateRandomString();
                $this->access_token_expires = static::getTokenExpirationTime();
            }
            
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::find()
                ->where(['access_token' => $token])
                ->andWhere(['>', 'access_token_expires', date('Y-m-d H:i:s')])->one();
        
        if (!empty($user)) {
            $user->access_token_expires = static::getTokenExpirationTime();
            $user->save();
        }
        
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function ResetAccessToken()
    {
        $this->access_token = null;
        $this->save();
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === static::EncodePassword($password);
    }

    /**
     * Returns password hash
     *
     * @param string $password password to encode
     * @return string
     */
    public static function EncodePassword($password)
    {
        return md5($password);
    }

    /**
     * Returns token expiration time
     * 
     * @return string Date and time
     */
    private static function getTokenExpirationTime()
    {
        return date('Y-m-d H:i:s', strtotime('now + 15 minutes'));
    }
    
}
