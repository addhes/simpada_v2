$(document).ready(function() {
    $('.select-submission').select2();
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

  $('body').on('change', '#submission_code', function(event) {
    event.preventDefault();
    var id = $(this).val();
    $.get(("getdata") + "/" + id, function(data) {
      console.log(data.estimated_price);
        $('#estimasi').val(data.estimated_price);       
        formatRp();
    })
});


  function myfunction() {
    var sum = 0;
    var amounts  = document.getElementsByClassName('nominal-detail');
    var estimasi  = document.getElementById("estimasi");
    var estimasivalue = parseFloat(estimasi.value.replace(/[^,\d]/g, ""))
    var cls = '';
    var desc = '';

    for(var i=0; i<(amounts.length); i++) {
      var a = +amounts[i].value.replace(/[^,\d]/g, "");
      sum += parseFloat(a) || 0;
    }

    selisih =  estimasivalue - sum;

    if (estimasivalue > sum) {
      desc = 'Anggaran Lebih';
      cls = 'text-warning';
    }else if(estimasivalue < sum){
      desc = 'Anggaran Kurang';
      cls = 'text-danger';
    }else{
      desc = 'Anggaran Sesuai';
      cls = 'text-success';
    }

    document.getElementById("total").value = sum;
    document.getElementById("selisih").value = selisih;
    $("#desc-selisih").text(desc).removeClass("text-success text-danger text-warning").addClass(cls);

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
    // alert(id);
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#rec-' + id).remove();

    //regnerate index number on table
    $('#tbl_posts_body tr').each(function(index) {
      //alert(index);
      $(this).find('span.sn').html(index+1);
    });
    myfunction();
    return true;
  } else {
    return false;
  }
});

