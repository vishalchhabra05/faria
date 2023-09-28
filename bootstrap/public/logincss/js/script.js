function checkForInput(element) {
  // element is passed to the function ^
  
  const $label = $(element).siblings('.label');

  if ($(element).val().length > 0) {
    $label.addClass('input-has-value');
  } else {
    $label.removeClass('input-has-value');
  }
}