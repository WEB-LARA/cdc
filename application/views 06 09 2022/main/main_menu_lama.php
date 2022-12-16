<link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/gif">
<body class="easyui-layout" background="<?php echo base_url();?>assets/image/wall_login.jpg">
  <div DATA-OPTIONS="region:'north'" style="height:55px; no-repeat;">
      <div style="margin:4px;" align="left">
          &nbsp

      <div id='menuUtama'>
          <?php
            foreach ($menu as $row){
            if(trim($row->DETAILED_DATA) === "Y"){
            echo "<a href='#' class='easyui-menubutton' data-options= menu:'#".$row->MENU_ID."'>".$row->MENU_NAME."</a>";
          }
          else{
             
           /* if($row->MENU_ID == 8){
               echo "<a href='#' class='easyui-linkbutton' onclick='showabsensikodel()' data-options='plain:true'>".$row->MENU_NAME."</a>";
            }else{*/
               echo "<a href='".base_url()."".$row->URL."' class='easyui-linkbutton' data-options='plain:true'>".$row->MENU_NAME."</a>";
            //}
          }
        }
        ?>
      </div> <!-- close div menuUtama -->

        <?php
        foreach ($menu as $row){
      //IF PUNYA SUBMENU
      if(trim($row->DETAILED_DATA) === "Y"){
        echo " <div id='".$row->MENU_ID."' style='width:300px;'> ";
        foreach ($subMenu as $row1){
          if( trim($row1->MENU_ID) === trim($row->MENU_ID) ){
            if(trim($row1->DETAILED_DATA) === "Y"){
              echo "<div>";
                echo "<span class='easyui-linkbutton'  data-options='plain:true'>".$row1->DETAIL_NAME."</span> ";
                  echo "<div>";
                  foreach ($subMenu as $row2){
                    if( trim($row2->MENU_ID) === trim($row1->DETAIL_ID) ){
                      if(trim($row2->DETAILED_DATA) === "Y"){
                        echo "<div> <span class='easyui-linkbutton' data-options='plain:true'>".$row2->DETAIL_NAME."</span>";
                        echo "<div>";

                          foreach ($subMenu as $row3){
                          if( trim($row3->MENU_ID) === trim($row2->DETAIL_ID) ){
                            echo "<div> <a href='".base_url()."".str_replace(" ","",$row3->URL)."'class='easyui-linkbutton' data-options='plain:true'>".$row3->DETAIL_NAME."</a> </div>";
                          }
                        }
                          echo "</div>";
                        ////////////////////////////////
                        echo "</div>";
                      }
                      else{
                        echo "<div> <a href='".base_url()."".str_replace(" ","",$row2->URL)."'class='easyui-linkbutton' data-options='plain:true'>".$row2->DETAIL_NAME."</a> </div>";
                      }
                    }
                  }
                  echo "</div>"; //close div id=$row->DETAILED_DATA
              echo "</div>";
              }

            else{
              echo "<div> <a href='".base_url()."".str_replace(" ","",$row1->URL)."'class='easyui-linkbutton' data-options='plain:true' id=".$row1->ID_ATTRIBUTE.">".$row1->DETAIL_NAME."</a> </div>";
              }
          }
          //echo "<div>".$row1->DETAIL_NAME."</div>";
        }
        echo "</div>"; //close div id=$row->MENU_ID
      }
        }
    ?>

  </div>  <!-- CLOSE DIV ALIGN LEFT -->


        <div id="loginProfile" align="left" style="left:77%;width:500px;">
          <!-- LOGIN/LOGOUT POJOK KANAN -->
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-man'" id="userCheck" > <B> <?php echo strtoupper($this->session->userdata('username')); ?></B></a>
          <?php if ($this->session->userdata('role_id') < 4) { ?>
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-no-entry'" id="cl_shift"> <B>CLOSE SHIFT</B> </a>
          <?php }elseif ($this->session->userdata('role_id') > 4 && ($this->session->userdata('role_id') != 7) ){ ?>
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-no-entry'" id="cl_branch"> <B>CHANGE BRANCH</B> </a>
          <?php } ?>
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-exit'" id="logOut"> <B>LOGOUT</B> </a>

          <?php if ($this->session->userdata('role_id') < 4) { ?>
            <div id="loginShift">
              <?php
                  echo "SHIFT : ".$this->session->userdata('shift_num');
              ?>
            </div>
          <?php }elseif ($this->session->userdata('role_id') > 4 &&  ($this->session->userdata('role_id') != 7) ) { ?>
            <div id="loginShift">
              <?php
                  echo "BRANCH : ".$this->session->userdata('branch_code');
              ?>
            </div>
          <?php } ?>
        </div>
        <!-- END DIV ALIGMNT LEFT-->
   </div>  <!-- END DIV REGION NORTH-->


