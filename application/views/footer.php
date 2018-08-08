<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript">
  
 var path = "<?php echo base_url('quiz'); ?>";  

    $(document).ready(function(){
        $( "body" ).delegate( ".addQues", "click", function() {  
            var parent_id = $(this).data("id");
            jQuery.ajax({
                  type: "POST",
                  url: path+'/addquest',
                  data:'parent_id='+parent_id,
                  success: function(data){
                    $(".input_div_"+parent_id).append(data);
                  }
            });

        });

        $('#frmQues').on("change", '.type', function(){
            var html = "";
            var parent_id = $(this).data("id");
            var quesType = $(this).val();
            jQuery.ajax({
                  type: "POST",
                  url: path+'/addanswer',
                  data:{parent_id:parent_id, questtype: quesType},
                  success: function(data){
                    $('#'+parent_id).html(data);
                  }
            });
        });

    });

    $(document).on('click', '.subquest', function() { 
        var sub_ques_no = $(this).data("sub");
        $(this).parent().find('div.col-md-12.ans').append(question);
    });

    $(document).on('click', '#save', function() { 
      $( "#frmQues" ).submit();
    });    
</script>
</body>
</html>