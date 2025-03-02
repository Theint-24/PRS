<?php

namespace App\Controller;

use App\Controller\AppController;
use Symfony\Component\VarDumper\Cloner\Data;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;
use DateTime;
use LengthException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    /**
     * Mu lar Sann
     * User Controller for User CRUD action
     * index is for user List
     * view is for user profile
     * add is for user registration
     * edit is for user update
     * delete is for user delete action
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Admins']
        ];
        // $data = $this->Users->find('all')->where(del_flg => 'not');
        $data = $this->Users->find('all', array('conditions' => array('Users.del_flg' => 'not')));
        $users = $this->paginate($data);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {

            $user = $this->Users->patchEntity($user, $this->request->getData());
            //img upload
            $userImage = $this->request->getData('profile_img');
            $name = $userImage['name'];

            $type = pathinfo($name, PATHINFO_EXTENSION);
            $targetPath = WWW_ROOT . 'img' . DS . 'profile_img' . DS . $name;
            $hasher = new DefaultPasswordHasher();
            $token = Security::hash(Security::randomBytes(32));
            $premium_flg = "normal";
            $reward = "no reward";
            $del_flg = "not";
            $last_login = date('Y-m-d');
            $admin_id = null;
            $score = 0;
            $password = $this->request->getData('password');
            //age calculation
            $birthdate = $this->request->getData('birthdate');
            $dob_year = date('Y', strtotime($birthdate));
            $today =  date('Y-m-d');
            $today_year = date('Y', strtotime($today));
            $age = $today_year - $dob_year;

            $user->premium_flg = $premium_flg;
            $user->reward = $reward;
            $user->del_flg = $del_flg;
            $user->last_login = $last_login;
            $user->admin_id = $admin_id;
            $user->score = $score;
            $user->token = $token;
            $user->age = $age;
            $user->password = $hasher->hash($password);

            //image upload while file type is jpeg jpg and png
            if ($type == 'jpeg' || $type == 'jpg' || $type == 'png') {
                if (move_uploaded_file($userImage['tmp_name'], $targetPath)) {
                    if (!empty($name)) {
                        $user->profile_img = $name;
                    }
                }
            } else {
                $this->Flash->error(__('The file type could not be saved. Please, choose image file.'));
            }
            //when user age is less than 15 and greater than 100 we are not allow the user registeration
            if ($age >= 15 and $age <= 100) {
                $phone_no = $this->request->getData('phone');
                //phone no is no longer than 15
                if (strlen($phone_no) < 15) {
                    $user->phone = $phone_no;
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('The user has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The user could not be saved. Please, try again.'));
                    }
                } else {
                    $this->Flash->error(__('Your phone number is too long not longer than 15 digit'));
                }
            } elseif ($age < 15 or $age > 100) {
                $this->Flash->error(__('The user is not allowed to use the System.Only User age between age 15 and 100 allow.'));
            }
        }
        $admins = $this->Users->Admins->find('list', ['limit' => 200]);
        $this->set(compact('user', 'admins'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $hasher = new DefaultPasswordHasher();
        $user = $this->Users->get($id, [
            'contain' => [], 'formatted_date' => 'DATE_FORMAT(birthdate,"Y-m-d")'
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            //img upload
            $img_name = $this->request->getData('img_name');
            $userImage = $this->request->getData('profile_img');
            $new_img = $userImage['name'];

            if ($new_img != '') {
                $image = $new_img;
            } else {
                $image = $img_name;
            }
            $name = $image;
            $type = pathinfo($name, PATHINFO_EXTENSION);

            //age calculation
            $birthdate = $this->request->getData('birthdate');
            $dob_year = date('Y', strtotime($birthdate));
            $today =  date('Y-m-d');
            $today_year = date('Y', strtotime($today));
            $age = $today_year - $dob_year;
            //$age = date_create($birthdate)->diff(date_create($today));

            $user->age = $age;

            $targetPath = WWW_ROOT . 'img' . DS . 'profile_img' . DS . $name;
            $password = $this->request->getData('password');
            if (strlen($password) < 25) {
                $user->password = $hasher->hash($password);
            }
            if ($new_img != null) {
                if ($type == 'jpeg' || $type == 'jpg' || $type == 'png') {
                    if (move_uploaded_file($userImage['tmp_name'], $targetPath)) {
                        if (!empty($name)) {
                            $user->profile_img = $name;
                        }
                    }
                } else {
                    $this->Flash->error(__('The file type could not be saved. Please, choose image file.'));
                }
            } else {
                $user->profile_img = $name;
            }

            if ($age >= 15 and $age <= 100) {
                $phone_no = $this->request->getData('phone');
                if (strlen($phone_no) < 15) {
                    $user->phone = $phone_no;
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('The user has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                } else {
                    $this->Flash->error(__('Your phone number is too long not longer than 15 digit'));
                }
            } elseif ($age < 15 or $age > 100) {
                $this->Flash->error(__('The user is not allowed to use the System. Please, try again.'));
            }
            $this->Flash->error(__('The user could not be Update. Please, try again.'));
        }
        $admins = $this->Users->Admins->find('list', ['limit' => 200]);
        $this->set(compact('user', 'admins'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $user->del_flg = "deleted";
        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