<div id="req_summary_collect" class="easyui-window" title="Summary Collect" style="width:360px;height:230px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 150px!important;">
          <select id="summary_collect_branch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Kode Gudang</td>
        <td style="min-width: 150px!important;">
          <select id="summary_collect_dc_code" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Tanggal</td>
        <td style="min-width: 150px!important;">
          <input class="easyui-datebox" type="text" id="sum_collect_date" data-options="required:false,disabled:false" style="min-width: 200px; min-height:30px;"/>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Shift</td>
        <td style="min-width: 150px!important;">
          <select id="shift_start" class="easyui-combobox" name="" style="width: 88px; min-height:30px;">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
          <span>To</span>
          <select id="shift_end" class="easyui-combobox" name="" style="width: 88px; min-height:30px;">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_sum_collect" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_trend_collection" class="easyui-window" title="Trend Collection" style="width:360px;height:150px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Priode</td>
        <td style="min-width: 150px!important;">
          <select id="periode_trend_collect" class="easyui-combobox" name="" style="width: 200px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_tren_collect" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_monitoring_voucher" class="easyui-window" title="Monitoring Voucher" style="width:450px;height:200px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center" border="0">
      <tr>
        <td>Cabang</td>
        <td colspan="3"> : 
          <select id="monitoring_voucher_branch" class="easyui-combobox" name="" style="width:265px" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td>Kode Gudang</td>
        <td colspan="3"> : 
          <select id="monitoring_voucher_dc_code" class="easyui-combobox" name="" style="width:265px" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td> Batch Number </td>
        <td colspan="3"> : <select class="easyui-combobox" name="batch_number" id="combo_batch_num" style="width:265px"> </td>
      </tr>
      <tr>
        <td> from </td>
          <td>: <select class="easyui-datebox" name="batch_number" id="date1_monitoring_voucher" style="width:115px"> </td>
        <td> to </td>
          <td>: <select class="easyui-datebox" name="batch_number" id="date2_monitoring_voucher" style="width:115px"> </td>
      </tr>
      <tr>
        <td> </td>
        <td> </td>
        <td></td>
        <td> </td>
      </tr>
      <tr>
        <td> </td>
        <td> </td>
        <td></td>
        <td align="right"> <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_monitoring_voucher">Submit</a> </td>
      </tr>
    </table>
</div>

