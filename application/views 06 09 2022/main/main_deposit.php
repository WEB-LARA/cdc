 <script type="text/javascript" src="<?php echo base_url();?>assets/js/deposit.js"></script>   
    <div id="p" class="easyui-panel" title="Deposit"
            data-options="iconCls:'icon-save',region:'center'" style="padding:10px;">
            <div align="right" style="padding:5px; margin-right:2%">
                  <b> &nbsp
                        <span id="date_time" align="right"></span>
                        <script type="text/javascript">window.onload = date_time('date_time');</script>
                  </b>
            </div>
            <input type="hidden" id="user_role" value="<?php echo $role; ?>">
      	<table style="margin-bottom: 10px;">
      		<tr>
      			<td>Bank</td>
      			<td>
      				<select id="bank" class="easyui-combobox" name="bank" style="width:219px;" required="required">
				        	<option value=""></option>
                                    <?php foreach ($bank as $key) {?>
					      <option value="<?php echo $key->id; ?>"><?php echo $key->name; ?></option>
					      <?php } ?>
					</select>
      			</td>
      			<td style="min-width:20px;"></td>
      			<td rowspan="2">Actual Total Selected</td>
                        <td rowspan="2">
                              <input class="easyui-numberbox" type="text" name="ats" id="ats" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
                        </td>
                        <td style="min-width:20px;"></td>
                        <td>
                              <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'" id="editdep" style="min-width:150px !important;min-height:28px !important;">Edit</a>
                              <input type="hidden" values="" id="depid">
                        </td>
      		</tr>
      		<tr>
      			<td>Deposit Num</td>
      			<td>
		        		<input class="easyui-textbox" type="text" name="deposit_num" id="deposit_num" data-options="required:true" style="min-width:219px !important;"/>
      			</td>
      			<td style="min-width:20px;"></td>
      		</tr>
      		<tr>
      			<td>Deposit Date &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
      			<td>
      				<input class="easyui-datebox" name="deposit_date" id="deposit_date" data-options="required:true,showSeconds:true" style="width:100px !important;">
                              <select class="easyui-combobox" name="deposit_date" id="deposit_jam" data-options="required:true,showSeconds:true" style="width:50px !important;">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="00">00</option>
                              </select>
                              <span>:</span>
                              <select class="easyui-combobox" name="deposit_date" id="deposit_min" data-options="required:true,showSeconds:true" style="width:50px !important;">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="12">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="22">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                    <option value="34">34</option>
                                    <option value="35">35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                    <option value="46">46</option>
                                    <option value="47">47</option>
                                    <option value="48">48</option>
                                    <option value="49">49</option>
                                    <option value="50">50</option>
                                    <option value="51">51</option>
                                    <option value="52">52</option>
                                    <option value="53">53</option>
                                    <option value="54">54</option>
                                    <option value="55">55</option>
                                    <option value="56">56</option>
                                    <option value="57">57</option>
                                    <option value="58">58</option>
                                    <option value="59">59</option>
                                    <option value="00">00</option>
                              </select>
      			</td>
                        <td style="min-width:20px;"></td>
                        <td rowspan="2">Check Exc Total Selected</td>
                        <td rowspan="2">
                              <input class="easyui-numberbox" type="text" name="cts" id="cts" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
                        </td>
                        <td style="min-width:20px;"></td>
                        <td>
                              <a class="easyui-linkbutton" data-options="iconCls:'icon-no'" depid="" depnum="" id="del_deposit" style="min-width:150px !important;min-height:28px !important; display:none;">Delete</a>
                        </td>
      		</tr>
      		<tr>
      			<td>Mutation Date</td>
      			<td><input type="text" class="easyui-datebox" name="mutation_date" id="mutation_date" data-options="required:true" style="min-width: 219px;"></td>
      		</tr>
      		<tr>
      			<td>Status</td>
      			<td>
      				<input class="easyui-textbox" type="text" name="status" id="status" data-options="required:true,disabled:true" style="min-width:219px !important;" value="New"/>
      			</td>
                        <td style="min-width:20px;"></td>
                        <td rowspan="2">Deposit Total Selected</td>
                        <td rowspan="2">
                              <input class="easyui-numberbox" type="text" name="dts" id="dts" data-options="required:true,disabled:true,min:0,precision:2,groupSeparator:','" style="min-width:200px !important;min-height:50px !important;"/>
                        </td>
                        <td style="min-width:20px;"></td>
      		</tr>
      		<tr>
      			<td></td>
      			<td>
      				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload'" id="requery" style="min-height:28px;min-width:219px;">Requery</a>
                              <a class="easyui-linkbutton" data-options="iconCls:'icon-brush'" id="clear" style="display:none;min-height:28px;min-width:10px;">Clear</a>
      			</td>
      		</tr>
      	</table>
      	<table id="data_batch"></table>
            <table style="margin-top: 10px;">
                  <td>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" id="save" depid="" style="min-height:28px;min-width:150px;">Save</a>
                  </td>
                  <td>
                        <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="validate" style="min-height:28px;min-width:150px;display:none;" depid="">Validate</a>
                  </td>
            </table>
    </div>
      <div id="isian" class="easyui-window" title="Warning !" style="width:300px;height:130px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false">
            <div class="easyui-layout" data-options="fit:true,closed:true">
                  <div data-options="region:'center'">
                        <center><h4>Form Harus Diisi Dengan Lengkap!</h4></center>
                        <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="close_warn" depid="">Yes</a></center>
                  </div>
            </div>
      </div>
      <div id="sub" class="easyui-window" title="Warning !" style="width:300px;height:150px;"
            data-options="iconCls:'icon-save',modal:true">
            <div class="easyui-layout" data-options="fit:true,closed:true">
                  <div data-options="region:'center'">
                        <center><h4>Data Deposit berhasil disubmit. Klik 'Yes' untuk Validate.</h4></center>
                        <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="yes_val" depid="">Yes</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_val" >No</a></center>
                  </div>
            </div>
      </div>
      <div id="win-deposit" class="easyui-window" title="Deposit" style="width:1250px;height:500px;"
            data-options="iconCls:'icon-save',modal:true,collapsible:false,minimizable:false,maximizable:false">
            <div id="p" class="easyui-panel" title="Search" style="width:100%;height:180px;padding:10px;background:#fafafa;" data-options="iconCls:'icon-search',closable:false,collapsible:false,minimizable:false,maximizable:false">
                  <div id="tb" style="padding:3px">
                        <table>
                              <tr>
                                    <td style="min-width: 150px!important;">Bank</td>
                                    <td><input type="text" id="bank_sc" class="easyui-combobox" style="min-width:200px !important;"></td>
                                    <td style="min-width:20px;"></td>
                                    <td style="min-width: 150px!important;">Mutation Date</td>
                                    <td>
                                          <input class="easyui-datebox" type="text" id="mutation_date_sc" data-options="required:false,disabled:false,prompt:'Mutation Date'" style="min-width:200px !important;"/>
                                    </td>
                              </tr>
                              <tr>
                                    <td>Deposit Number</td>
                                    <td>
                                          <input class="easyui-textbox" type="text" id="deposit_num_sc" data-options="required:false,disabled:false,prompt:'Deposit Number'" style="min-width:200px !important;"/>
                                    </td>
                                    <td style="min-width:20px;"></td>
                                    <td style="min-width: 150px!important;">User Name</td>
                                    <td>
                                          <input class="easyui-textbox" type="text" id="username_sc" data-options="required:false,disabled:false,prompt:'Username'" style="min-width:200px !important;"/>
                                    </td>
                                    <!-- <td style="min-width: 150px!important;">Status</td>
                                    <td>
                                        <select id="status_sc" class="easyui-combobox" style="width:200px !important;">
                                                  <option value="N">New</option>
                                                  <option value="V">Validated</option>
                                              </select>
                                    </td> -->
                              </tr>
                              <tr>
                                    <td>Deposit Date</td>
                                    <td>
                                          <input class="easyui-datebox" type="text" id="deposit_date_sc" data-options="required:false,disabled:false,prompt:'Deposit Date'" style="min-width:200px !important;"/>
                                    </td>
                                    <td style="min-width:20px;"></td>
                              </tr>
                              <tr>
                                    <td style="min-width:20px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                          <a href="#" class="easyui-linkbutton" plain="false" onclick="doSearch()" data-options="iconCls:'icon-search'" style="min-width:116px !important;min-height:30px !important;">Search</a>
                                          <a id="rrr" class="easyui-linkbutton" plain="false" data-options="iconCls:'icon-reload'" style="min-height:30px !important;min-width:76px !important;">Refresh</a>
                                    </td>
                              </tr>
                        </table>
                  </div>
            </div>
            <div class="easyui-layout" data-options="fit:true,closed:true">
                  <!-- <div id="tb" style="padding:3px">
                        <input class="easyui-textbox" type="text" id="deposit_num_sc" data-options="required:false,disabled:false,prompt:'Deposit Number'" style="min-width:200px !important;"/>
                        <input class="easyui-datebox" type="text" id="deposit_date_sc" data-options="required:false,disabled:false,prompt:'Deposit Date'" style="min-width:200px !important;"/>
                        <input class="easyui-datebox" type="text" id="mutation_date_sc" data-options="required:false,disabled:false,prompt:'Mutation Date'" style="min-width:200px !important;"/>
                        <a href="#" class="easyui-linkbutton" plain="false" onclick="doSearch()">Search</a>
                        <a id="rrr" class="easyui-linkbutton" plain="false" data-options="iconCls:'icon-reload'"></a>
                  </div> -->
                  <table id="data_deposit"></table>
                  <div data-options="region:'south',split:false" style="height:50px">
                        Double Click to Choose.
                  </div>
            </div>
      </div>
      <div id="valdep" class="easyui-window" title="Caution" style="width:300px;height:95px;"
            data-options="iconCls:'icon-save',modal:true">
            <div class="easyui-layout" data-options="fit:true,closed:true">
                  <div data-options="region:'center'">
                        <center><h4>Deposit Berhasil Divalidasi</h4></center>
                  </div>
            </div>
      </div>

      <div id="deldep" class="easyui-window" title="Warning !" style="width:350px;height:180px;"
            data-options="iconCls:'icon-save',modal:true">
          <div class="easyui-layout" data-options="fit:true,closed:true">
                <div data-options="region:'center'">
                      <center><h4>Apakah anda yakin menghapus deposit <p id="dep_num_del"></p> Klik 'Yes' untuk Menghapus.</h4></center>
                      <center><a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="yes_del" depid="">Yes</a>&nbsp&nbsp&nbsp&nbsp<a class="easyui-linkbutton" data-options="iconCls:'icon-no'" id="no_del" >No</a></center>
                </div>
          </div>
      </div>

      <div id="notdeldep" class="easyui-window" title="Caution" style="width:300px;height:95px;"
                  data-options="iconCls:'icon-save',modal:true">
          <div class="easyui-layout" data-options="fit:true,closed:true">
                <div data-options="region:'center'">
                      <center><h4>Deposit Berhasil Dihapus</h4></center>
                </div>
          </div>
      </div>