<?php
  class User extends CI_controller{

    function __construct(){
      parent::__construct();
      $this->load->model('Mod_sys_user');

    }

    function index(){
      $this->load->view("zonk");
    }

    function update_pass(){
      $id = $this->session->userdata('usrId');
      $password = $this->input->post('pwdUser1');

      $result['status'] = 'SUKSES';
      $result['pesan'] = 'OK';

      $a = $this->session->userdata('password');
      $b = md5($this->input->post('pwdUserOld'));
      if( trim($a) === trim($b) ){
        $this->Mod_sys_user->update_user_password($id,$password);
        $result['status'] = 'SUKSES';
        $result['pesan'] = 'Berhasil ganti password';
        //$this->session->sess_destroy();
        //redirect('login');
      }else{
        $result['status'] = 'GAGAL';
        $result['pesan'] = 'Password lama tidak sesuai!';
      }

      echo json_encode($result);
    }


    function reset_pass(){
      $id = $this->session->userdata('usrId');
      $password = $this->input->post('resetPwdUser1');

      $result['status'] = 'SUKSES';
      $result['pesan'] = 'OK';
      $a = $this->session->userdata('password');
      $b = md5($password);

      if( trim($a) === trim($b) ){
        $result['status'] = 'GAGAL';
        $result['pesan'] = 'Mohon gunakan password yang berbeda';
      }else{
        //$this->m_sys_user->reset_user_password($user_id,$password);
        $this->Mod_sys_user->update_user_password($id,$password);
        $result['status'] = 'SUKSES';
        $result['pesan'] = 'Berhasil reset password, silahkan login kembali';
      }

      echo json_encode($result);
    }

/*
    function createUser(){
      $data['user'] = $this->Mod_login->getData();
      $this->load->view("header");
      $this->load->view("view_createUser",$data);
    }

    function inputUser(){
      $this->Mod_login->userInput();
      redirect('home');
    }

    function editUser($userID){
      echo "edit<br> no ID : ".$userID;
    }
    function deleteUser($userID){
      echo "delete<br> no ID : ".$userID;
    }
*/

  }
 ?>
