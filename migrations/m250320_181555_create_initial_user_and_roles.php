<?php

use yii\db\Migration;

class m250320_181555_create_initial_user_and_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $auth = Yii::$app->authManager;

        // Crear un rol llamado "admin"
        $administratorRole = $auth->createRole('admin');
        $administratorRole->description = 'Administrator';
        $auth->add($administratorRole);

        // Crear permiso para ciertas tareas
        $permission = $auth->createPermission('user-management');
        $permission->description = 'User Management';
        $auth->add($permission);

        // Permitir que los administradores gestionen usuarios
        $auth->addChild($administratorRole, $auth->getPermission('user-management'));

        // Crear usuario "admin" con contraseña "verysecret"
        $user = new \Da\User\Model\User([
            'scenario' => 'create',
            'email' => "email@example.com",
            'username' => "admin",
            'password' => "verysecret"  // >6 caracteres
        ]);
        $user->confirmed_at = time();
        $user->save();

        // Asignar rol al usuario admin
        $auth->assign($administratorRole, $user->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m250320_181555_create_initial_user_and_roles cannot be reverted.\n";

        //return false;
        $auth = Yii::$app->authManager;

        // Eliminar permiso
        $auth->remove($auth->getPermission('user-management'));

        // Eliminar usuario admin y rol de administrador
        $administratorRole = $auth->getRole("admin");
        $user = \Da\User\Model\User::findOne(['username'=>"admin"]);
        $auth->revoke($administratorRole, $user->id);
        $user->delete();
        $auth->remove($administratorRole);
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250320_181555_create_initial_user_and_roles cannot be reverted.\n";

        return false;
    }
    */
}