<div id="upload_data_am_as" class="easyui-window" title="Upload Data AM dan AS" style="width:360px;height:150px; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form action="<?php echo base_url(); ?>Upload/upload_am_as" method="post" enctype="multipart/form-data">
    <table align="center">
      <tr>
        <td style="min-width: 150px!important;">
          <input id="file_am_as" class="easyui-filebox" name="file_am_as" style="width: 200px; min-height:30px;" />
          <input type="hidden" name="curcname" value="<?php echo $this->router->fetch_class().'/'.$this->router->fetch_method(); ?>">
        </td>
      </tr>
      <tr>
        <td style="min-width: 150px!important;">
          <input class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_am_as" href="" style="min-width:88px !important;min-height:30px !important;" value="Upload" type="submit" />
          <a href="" class="easyui-linkbutton" style="min-width:88px !important;min-height:30px !important;" id="temp_am_as">Template</a>
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="upload_data_go" class="easyui-window" title="Upload Data GO" style="width:360px;height:150px; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form action="<?php echo base_url(); ?>Upload/upload_go" method="post" enctype="multipart/form-data">
    <table align="center">
      <tr>
        <td style="min-width: 150px!important;">
          <input id="file_go" class="easyui-filebox" name="file_go" style="width: 200px; min-height:30px;" />
          <input type="hidden" name="curcname_go" value="<?php echo $this->router->fetch_class().'/'.$this->router->fetch_method(); ?>">
        </td>
      </tr>
      <tr>
        <td style="min-width: 150px!important;">
          <input class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_go" href="" style="min-width:88px !important;min-height:30px !important;" value="Upload" type="submit" />
          <a href="" class="easyui-linkbutton" style="min-width:88px !important;min-height:30px !important;" id="temp_go">Template</a>
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="upload_data_voucher" class="easyui-window" title="Upload Data Voucher" style="width:360px;height:150px; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form action="<?php echo base_url(); ?>Upload/upload_voucher" method="post" enctype="multipart/form-data">
    <table align="center">
      <tr>
        <td style="min-width: 150px!important;">
          <input id="file_voucher" class="easyui-filebox" name="file_voucher" style="width: 200px; min-height:30px;" />
          <input type="hidden" name="curcname_voucher" value="<?php echo $this->router->fetch_class().'/'.$this->router->fetch_method(); ?>">
        </td>
      </tr>
      <tr>
        <td style="min-width: 150px!important;">
          <input class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_voucher" href="" style="min-width:88px !important;min-height:30px !important;" value="Upload" type="submit" />
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="upload_data_stn" class="easyui-window" title="Upload Data STN" style="width:450px;height:auto; padding:10px;"
            data-options="iconCls:'icon-up',modal:true,collapsible:false,minimizable:false,maximizable:false">
  <form action="<?php echo base_url(); ?>Upload/upload_stn" method="post" enctype="multipart/form-data">
    <table align="center">
      <tr>
        <td style="min-width: 150px!important;">
          <input id="file_stn" class="easyui-filebox" name="file_stn" style="width: 200px; min-height:30px;" />
          <input type="hidden" name="curcname_stn" value="<?php echo $this->router->fetch_class().'/'.$this->router->fetch_method(); ?>">
        </td>
      </tr>
      <tr>
        <td style="min-width: 150px!important;">
         <!-- <input class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_stn" href="" style="min-width:88px !important;min-height:30px !important;" value="Upload" type="submit" />-->
          <!-- <a class="easyui-linkbutton" data-options="iconCls:'icon-'" id="dwn_tmp_stn" onclick='download_stn_template()' href="" style="min-width:88px !important;min-height:30px !important;" value="Download Template">Download Template</a> -->
          <a href="#" class="easyui-linkbutton" id="dwn-tmp-stn" onclick='download_stn_template()' style="width:auto;height:30px">Download Template</a>
        </td>
      </tr>
    </table>
    <hr>
    <h4>TABLE MASTER PENAMBAH / PENGURANG STN</h4>
    <table class="easyui-datagrid" style="width:auto;height:auto" data-options="fitColumns:true">
      <thead>
        <tr>
          <th data-options="field:'PENAMBAH',width:100">PENAMBAH</th>
          <th data-options="field:'PENGURANG',width:100">PENGURANG</th>
        </tr>
      </thead>
      <tr>
        <td>Bayar Sisa/Lebih RRAK</td>
        <td>Kurset Sales (Fisik)</td>
      </tr>
      <tr>
        <td>Bayar Kurset RRAK</td>
        <td>Kurset Sales (UPAL)</td>
      </tr>
      <tr>
        <td>Bayar Kurset Sales</td>
        <td>Kurset Sales (Uang Rusak)</td>
      </tr>
      <tr>
        <td>Lain - lain/Lebih Setor</td>
        <td>Kurset Sales (Promosi)</td>
      </tr>
      <tr>
        <td>Bayar Kurset Virtual</td>
        <td>Kurset Sales (Varian)</td>
      </tr>
      <tr>
        <td></td>
        <td>Kurset Virtual</td>
        <!-- <td>KURANG SETOR RRAK</td> -->
      </tr>
      <tr>
        <td></td>
        <td>Potongan Virtual</td>
        <!-- <td>KURANG SETOR RRAK</td> -->
      </tr>
      <tr>
        <td></td>
        <td>Potongan RRAK</td>
        <!-- <td>KURANG SETOR RRAK</td> -->
      </tr>
      <tr>
        <td></td>
        <td>Potongan Lain - lain</td>
        <!-- <td>KURANG SETOR RRAK</td> -->
      </tr>
    </table>
  </form>
