$( document ).ready(function() {
    myfunction();
  });

  function formatRp() {
    $('.input-element').each(function (index, ele) {
      var cleaveCustom = new Cleave(ele, {
       numeral: true,
       numeralDecimalMark: 'thousand',
       delimiter: '.'
     });
    });
  }

  $('#submission_code').change(function(){
    $.ajax({
      url: "{{ route('getestimated_price') }}",
      data: { 
        submission_code: $("#submission_code").val() 
      },
      dataType:"json",
      type: "get",
      success: function(data){
       // $('#submission_code').append(data);
       document.getElementById("estimasi").value = data.estimated_price;
       formatRp();
       // alert(data.submission_code);
     }
   });

  });

  function myfunction() {
    var sum = 0;
    var amounts  = document.getElementsByClassName('nominal-detail');

    for(var i=0; i<(amounts.length); i++) {
      var a = +amounts[i].value.replace(/[^,\d]/g, "");
      sum += parseFloat(a) || 0;
    }

    document.getElementById("total").value = sum;
    formatRp();
  }

  jQuery(document).delegate('a.add-record', 'click', function(e) {
   e.preventDefault();    
   var content = jQuery('#sample_table tr'),
   size = jQuery('#tbl_posts >tbody >tr').length + 1,
   element = null,    
   element = content.clone();
   element.attr('id', 'rec-'+size);
   element.find('.form-control').attr('required', "true");
   element.find('.delete-record').attr('data-id', size);
   element.appendTo('#tbl_posts_body');
   element.find('.sn').html(size);

   $('.input-element').each(function (index, ele) {
    var cleaveCustom = new Cleave(ele, {
     numeral: true,
     numeralDecimalMark: 'thousand',
     delimiter: '.'
   });
  });
 });

  jQuery(document).delegate('a.delete-record', 'click', function(e) {
   e.preventDefault();    
   var didConfirm = confirm("Are you sure You want to delete row");
   if (didConfirm == true) {
    var id = jQuery(this).attr('data-id');
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#rec-' + id).remove();

    //regnerate index number on table
    $('#tbl_posts_body tr').each(function(index) {
      //alert(index);
      $(this).find('span.sn').html(index+1);
    });
    myfunction()
    return true;
  } else {
    return false;
  }
});