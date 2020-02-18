function removeRow(id){
  $(id).remove();
  resCount();
}

function resCount(){
  var count = 1;
  $('.labelText').each(function(i, obj){
      $(obj).text('Item #' + count);
      count++;
  });
}

function rremoveRow(id){
  $(id).remove();
  rresCount();
}

function rresCount(){
  var count = 1;
  $('.rlabelText').each(function(i, obj){
      $(obj).text('Item #' + count);
      count++;
  });
}

function mremoveRow(id){
  $(id).remove();
  mresCount();
}

function mresCount(){
  var count = 1;
  $('.mlabelText').each(function(i, obj){
      $(obj).text('Item #' + count);
      count++;
  });
}