</div>

<?php echo $this->session->flashdata('msg'); ?>

<?php echo $this->session->flashdata('form_shift'); ?>

<div id="form_close_shift" class="easyui-window" title="Warning !" style="width:300px;height:140px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Apakah anda ingin menutup Shift ?</h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="ya_close" userid="<?php echo $this->session->userdata('usrId'); ?>">Ya</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_close" >Tidak</a></center>
          </div>
    </div>
</div>

<div id="form_cl_shift" class="easyui-window" title="Warning !" style="width:300px;height:140px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
    <div class="easyui-layout" data-options="fit:true,closed:true">
          <div data-options="region:'center'">
                <center><h4>Apakah anda ingin menutup Shift ?</h4></center>
                <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="ya_cl" userid="<?php echo $this->session->userdata('usrId'); ?>">Ya</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_cl" >Tidak</a></center>
          </div>
    </div>
</div>

<div id="req_diff_journal" class="easyui-window" title="Sales Difference Journal" style="width:350px;height:270px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="diff_journal_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Kode Gudang</td>
        <td style="min-width: 135px!important;">
          <select id="diff_journal_dc_code" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_dj" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_dj" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Owner Type</td>
        <td style="min-width: 135px!important;">
          <select id="store_type_dj" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="all">All</option>
            <option value="R">Reguler</option>
            <option value="F">Franchise</option>
          </select>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_diff_journal" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="rep_pending_setor_toko" class="easyui-window" title="Detail Toko Pending Sales" style="width:350px;height:350px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            
            <input id="pst_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:135px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_pst" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_pst" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sort By </td>
        <td style="min-width: 135px!important;">
          <select id="sort_pst" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="QAsc">Qty Asc</option>
            <option value="QDesc">Qty Desc</option>
             <option value="RAsc">Rp Asc</option>
            <option value="RDesc">Rp Desc</option>
          </select>
        </td>
      </tr>
       <tr>
        <td style="min-width: 115px!important;">Jumlah toko yang ingin ditampilkan</td>
        <td style="min-width: 135px!important;">
          <select id="jumlah_toko_pst" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="5">5</option>
            <option value="10">10</option>
             <option value="20">20</option>
            <option value="30">30</option>
             <option value="50">50</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><br></td>
      </tr>
      <tr>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pst_pdf" style="min-width:133px !important;min-height:30px !important;">PDF</a>
        </td>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pst_csv" style="min-width:133px !important;min-height:30px !important;">CSV</a>
        </td>
      </tr>
      
    </table>
</div>

<div id="rep_sales_toko_idm" class="easyui-window" title="Laporan Absensi Sales Toko per Shift" style="width:500;height:300px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Tampilan Cetak</td>
        <td style="min-width: 135px!important;">
            
            <select id="tampilan_cetak" class="easyui-combobox" name="tampilan_cetak" style="width:200px;">Tampilan Cetak
              <option value="per Toko" selected>per Toko</option>
              <option value="per Cabang">per Cabang</option>
         
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            <input id="absensi_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:200px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
  
       <tr>
        <td style="min-width: 115px!important;">Kode - Nama Toko</td>
        <td style="min-width: 135px!important;">
            <input id="absensi_toko" type="text" class="easyui-combobox"  data-options="valueField:'STORE_CODE',textField:'STORE',url:'<?php echo base_url(); ?>Report/choose_store?>'" style="width:200px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_absensi" style="width:200px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_absensi" style="width:200px; height:30px;">
        </td>
      </tr>
  
      <tr>
        <td><br></td>
      </tr>
      <tr>
      
       
       <td align="right">
          <a class="easyui-linkbutton" id="reset_absensi" style="min-width:133px !important;min-height:30px !important;">Reset</a>
        </td>
         <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_absensi" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
       
      </tr>
      
    </table>
