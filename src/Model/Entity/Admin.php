<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Admin Entity
 *
 * @property int $admin_id
 * @property string $email
 * @property string $password
 * @property string $del_flg
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $token
 *
 * @property \App\Model\Entity\Admin $admin
 */
class Admin extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'admin_id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];
}
