<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header'); ?>

<div class="container">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Add new call</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
            </div>
            <div class="panel-body">
                <div class="control-group" id="fields">
                    <div class="controls" id="profs"> 
                        <form class="input-append" method="post" id='frmQues' action="<?php echo base_url('quiz/savedata'); ?>">   
                        	<div class="input_div_1"></div>
                        </form>
                        <br>
                        <label  class="addQues btn add-more pull-right" data-id="1">+Add new question</label>
                        <div class="col-md-12 clearfix">
                            <button class="btn btn-info" id="save" type="submit">Save</button>
                            <button class="btn" id="reset" type="button">Cancel</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer'); ?>