</div>


<div id="rep_sales_region" class="easyui-window" title="Rekap Monitoring Penerimaan Sales" style="width:350px;height:270px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            
            <input id="mps_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:135px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_mps" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_mps" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Report Type</td>
        <td style="min-width: 135px!important;">
          <select id="report_type" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="Qty">Qty</option>
            <option value="Rp">Rp</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><br></td>
      </tr>
      <tr>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_mps_pdf" style="min-width:133px !important;min-height:30px !important;">PDF</a>
        </td>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_mps_csv" style="min-width:133px !important;min-height:30px !important;">CSV</a>
        </td>
      </tr>
    </table>
</div>





<div id="rep_penerimaan_sales_detil" class="easyui-window" title="Detail Penerimaan Sales" style="width:350px;height:270px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            
            <input id="psd_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:135px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_psd" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_psd" style="width:135px; height:30px;">
        </td>
      </tr>
      
       <tr>
        <td><br></td>
      </tr>
      <tr>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_psd_pdf" style="min-width:133px !important;min-height:30px !important;">PDF</a>
        </td>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_psd_csv" style="min-width:133px !important;min-height:30px !important;">CSV</a>
        </td>
      </tr>
    </table>
</div>



<div id="rep_penerimaan_sales_per_cbg" class="easyui-window" title="Penerimaan Sales per Cabang" style="width:350px;height:270px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            
            <input id="psc_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:135px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_psc" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_psc" style="width:135px; height:30px;">
        </td>
      </tr>
        <tr>
        <td><br></td>
      </tr>
      <tr>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_psc_pdf" style="min-width:133px !important;min-height:30px !important;">PDF</a>
        </td>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_psc_csv" style="min-width:133px !important;min-height:30px !important;">CSV</a>
        </td>
      </tr>
    </table>
</div>


<div id="rep_sales_toko" class="easyui-window" title="Penerimaan Sales per Toko" style="width:350px;height:270px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
            
            <input id="rst_branch" type="text" class="easyui-combobox" data-options="valueField:'BRANCH_ID',textField:'BRANCH_VALUE',url:'<?php echo base_url(); ?>Report/choose_branch?>'" style="width:135px; height:30px;">
          
          <!-- <select id="mps_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 7) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select> -->
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="start_date_rst" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="end_date_rst" style="width:135px; height:30px;">
        </td>
      </tr>
       <tr>
        <td><br></td>
      </tr>
      <tr>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_rst_pdf" style="min-width:133px !important;min-height:30px !important;">PDF</a>
        </td>
      
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_rst_csv" style="min-width:133px !important;min-height:30px !important;">CSV</a>
        </td>
      </tr>
    </table>
</div>


<div id="req_mtr_dana_sales" class="easyui-window" title="Monitoring Setoran Dana Sales" style="width:383px;height:197px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="mtr_dana_branch" class="easyui-combobox" name="" style="width:214px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Kode Toko</td>
        <td style="min-width: 135px!important;">
          <select id="store_mtr" class="easyui-combobox" name="" style="width: 214px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Tanggal</td>
        <td style="min-width: 135px!important;">
          <input class="easyui-datebox" id="start_date_mtr" style="width:100px; height:30px;"/>
            -
          <input class="easyui-datebox" id="end_date_mtr" style="width:100px; height:30px;"/>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="left">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_mtr_dana" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_mtr_dana_sales_shift" class="easyui-window" title="Monitoring Setoran Dana Sales" style="width:383px;height:197px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="mtr_dana_branch_shift" class="easyui-combobox" name="" style="width:214px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Kode Toko</td>
        <td style="min-width: 135px!important;">
          <select id="store_mtr_shift" class="easyui-combobox" name="" style="width: 214px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Tanggal</td>
        <td style="min-width: 135px!important;">
          <input class="easyui-datebox" id="start_date_mtr_shift" style="width:100px; height:30px;"/>
            -
          <input class="easyui-datebox" id="end_date_mtr_shift" style="width:100px; height:30px;"/>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="left">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_mtr_dana_shift" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<!-- START IWAN CODE -->
