$(document).ready(function(){
  init_parsley();
});
function init_parsley() {
    
  if( typeof (parsley) === 'undefined'){ return; }
  console.log('init_parsley');
  
  $/*.listen*/('parsley:field:validate', function() {
    validateFront();
  });
  $("form[type='submit']").on('click', function() {
    $('form').parsley().validate();
    validateFront();
  });
  var validateFront = function() {
    if (true === $('form').parsley().isValid()) {
      $('.bs-callout-info').removeClass('hidden');
      $('.bs-callout-warning').addClass('hidden');
    } else {
      $('.bs-callout-info').addClass('hidden');
      $('.bs-callout-warning').removeClass('hidden');
    }
  };
  try {
      hljs.initHighlightingOnLoad();
  } catch (err) {}
};