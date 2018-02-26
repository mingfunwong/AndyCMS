
<script>
    var title = '<?php echo $msg ?>';
        text = '';
        type = '<?php echo @$type ?>';
        link = '<?php echo $goto ?>';
    
    function redirect(){
        if (link)
            window.location.href = link;
        else
            history.back()
    }

    swal({ 
      title: title, 
      text: text, 
      type: type,
      animation: false,
    })
    .then(redirect);
</script>