<div id="req_listing_gtu" class="easyui-window" title="Listing Giro Tukar Uang" style="width:300px;height:245px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="listing_gtu_branch" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Kode Gudang</td>
        <td style="min-width: 135px!important;">
          <select id="listing_gtu_dc_code" class="easyui-combobox" name="" style="width:135px; height:30px;" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Batch Date From</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date1_listing_gtu" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Batch Date To</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date2_listing_gtu" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_listing_gtu" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>


<div id="req_monitoring_kodel" class="easyui-window" title="Monitoring Penerimaan Kodel" style="width:350px;height:195px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Tgl Kirim Barang</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date1_monitoring_kodel" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Status Kirim Barang</td>
        <td>
          <select id="status_kirim_barang" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="A">All</option>
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Status Kodel</td>
        <td>
          <select id="status_kodel" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="A">All</option>
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_monitoring_kodel" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>


<div id="req_penerimaan_sales" class="easyui-window" title="Monitoring Penerimaan Sales Toko" style="width:350px;height:160px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Tgl Input Sales</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date1_penerimaan_sales" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Sales Pending Flag</td>
        <td>
          <select id="sales_pending_flag" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="A">All</option>
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_penerimaan_sales" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>


<div id="req_receipt_sales" class="easyui-window" title="Receipt Sales (Qty)-Handheld" style="width:300px;height:130px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Scan Date</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date1_receipt_sales" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_receipt_sales" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_monitoring_voucher_perToko" class="easyui-window" title="Monitoring Voucher per Toko" style="width:450px;height:250px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center" border="0">
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 150px!important;">
          <select id="monitoring_voucher_perToko_branch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Kode Gudang</td>
        <td style="min-width: 150px!important;">
          <select id="monitoring_voucher_perToko_dc_code" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 70px!important;"> Kode Toko </td>
        <td>
          <select class="easyui-combobox" name="batch_number" id="combo_monitoring_voucher_perToko" style="width:265; height:30px;">
        </td>
      </tr>
      <tr>
        <td> Tanggal Awal </td>
        <td>
          <select class="easyui-datebox" name="batch_number" id="date1_monitoring_voucher_perToko" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td> Tanggal Berakhir </td>
          <td>
            <select class="easyui-datebox" name="batch_number" id="date2_monitoring_voucher_perToko" style="width:135px; height:30px;">
          </td>
      </tr>
      <tr>
        <td colspan="2" align="right"> <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_monitoring_voucher_perToko">Submit</a> </td>
      </tr>
    </table>
</div>


<div id="req_pending_sales" class="easyui-window" title="Report Pending Sales" style="width:300px;height:160px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Per Tanggal</td>
        <td style="min-width: 135px!important;">
          <select class="easyui-datebox" id="date1_pending_sales" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Include Data Go</td>
        <td style="min-width: 135px!important;">
          <select id="combo_pending_flag" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="right">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pending_sales" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>


