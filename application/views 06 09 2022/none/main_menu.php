<body class="easyui-layout">
  <!--
  <div DATA-OPTIONS="region:'north'" style="height:90px;background:#FFFFFF url(<?php  echo base_url('assets/css/image/logo_indomaret.png');?>) no-repeat;">
  -->
  <div DATA-OPTIONS="region:'north'" style="height:50px; no-repeat;">

      <div style="margin:10px;" align="left"></div>
          &nbsp
<!--
          <a id="btn-home" href="<?php base_url();?>" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-home'">&nbsp HOME</a>
          <a href="#" class="easyui-menubutton" data-options="menu:'#mm1',iconCls:'icon-Script'">MASTER</a>
          <a id="btn-input" href="<?php base_url();?>inputBatch" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit'"><b>&nbsp ENTRY BATCH &nbsp</b></a>
          <a id="btn-deposit" href="<?php base_url();?>inputDeposit" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-Up'"><b>&nbsp DEPOSIT &nbsp</b></a>
          <a href="#" class="easyui-menubutton" data-options="menu:'#mm2',iconCls:'icon-help'">HELP</a>
-->
        <a href="#" class="easyui-menubutton">MASTER</a>

        <?php
        foreach ($menu as $row){
          echo "<a href=".$row->URL." class=easyui-menubutton>".$row->MENU_NAME."</a>";
                          }
        ?>

          &nbsp
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-man'" id="userCheck" > <B><?php echo strtoupper($this->session->userdata('username')); ?></B></a>
          <a class="easyui-linkbutton" DATA-OPTIONS="plain:true,iconAlign:'right',iconCls:'icon-exit'" id="logOut">	<B>LOGOUT</B> </a>

          <div >
          </div>

        <div id="mm1" style="width:150px;">
          <div> <a href="<?php base_url();?>master/Master_Toko"> Master Toko </a> </div>
          <div> <a id="masterBank_btn" > Master Bank </a> </div>
          <div>Master Type</div>
          <div class="menu-sep"></div>
          <div>Detail Penambah</div>
          <div>Detail Minus</div>
          <div class="menu-sep"></div>
          <div>Master Voucher</div>
          <div>Master AMAS</div>
          <div>Master Shift</div>
          <div>Master Branch</div>
          <div>Master instance</div>
          <div class="menu-sep"></div>
        </div>  <!-- END DIV mm1-->


        <div id="mm2" style="width:100px;">
          <div>Help</div>
          <div>About</div>
        </div>  <!-- END DIV mm2-->

      </div>  <!-- END DIV ALIGMNT LEFT-->
   </div>  <!-- END DIV REGION NORTH-->
