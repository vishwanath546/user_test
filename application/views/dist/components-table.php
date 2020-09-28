<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>


      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">

            <div class="section-header-breadcrumb">

                <div class="buttons">


                    <button type="button" class="btn btn-icon btn-primary"  id="user_div"><i class="fa fa-plus"></i></button>

                </div>
            </div>
          </div>
            <div class="row" id="user_section" style="display:none">
                <div class="col col-md-8 " style="float: none;margin: auto;">
                    <div class="card">
                        <form id="add_user_form" name="add_stock_form"  enctype="multipart/form-data" method="post" novalidate="novalidate">

                            <input type="hidden" class="form-control" id="update_user_id" name="update_user_id" value="0">
                        <div class="card-header">
                            <h3><b>Add user</b></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Role Type</label>

                                <select class="form-control" id="role_type" name="role_type">
                                    <option>Project Manager</option>
                                    <option>Task Manager</option>
                                    <option>Client</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" id="f_name" name="f_name">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" id="l_name" name="l_name">
                            </div>
                            <div class="form-group">
                                <label>Email id</label>
                                <input type="text" class="form-control" id="email_id" name="email_id">
                            </div>
                            <div class="form-group">
                                <label>Mobile No</label>
                                <input type="number" min="0" class="form-control" id="mobile_no" name="mobile_no">
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <input type="text"  class="form-control" id="address" name="address">
                            </div>

                               <button type="button" class="btn btn-secondary" id="close_user_div">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>



                        </div>
                    </form>
                    </div>



                </div>

            </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12 >
                <div class="card">
                  <div class="card-header">
                    <h4>User Table</h4>
                  </div>
                  <div class="card-body">

                      <div class="row">
                          <div class="form-group col-6">
                              <label>Role Type</label>

                              <select class="form-control" id="search_role_type" name="search_role_type">
                                  <option value="">Select Role</option>
                                  <option>Project Manager</option>
                                  <option>Task Manager</option>
                                  <option>Client</option>
                              </select>

                          </div>
                      </div>

                      <div class="card">
                          <form id="fetch_date_wise_result">
                          <div class="card-body">
                              <div class="row">


                                  <div class="form-group col-6">
                                      <label for="date">Start Date</label>
                                  <div class="input-group">

                                      <input id="start_date" type="date" class="form-control" name="start_date">
                                  </div>
                              </div>
                              <div class="form-group col-6">
                                  <label for="date">End Date</label>
                                  <div class="input-group">

                                      <input id="end_date" type="date" class="form-control" name="end_date">
                                  </div>
                              </div>

                              </div>
                              <button type="submit" class="btn btn-primary">Search</button>
                          </div>
                          </form>
                      </div>





                    <div class="table-responsive">
                      <table id="user_table"  class="table table-striped responsive display" cellspacing="0">
                        <tr>
                            <thead>

                          <th>user Nmae</th>
                          <th>Email id</th>
                          <th>Mobile NO</th>
                         <th>Role </th>
                          <th>Created on </th>
                          <th>Action</th>
                            </thead>
                        </tr>
                      </table>
                    </div>
                  </div>

                </div>
              </div>






      </div>

</div>
</section>
</div>


<?php $this->load->view('dist/_partials/footer'); ?>

<script type="text/javascript" src="<?= base_url("assets/") ?>js/user_management.js"></script>