<div id="req_receipt_register" class="easyui-window" title="Receipt Register*" style="width:410px;height:300px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center" border="0">
      <tr>
        <td style="min-width: 100px!important;">Cabang</td>
        <td style="min-width: 150px!important;">
          <select id="receipt_register_branch" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Kode Gudang</td>
        <td style="min-width: 150px!important;">
          <select id="receipt_register_dc_code" class="easyui-combobox" name="" style="width: 200px; min-height:30px;" <?php if ($this->session->userdata('role_id') < 3) {?> dc-code="<?php echo $this->session->userdata('dc_code'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td> Tanggal Awal </td>
        <td>
          <select class="easyui-datebox" name="batch_number" id="date1_receipt_register" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td> Tanggal Berakhir </td>
          <td>
            <select class="easyui-datebox" name="batch_number" id="date2_receipt_register" style="width:135px; height:30px;">
          </td>
      </tr>
      <tr>
        <td style="min-width: 70px!important;"> Store Code Begin </td>
        <td>
          <select class="easyui-combobox" name="batch_number" id="combo1_receipt_register" style="width:210px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 70px!important;"> Store Code End </td>
        <td>
          <select class="easyui-combobox" name="batch_number" id="combo2_receipt_register" style="width:210px; height:30px;">
        </td>
      </tr>
      <!-- <tr>
        <td style="min-width: 115px!important;">Create CSV (Y/N)</td>
        <td style="min-width: 135px!important;">
          <select id="combo_receipt_register" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr> -->
      <tr>
        <td></td>
        <td>
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_receipt_register" style="min-width:133px !important;min-height:30px !important;">Submit</a>
      </td>
      </tr>
    </table>
</div>



<div id="req_sales_tgl_am" class="easyui-window" title="Sales per Tanggal per AM AS" style="width:410px;height:200px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false">
    <table align="center" border="0">
      <tr>
        <td> Tanggal Proses </td>
        <td>
          <select class="easyui-datebox" id="date1_sales_tgl_am" style="width:135px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 70px!important;"> Area Manager </td>
        <td>
          <select class="easyui-combobox" id="combo1_sales_tgl_am" style="width:210px; height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Status Pending Sales </td>
        <td style="min-width: 135px!important;">
          <select id="combo2_sales_tgl_am" class="easyui-combobox" name="" style="width: 135px; min-height:30px;">
            <option value="all">All</option>
            <option value="Y">Yes</option>
            <option value="N">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="right"> <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_sales_tgl_am">Submit</a> </td>
      </tr>
    </table>
</div>

<div id="form_choose_branch_ch" class="easyui-window" title="Pilih Cabang"  style="width:360px;height:170px; padding:10px;" data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true">
  <div data-options="region:'center'">
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
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_admin_branch" href="" style="min-width:88px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
  </div>
</div>

<div id="form_detail_plus_minus" class="easyui-window" title="Detail Data Penambah dan Pengurang"  style="width:380px;height:230px; padding:10px;" data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true">
  <div data-options="region:'center'">
    <table>
      <tr>
        <td style="min-width: 100px!important;">Deposit Num</td>
        <td style="min-width: 150px!important;">
          <input type="text" class="easyui-textbox" id="pm_dep_num" style="width: 225px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Batch Num</td>
        <td style="min-width: 150px!important;">
          <input type="text" class="easyui-textbox" id="pm_batch_num" style="width: 225px; min-height:30px;">
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Deposit Date</td>
        <td style="min-width: 150px!important;">
          <input type="text" class="easyui-datebox" id="pm_start_date" style="width: 100px; min-height:30px;" required>
          To
          <input type="text" class="easyui-datebox" id="pm_end_date" style="width: 100px; min-height:30px;" required>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;">Type</td>
        <td style="min-width: 150px!important;">
          <select class="easyui-combobox" id="pm_type" name="" style="width: 225px; min-height:30px;">
            <option value="ALL">ALL</option>
            <option value="plus">Penambah</option>
            <option value="minus">Pengurang</option>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 150px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pm" href="" style="min-width:100px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
  </div>
</div>


<!--emma 25-11-2019 -->
<div id="form_print_report" class="easyui-window" title="Print Report "  style="width:380;height:100px; padding:10px;" data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true">
  <div data-options="region:'center'">
     <table>
    
      <tr>
        <td style="min-width: 70px!important;"></td>
        <td style="min-width: 50px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pm_pdf" name='sub_pm_pdf' href="" style="min-width:50px !important;min-height:30px !important;">PDF</a>
        </td>
        <td style="min-width: 100px!important;"></td>
        <td style="min-width: 10px!important;">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_pm_csv" href="" style="min-width:50px !important;min-height:30px !important;">CSV</a>
            <input type="hidden" class="easyui-textbox" id="url" >
        </td>
        
          
      </tr>
    </table>
  </div>
</div>



<div id="req_kurset_per_shift" class="easyui-window" title="Rincian Kurset Per Shift" style="width:383px;height:197px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="kurset_branch_shift" class="easyui-combobox" name="" style="width:214px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Kode Toko</td>
        <td style="min-width: 135px!important;">
          <select id="store_kurset_shift" class="easyui-combobox" name="" style="width: 214px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Tanggal</td>
        <td style="min-width: 135px!important;">
          <input class="easyui-datebox" id="start_date_kurset_shift" style="width:100px; height:30px;"/>
            -
          <input class="easyui-datebox" id="end_date_kurset_shift" style="width:100px; height:30px;"/>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="left">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_kurset_shift" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_lebset_per_shift" class="easyui-window" title="Rincian Lebset Per Shift" style="width:383px;height:197px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="lebset_branch_shift" class="easyui-combobox" name="" style="width:214px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Kode Toko</td>
        <td style="min-width: 135px!important;">
          <select id="store_lebset_shift" class="easyui-combobox" name="" style="width: 214px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Tanggal</td>
        <td style="min-width: 135px!important;">
          <input class="easyui-datebox" id="start_date_lebset_shift" style="width:100px; height:30px;"/>
            -
          <input class="easyui-datebox" id="end_date_lebset_shift" style="width:100px; height:30px;"/>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="left">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_lebset_shift" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>

<div id="req_kurset_per_toko" class="easyui-window" title="Rincian Kurset Per Toko" style="width:383px;height:197px; padding:10px;"
            data-options="iconCls:'icon-script',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true">
    <table align="center">
      <tr>
        <td style="min-width: 115px!important;">Cabang</td>
        <td style="min-width: 135px!important;">
          <select id="kurset_branch_toko" class="easyui-combobox" name="" style="width:214px; height:30px;" <?php if ($this->session->userdata('role_id') < 5) {?> branch-id="<?php echo $this->session->userdata('branch_id'); ?>" disabled <?php } ?> required>
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">AM</td>
        <td style="min-width: 135px!important;">
          <select id="am_kuset_toko" class="easyui-combobox" name="" style="width: 214px; min-height:30px;">
          </select>
        </td>
      </tr>
      <tr>
        <td style="min-width: 115px!important;">Tanggal</td>
        <td style="min-width: 135px!important;">
          <input class="easyui-datebox" id="start_date_kurset_toko" style="width:100px; height:30px;"/>
            -
          <input class="easyui-datebox" id="end_date_kurset_toko" style="width:100px; height:30px;"/>
        </td>
      </tr>
      <tr>
        <td> </td>
        <td align="left">
          <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="sub_kurset_toko" style="min-width:133px !important;min-height:30px !important;">Submit</a>
        </td>
      </tr>
    </table>
</div>
<div id="pb" class="easyui-progressbar" style="width:400px;"></div>
<script>

function download_stn_template(){
  window.open(base_url+'Upload/download_stn_template');
}

$(document).ready(function() {
  $("#form_print_report").window('close');

$('#tampilan_cetak').combobox({
  onSelect: function(row){
    if(row.value=='per Cabang'){

      $('#absensi_toko').combobox('setValue','');
      $('#absensi_toko').combobox('disable');

       $('#absensi_branch').combobox('enable');

    }else if(row.value=='per Toko'){
      $('#absensi_branch').combobox('disable');
      $('#absensi_branch').combobox('setValue','');    
        $('#absensi_toko').combobox('enable');
    }else{
        $('#absensi_branch').combobox('enable');
           $('#absensi_toko').combobox('enable');
    }
  }
});
  $('#start_date_mtr_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#end_date_mtr_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#start_date_kurset_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#end_date_kurset_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#start_date_lebset_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#end_date_lebset_shift').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#start_date_kurset_toko').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });

$('#end_date_kurset_toko').datebox({
    formatter: function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?'0'+d:d)+'-'+(m < 10 ? '0' + m : m)+'-'+y;
  },
    parser:function(s){
    if (!s) return new Date();
    var ss = s.split('-');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
      return new Date(y,m-1,d);
    } else {
      return new Date();
    }
  }
 });



});
 

</script>
<!-- END IWAN CODE-->
