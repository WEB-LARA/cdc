<!-- Button trigger modal -->
<b> User </b>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInput">
  Input User
</button>

<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalView">
  View User
</button>

<!-- Modal1 -->
<div class="modal fade" id="modalInput" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center> <h4 class="modal-title" id="myModalLabel"> INPUT USER </h4> </center>
      </div>
      <div class="modal-body">
<!------------------------------------------------------------------->
      <?php
        echo form_open('home/inputUser');
      ?>
        <form class="form-horizontal">
        <fieldset>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">User ID </label>
          <div class="col-md-4">
          <input id="textinput" name="id" type="text" placeholder="user id" class="form-control input-md" autofocus>
          </div>
        </div> <br>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Username </label>
          <div class="col-md-4">
          <input id="textinput" name="username" type="text" placeholder="username" class="form-control input-md">
          </div>
        </div> <br>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Password </label>
          <div class="col-md-4">
          <input id="textinput" name="password" type="password" placeholder="password" class="form-control input-md">
          </div>
        </div> <br>

        <!-- Button (Double) -->
        <div class="form-group">
          <div class="col-md-offset-3">
            <button id="button1id" name="button1id" class="btn btn-success">Simpan</button>
            <button id="button2id" name="button2id" class="btn btn-danger">Batalkan</button>
          </div>
        </div>

        </fieldset>
        </form>
      </form>

<!------------------------------------------------------------------->
      </div>
      <div class="modal-footer">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------->
<div class="modal fade bs-example-modal-lg" id="modalView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center> <h4 class="modal-title" id="myModalLabel"> VIEW USER </h4> </center>
      </div>
      <div class="modal-body">
<!------------------------------------------------------------------->
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role-ID</th>
            <th>Branch-ID</th>
            <th>Active-Date</th>
            <th>Last-Update</th>
          </tr>
        </thead>

        <tbody>
            <?php
                  foreach ($user as $row){
                    echo "<tr> <td> ".$row->USER_ID."</td>";
                    echo "     <td> ".$row->USER_NAME."</td>";
                    echo "     <td> ".$row->PASSWORD."</td>";
                    echo "     <td> ".$row->ROLE_ID."</td>";
                    echo "     <td> ".$row->BRANCH_ID."</td>";
                    echo "     <td> </td>";
                    echo "     <td> </td>";
                    //echo "<br>";
                    echo "     <td>". anchor('home/editUser/'.$row->USER_ID,'edit');
                    echo "&nbsp ";
                    echo anchor('home/deleteUser/'.$row->USER_ID,'delete') ."<td></tr>";
                  }
            ?>
        </tbody>
      </table>

<!------------------------------------------------------------------->
      </div>
      <div class="modal-footer">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<br>
<?php
  $a="iwan";
  echo md5($a);
  ?>
