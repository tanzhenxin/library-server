<?php

class ChangePasswordForm extends CFormModel
{
  public $currentPassword;
  public $newPassword;
  public $newPassword_repeat;
  private $_user;
  
  public function rules()
  {
    return array(
      array(
        'currentPassword', 'compareCurrentPassword'
      ),
      array(
        'currentPassword, newPassword, newPassword_repeat', 'required',
        'message'=>'Enter your {attribute}.',
      ),
      array(
        'newPassword_repeat', 'compare',
        'compareAttribute'=>'newPassword',
        'message'=>'The new password does not match.',
      ),
      
    );
  }
        
  public function compareCurrentPassword($attribute,$params)
  {
    if( base64_encode(md5(trim($this->currentPassword))) !== $this->_user->pwd )
    {
      $this->addError($attribute,'The Current Password is incorrect');
    }
  }
  
  public function init()
  {
      $username = Yii::app()->User->name;
    $this->_user = User::model()->findByAttributes( array( 'username'=> $username) );
  }
  
  public function attributeLabels()
  {
    return array(
      'currentPassword'=>'Current Password',
      'newPassword'=>'New Password',
      'newPassword_repeat'=>'New Password (Repeat)',
    );
  }
  
  public function changePassword()
  {
    $this->_user->pwd = base64_encode(md5(trim($this->newPassword)));
    if( $this->_user->save() )
      return true;
    return false;
  }
}