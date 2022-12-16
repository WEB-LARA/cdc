<?php
  class Home extends CI_controller{

    function __construct(){
      parent::__construct();
      if(!$this->session->userdata('logged_in')){
        redirect(base_url('Login'));
      }
      $this->load->model('Mod_login');
      $this->load->model('Mod_sys_user');
      $this->load->model('Mod_sys_menu');

      $tag_chose_dc = '';
      $height = '170';

      if ($this->session->userdata('role_id') < 4 ) {
        $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
        $shift = $this->Mod_login->check_shift($this->session->userdata('usrId'));
        if ($shift) {
          if ($shift[0]->SHIFT_NUMBER) {
            $this->session->set_userdata('shift_num',$shift[0]->SHIFT_NUMBER);
            $this->session->set_userdata('no_ref',$shift[0]->NO_REF);
            $this->session->set_userdata('dc_code',$shift[0]->DC_CODE);
            $dc_type = $this->Mod_login->check_dc_type($shift[0]->DC_CODE);
            $this->session->set_userdata('dc_type',$dc_type[0]->DC_TYPE);
          } else {
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
      }elseif ($this->session->userdata('role_id') > 4 && $this->session->userdata('role_id') == 9 ) {
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
        $dc_type = $this->Mod_login->check_dc_type($this->session->userdata('dc_code'));
        $shift = $this->Mod_login->check_shift($this->session->userdata('usrId'));
      }
    }

    function index(){
      $data['user'] = $this->Mod_login->getData();
      $data3['user'] = $this->Mod_sys_user->getData();

      $data['menu'] = $this->Mod_sys_menu->getMenu();
  	  $data['subMenu'] = $this->Mod_sys_menu->getSub();
  	  /*$data['shift'] = $this->Mod_cdc_master_shift->shiftLogin();*/


      $this->load->view("main/main_header");
      $this->load->view("main/main_menu",$data);
	    $this->load->view("main/main_body");
      $this->load->view("main/main_footer");
    } //END of INDEX()

    function call(){
      echo "test";
    }


  }
?>
