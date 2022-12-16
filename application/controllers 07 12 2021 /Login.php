<?php
  class Login extends CI_controller{

      function __construct(){
        parent::__construct();
        $this->load->model('master/Mod_cdc_master_branch');
      }

      function index(){
        if($this->session->userdata('logged_in')){
          redirect(base_url());
        }else{
          $this->load->view("main/main_header");
          $this->load->view("view_login");
        }
      }

      function validate_user(){
    		$user = $this->input->post('username');
    		$pass = $this->input->post('password');

    		$resCheck = $this->Mod_login->getLogin($user,$pass);

    		echo json_encode($resCheck);
    	}

      function go_login(){
        $resultRow = array();
        $user = $this->input->post('username');
        $resultRow = $this->Mod_login->userid($user);

        $this->session->set_userdata('logged_in',true);
        $this->session->set_userdata('usrId', $resultRow['idUsr']);
        $this->session->set_userdata('username',$resultRow['usrName']);
        $this->session->set_userdata('password',$resultRow['password']);
        $this->session->set_userdata('role_id',$resultRow['roleId']);
        $this->session->set_userdata('resetFlag',$resultRow['resetFlag']);

        if ($this->session->userdata('role_id') < 4) {
          $this->session->set_userdata('branch_id',$resultRow['branchId']);
          $this->session->set_userdata('dc_code',$resultRow['dcCode']);
          $branchCode = $this->Mod_cdc_master_branch->getBranchCode($this->session->userdata('branch_id'));
          $this->session->set_userdata('branch_code',$branchCode);

          $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
          $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);

          $tag_chose_dc = '';
          $height = '170';

          $shift = $this->Mod_login->check_shift($this->session->userdata('usrId'));

          if ($shift) {
            $date_s = date_create($shift[0]->SHIFT_DATE);
            $date_s2 = date_create(date('Y-m-d'));
            $diff = date_diff($date_s,$date_s2);
            $interval = intval($diff->format('%a'));
            if ($interval > 2) {
              $this->session->set_userdata('shift_date',date('Y-m-d'));
              $this->Mod_login->update_date_shift($this->session->userdata('usrId'));
            }else{
              $this->session->set_userdata('shift_date',$shift[0]->SHIFT_DATE);
            }
            $this->session->set_userdata('shift_num',$shift[0]->SHIFT_NUMBER);
            $this->session->set_userdata('no_ref',$shift[0]->NO_REF);
            $this->session->set_userdata('dc_code',$shift[0]->DC_CODE);
            $dc_type = $this->Mod_login->check_dc_type($shift[0]->DC_CODE);
            $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);
          }else{
            if ($dc_type[0]->DC_TYPE == 'DCI') {
              $dc = $this->Mod_login->get_dc($this->session->userdata('dc_code'));
              $tag_chose_dc = '
                <tr>
                  <td style="min-width: 100px!important;">DC</td>
                  <td style="min-width: 150px!important;">
                    <select id="dc_shift" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                      <option value="'.$this->session->userdata('dc_code').'">'.$this->session->userdata('dc_code').'</option>';
              foreach ($dc as $key) {
                $tag_chose_dc .= '<option value="'.$key->DC_CODE.'">'.$key->DC_CODE.'</option>';
              }
              $tag_chose_dc .= '
                  </select>
                </td>
              </tr>';
              $height = '200';
            }
            $this->session->set_flashdata('form_shift','
              <div id="form_shift" class="easyui-window" title="Pilih Shift"  style="width:360px;height:'.$height.'px; padding:10px;"
                data-options="iconCls:\'icon-script\',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
                <div data-options="region:\'center\'">
                  <table>
                    <tr>
                      <td style="min-width: 100px!important;">No Sticker</td>
                      <td style="min-width: 150px!important;">
                        <input type="hidden" id="user_id" value="'.$this->session->userdata('usrId').'">
                        <input class="easyui-textbox" type="text" id="ref_num" data-options="required:true,disabled:false" style="min-width: 200px; min-height:30px;"/>
                      </td>
                    </tr>
                    '.$tag_chose_dc.'
                    <tr>
                      <td style="min-width: 100px!important;">Shift</td>
                      <td style="min-width: 150px!important;">
                        <select id="col_shift" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                          <option value=""></option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td style="min-width: 100px!important;"></td>
                      <td style="min-width: 150px!important;">
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" id="sub_shift" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-exit\'" id="sub_shift_logout" href="'.base_url().'login/logout" style="min-width:88px !important;min-height:30px !important;">Logout</a>
                      </td>
                    </tr>
                  </table>
                </div>
            </div>');
          }
        }elseif ($this->session->userdata('role_id') > 4  && $this->session->userdata('role_id') !=7 && $this->session->userdata('role_id') !=8 ) {
          if (!$this->session->userdata('branch_id') && !$this->session->userdata('dc_code')) {
            $this->session->set_flashdata('form_shift', '
            <div id="form_choose_branch" class="easyui-window" title="Pilih Cabang"  style="width:360px;height:170px; padding:10px;" data-options="iconCls:\'icon-script\',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
                <div data-options="region:\'center\'">
                  <table>
                    <tr>
                      <td style="min-width: 100px!important;">Cabang</td>
                      <td style="min-width: 150px!important;">
                        <select id="col_branch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td style="min-width: 100px!important;">Kode Gudang</td>
                      <td style="min-width: 150px!important;">
                        <select id="col_dc_code" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" required>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td style="min-width: 100px!important;"></td>
                      <td style="min-width: 150px!important;">
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" id="sub_admin_branch" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
                        <a class="easyui-linkbutton" data-options="iconCls:\'icon-exit\'" id="sub_shift_logout" href="'.base_url().'login/logout" style="min-width:88px !important;min-height:30px !important;">Logout</a>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
          ');
          }
        }else{
          $this->session->set_userdata('branch_id',$resultRow['branchId']);
          $this->session->set_userdata('dc_code',$resultRow['dcCode']);

          $branchCode = $this->Mod_cdc_master_branch->getBranchCode($this->session->userdata('branch_id'));
          $this->session->set_userdata('branch_code',$branchCode);

          $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
          $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);

          $tag_chose_dc = '';
          $height = '170';

          $shift = $this->Mod_login->check_shift($this->session->userdata('usrId'));
        }

        $ret['logged_in'] = 'OK';
        $ret['username'] = trim($resultRow['usrName']);

        echo json_encode($ret);
      }

      public function set_shift()
      {
        $this->Mod_login->set_shift($this->input->post());
        $this->session->set_userdata('shift_num',$this->input->post('no_shift'));
        $this->session->set_userdata('no_ref',$this->input->post('no_ref'));
        $this->session->set_userdata('shift_date',date('Y-m-d'));
        if ($this->input->post('dc_shift') != 'N') {
          $cek_dc = $this->Mod_login->cek_dc($this->input->post('dc_shift'));
          if ($cek_dc) {
            $this->session->set_userdata('dc_code',$this->input->post('dc_shift'));
            $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
            $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);
            echo 'sukses';
          }else{
            echo 'Kode gudang tidak terdaftar pada sistem CDC Web, mohon untuk menghubungi IT Support untuk didaftarkan kode gudang tersebut';
          }
        }
      }

      public function del_shift()
      {
        $this->Mod_login->del_shift($this->input->post());
      }

      public function check_role()
      {
        echo $this->session->userdata('role_id');
      }

      public function admin_choose_branch()
      {
        $result = $this->Mod_login->admin_choose_branch();
        echo json_encode($result);
      }

      public function admin_choose_dc($branch_id)
      {
        $result = $this->Mod_login->admin_choose_dc($branch_id);
        echo json_encode($result);
      }

      public function set_admin_branch()
      {
        $this->session->set_userdata('branch_id',$this->input->post('branch'));
        $this->session->set_userdata('dc_code',$this->input->post('dc'));

        $branchCode = $this->Mod_cdc_master_branch->getBranchCode($this->session->userdata('branch_id'));
        $this->session->set_userdata('branch_code',$branchCode);

        $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
        $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);
      }

      function logout(){
        if ($this->session->userdata('logged_in')) {
          $this->session->sess_destroy();
        }
        redirect(base_url().'login');
      }

  }

 ?>
